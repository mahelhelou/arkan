#!/usr/bin/env python3
"""
Lightweight PHP sanity checker for the Arkan theme.

No PHP binary is available in this sandbox, so this script does a
structural check instead of a real parse:

  1. balanced (), {}, [] outside of strings/comments
  2. balanced alternative-syntax blocks (if/endif, foreach/endforeach, ...)
  3. every require/get_template_part target exists on disk
  4. every arkan_* function called is defined somewhere in the theme
"""

import os
import re
import sys

ROOT = os.path.dirname(os.path.abspath(__file__))


def strip_php(src):
    """Remove comments and string bodies, keeping structure intact."""
    out = []
    i = 0
    n = len(src)
    while i < n:
        c = src[i]
        # line comments
        if src.startswith('//', i) or c == '#':
            j = src.find('\n', i)
            i = n if j == -1 else j
            continue
        if src.startswith('/*', i):
            j = src.find('*/', i + 2)
            i = n if j == -1 else j + 2
            out.append(' ')
            continue
        # heredoc / nowdoc
        m = re.match(r"<<<\s*(['\"]?)([A-Za-z_]\w*)\1\r?\n", src[i:])
        if m:
            label = m.group(2)
            end = re.search(r"^\s*" + label + r"\s*;?", src[i:], re.M)
            i = n if not end else i + end.end()
            out.append('""')
            continue
        if c in "'\"":
            quote = c
            i += 1
            while i < n:
                if src[i] == '\\':
                    i += 2
                    continue
                if src[i] == quote:
                    i += 1
                    break
                i += 1
            out.append('""')
            continue
        out.append(c)
        i += 1
    return ''.join(out)


PAIRS = {')': '(', '}': '{', ']': '['}
OPENERS = set(PAIRS.values())

ALT_OPEN = ('if', 'foreach', 'while', 'for', 'switch')
ALT_CLOSE = {
    'endif': 'if',
    'endforeach': 'foreach',
    'endwhile': 'while',
    'endfor': 'for',
    'endswitch': 'switch',
}


def check_file(path):
    problems = []
    with open(path, encoding='utf-8') as fh:
        raw = fh.read()

    if not raw.lstrip().startswith('<?php'):
        problems.append('does not start with <?php')

    if '?>' in raw and raw.rstrip().endswith('?>'):
        problems.append('has a trailing ?> (should be omitted in PHP-only files)')

    code = strip_php(raw)

    # ---- bracket balance -------------------------------------------------
    stack = []
    line = 1
    for ch in code:
        if ch == '\n':
            line += 1
        elif ch in OPENERS:
            stack.append((ch, line))
        elif ch in PAIRS:
            if not stack:
                problems.append('unexpected "%s" on line %d' % (ch, line))
                break
            opener, oline = stack.pop()
            if opener != PAIRS[ch]:
                problems.append(
                    'mismatched "%s" on line %d (opened "%s" on line %d)'
                    % (ch, line, opener, oline)
                )
                break
    if stack:
        opener, oline = stack[-1]
        problems.append('unclosed "%s" opened on line %d' % (opener, oline))

    # ---- alternative syntax ---------------------------------------------
    alt = []
    for m in re.finditer(r'\b(if|elseif|else|foreach|while|for|switch|end(?:if|foreach|while|for|switch))\b\s*(\(?)', code):
        word = m.group(1)
        lineno = code.count('\n', 0, m.start()) + 1
        if word in ALT_CLOSE:
            if not alt:
                problems.append('stray "%s" on line %d' % (word, lineno))
                break
            opened, oline = alt.pop()
            if opened != ALT_CLOSE[word]:
                problems.append(
                    '"%s" on line %d closes "%s" opened on line %d'
                    % (word, lineno, opened, oline)
                )
                break
        elif word in ALT_OPEN:
            # only alternative syntax ends the head with ':' -- find it
            depth = 0
            j = m.end(2) - 1 if m.group(2) else m.end()
            if m.group(2):
                depth = 0
                while j < len(code):
                    if code[j] == '(':
                        depth += 1
                    elif code[j] == ')':
                        depth -= 1
                        if depth == 0:
                            j += 1
                            break
                    j += 1
            k = j
            while k < len(code) and code[k] in ' \t\r\n':
                k += 1
            if k < len(code) and code[k] == ':':
                alt.append((word, lineno))
    if alt:
        word, lineno = alt[-1]
        problems.append('unclosed alternative-syntax "%s" opened on line %d' % (word, lineno))

    return problems, raw


def main():
    php_files = []
    for dirpath, dirnames, filenames in os.walk(ROOT):
        dirnames[:] = [d for d in dirnames if d not in ('assets', 'node_modules')]
        for name in sorted(filenames):
            if name.endswith('.php'):
                php_files.append(os.path.join(dirpath, name))

    all_source = {}
    failures = 0

    for path in sorted(php_files):
        rel = os.path.relpath(path, ROOT)
        problems, raw = check_file(path)
        all_source[rel] = raw
        if problems:
            failures += 1
            print('FAIL %s' % rel)
            for p in problems:
                print('       - %s' % p)
        else:
            print('ok   %s' % rel)

    joined = '\n'.join(all_source.values())

    # ---- required includes ----------------------------------------------
    print('\n-- require targets --')
    for m in re.finditer(r"require(?:_once)?\s+ARKAN_DIR\s*\.\s*'([^']+)'", joined):
        target = os.path.join(ROOT, m.group(1))
        state = 'ok  ' if os.path.exists(target) else 'MISSING'
        if not os.path.exists(target):
            failures += 1
        print('%s %s' % (state, m.group(1)))

    # ---- template parts --------------------------------------------------
    print('\n-- get_template_part targets --')
    seen = set()
    for m in re.finditer(r"get_template_part\(\s*'([^']+)'(?:\s*,\s*'([^']+)')?", joined):
        slug, name = m.group(1), m.group(2)
        candidate = slug + ('-' + name if name else '') + '.php'
        if candidate in seen:
            continue
        seen.add(candidate)
        target = os.path.join(ROOT, candidate)
        exists = os.path.exists(target)
        if not exists:
            failures += 1
        print('%s %s' % ('ok  ' if exists else 'MISSING', candidate))

    # ---- arkan_* function definitions vs. calls --------------------------
    defined = set(re.findall(r'function\s+(arkan_\w+)\s*\(', joined))
    called = set(re.findall(r'\b(arkan_\w+)\s*\(', joined))
    undefined = sorted(called - defined)
    print('\n-- arkan_* functions --')
    print('defined: %d, called: %d' % (len(defined), len(called)))
    if undefined:
        failures += 1
        print('UNDEFINED: %s' % ', '.join(undefined))
    else:
        print('ok   every arkan_* call has a definition')

    unused = sorted(defined - called)
    if unused:
        print('note  defined but never called: %s' % ', '.join(unused))

    print('\n%d file(s) checked, %d problem group(s).' % (len(php_files), failures))
    return 1 if failures else 0


if __name__ == '__main__':
    sys.exit(main())
