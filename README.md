# Arkan — WordPress Theme

A WordPress conversion of the ArchSan architecture/interior HTML template found in `../frontend`.
Every piece of markup, CSS and JS from the template is preserved; the static content has been
replaced with WordPress loops, ACF fields and Contact Form 7.

---

## 1. Requirements

| Requirement | Why |
|---|---|
| WordPress 6.0+ | Block editor, template hierarchy features used here |
| PHP 7.4+ | Short array syntax, null coalescing |
| **Advanced Custom Fields PRO** | Repeater, Gallery and Options Page — the theme registers all field groups in code, so nothing needs importing |
| **Contact Form 7** | Renders the contact page form and the "Let's Talk" bar |

Without ACF the theme still renders — it falls back to the bundled demo images and the default
strings — but nothing is editable. An admin notice reminds you which plugin is missing.

---

## 2. Install

1. Copy this folder to `wp-content/themes/arkan` (the theme root is the folder
   containing `style.css` and `functions.php`).
2. Activate **Appearance → Themes → Arkan**.
3. Install and activate **ACF PRO** and **Contact Form 7**.
4. Visit **Settings → Permalinks** and click Save once (registers the `projects/` and `services/` URLs).

> `frontend/` holds the original HTML template and is **not used at runtime**.
> It adds ~8 MB to the theme; delete it before deploying if you don't need the reference.

---

## 3. First-run setup

### 3.1 Home page

1. Create a page called `Home`.
2. **Settings → Reading → Your homepage displays → A static page → Homepage: Home.**
3. Edit the page — the **Home Page Sections** panel appears with tabs:
   Hero Slider, About Section, Projects Section, Testimonials Section, Latest News Section.

The same panel is also available on any page assigned the **Home Page** template, so a second
landing page can be built without touching Reading settings.

### 3.2 Menu

**Appearance → Menus → Primary Menu.** The walker reproduces the template's markup exactly:

- Depth 0 → `li.nav-item` / `a.nav-link` (a caret is added automatically when the item has children)
- Depth 1 → `li.dropdown-submenu` / `a.dropdown-item`
- Depth 2 → `li` / `a.dropdown-item`

Maximum depth is 3, matching the template's three-level "Pages → Other Pages" submenu.

### 3.3 Pages to create

| Page | Template to assign |
|---|---|
| About | About Page |
| Services | Services Page *(or link straight to the Services archive)* |
| Contact | Contact Page |
| Gallery | Gallery Page |
| FAQ | FAQ Page |
| Process | Process Page |
| Blog | *(no template — set as "Posts page" in Settings → Reading)* |

### 3.4 Theme Options

**Theme Options** in the admin sidebar (ACF options page) holds everything global:

- **General** — default page banner, preloader / scroll-ring / decorative-lines toggles
- **Let's Talk Bar** — background, overlay, copy and the CF7 form ID for the bar above the footer
- **Footer** — up to two offices with address, phone, email and social links, plus the copyright line
- **Archives** — banner and headings for the Projects, Services and Blog archives; projects per page
- **404 Page** — background, title and text

---

## 4. Content model

| HTML page | WordPress equivalent |
|---|---|
| `index.html` | `front-page.php` + `template-parts/home/*` |
| `about.html` | `page-templates/template-about.php` |
| `services.html` | `archive-service.php` and `page-templates/template-services.php` |
| `services-page.html` | `single-service.php` |
| `projects.html` | `archive-project.php` |
| `project-page.html` | `single-project.php` |
| `blog.html` | `index.php` (posts page) + `archive.php` |
| `post.html` | `single.php` + `comments.php` |
| `contact.html` | `page-templates/template-contact.php` |
| `gallery.html` | `page-templates/template-gallery.php` |
| `faq.html` | `page-templates/template-faq.php` |
| `process.html` | `page-templates/template-process.php` |
| `404.html` | `404.php` |

### Post types

| Type | Slug | Public | Fields |
|---|---|---|---|
| `project` | `/projects/` | yes | number, intro, image slider (gallery), year / company / name / location, checklist, "feature in hero" |
| `service` | `/services/` | yes | number, card background, gallery |
| `team` | — | no (UI only) | role, social links |
| `testimonial` | — | no (UI only) | role/company; the quote is the post content, the photo is the featured image |

Taxonomies: `project_category` and `project_tag` (both attached to `project`).
`project_category` drives the isotope filter on the home page and the filter row on the archive.

### Field → design mapping

- **Featured image** = the card / banner image for every type
- **Menu order** (Page Attributes) controls ordering of projects, services, team and testimonials
- Project and service numbers fall back to an auto-generated `01`, `02`… if left blank
- Any field left empty falls back to a bundled demo image, so no layout ever collapses

---

## 5. Contact Form 7

Set the numeric form ID in Theme Options (Let's Talk) and on the Contact page. Paste the markup
below into the CF7 **Form** tab so the fields inherit the design's styling.

### 5.1 "Let's Talk" bar (2 fields + consent, inline)

```
<div class="row">
  <div class="col-md-6 form-group">
    [text* your-name class:line-gray placeholder "Full Name *"]
  </div>
  <div class="col-md-3 form-group">
    [tel* your-phone class:line-gray placeholder "Phone *"]
  </div>
  <div class="col-md-2">
    [submit class:line-gray "Send"]
  </div>
</div>
<div class="row">
  <div class="col-md-12 mt-3">
    [acceptance accept-policy class:line-gray] I agree with the <a href="/privacy-policy/" class="underline line-gray">privacy policy</a> [/acceptance]
  </div>
</div>
```

### 5.2 Contact page form (stacked)

```
<div class="row">
  <div class="col-md-12 form-group">[text* your-name placeholder "Your Name *"]</div>
  <div class="col-md-12 form-group">[email* your-email placeholder "Your Email *"]</div>
  <div class="col-md-12 form-group">[tel* your-phone placeholder "Your Number *"]</div>
  <div class="col-md-12 form-group">[text* your-subject placeholder "Subject *"]</div>
  <div class="col-md-12 form-group">[textarea* your-message rows:4 placeholder "Message *"]</div>
  <div class="col-md-12 mt-2">[submit "Send Message"]</div>
</div>
```

Comments use WordPress' native `comment_form()`, restyled in `comments.php` and
`inc/comment-walker.php` to match `post.html`.

---

## 6. File map

```
<theme root>/
├── style.css                   theme header only (design lives in assets/css/style.css)
├── functions.php               setup, menus, image sizes, sidebars, includes
├── screenshot.png
├── inc/
│   ├── helpers.php             arkan_field/arkan_option/image + CF7 helpers with fallbacks
│   ├── enqueue.php             all CSS/JS, in the template's original order
│   ├── nav-walker.php          Bootstrap 5 walker reproducing the template's menu markup
│   ├── post-types.php          CPTs, taxonomies, archive query tweaks, rewrite flush
│   ├── acf-fields.php          options page + every field group, registered in PHP
│   ├── template-tags.php       banner, section heading, cards, pagination, social links
│   ├── comment-walker.php      comment markup matching .comments .wrap
│   ├── customizer.php          logo, blog layout, accent colour
│   └── admin-notices.php       missing-plugin warnings
├── template-parts/
│   ├── home/                   hero, about, projects, testimonials, blog
│   ├── global/lets-talk.php
│   └── content/                content-excerpt, content-none
├── page-templates/             home, about, services, contact, gallery, faq, process
├── header.php  footer.php  sidebar.php  comments.php  searchform.php
├── front-page.php  index.php  page.php  archive.php  single.php  search.php  404.php
├── archive-project.php  single-project.php  taxonomy-project_category.php  taxonomy-project_tag.php
├── archive-service.php  single-service.php
└── assets/                     css, js, images, fonts, modules (copied from frontend/)
```

---

## 7. Changes made to the original template assets

Four edits were made to `assets/js/script.js`; everything else is byte-identical to `frontend/`.
See §8 if the site ever renders blank.

1. **jQuery wrapper (critical)** — the template's IIFE was `(function () { ... $(window) ... })();`,
   relying on a global `$`. **WordPress ships jQuery in noConflict mode, so `$` does not exist on the
   front end.** The wrapper is now `(function ($) { ... })(jQuery);`. See §9.
2. **Logo path** — the scroll handler hardcoded `images/logo.png`. It now reads the URL injected
   from PHP (`wp_localize_script` → `arkanVars`), so the Customizer logo is respected.
3. **Scroll-to-top ring** — `document.querySelector('.progress-wrap path')` threw when the ring was
   absent, which killed every script after it. It is now guarded, so the ring can be switched off in
   Theme Options.
4. **Contact AJAX** — the legacy `.contact__form` handler now excludes `.wpcf7-form` so it can never
   fight with Contact Form 7's own AJAX.

New files:

- `assets/js/wp-safety.js` — dependency-free fail-safe (see §9)
- `assets/css/wp-overrides.css` — styles for everything WordPress emits that the template never had
  (CF7, comment list, core alignment/caption/gallery classes, pagination `<span class="current">`,
  admin bar offset, screen-reader helpers, footer menu)
- `assets/css/editor-style.css` — dark block-editor styling
- `assets/js/customizer-preview.js` — live preview for the site title and accent colour

---

## 8. Troubleshooting: blank / all-dark page

**Symptom.** The site loads but shows nothing — a solid dark screen, no header, no footer.

**Why this template is prone to it.** Two rules in `assets/css/style.css` make the theme's
JavaScript a single point of failure:

```css
.preloader-bg, #preloader { position: fixed; width: 100%; height: 100%;
                            background: #1b1b1b; z-index: 999999; }
.js .animate-box          { opacity: 0; }
```

The preloader is a full-screen opaque overlay that is **only** removed by
`$("#preloader").fadeOut(700)` in `script.js`, and most sections start invisible until the Waypoints
handler in the same file reveals them. So *any* uncaught error in `script.js` — a jQuery clash, a
plugin conflict, a blocked asset — produces a completely blank dark page with no clue as to why.

**The original cause here** was the jQuery wrapper (§7.1): `$` is undefined under WordPress, so
`var wind = $(window);` threw `TypeError: $ is not a function` on the first line of the IIFE.

**What now prevents a recurrence.** `assets/js/wp-safety.js` is enqueued with **no dependencies**, so
it still runs even when `script.js` dies. `script.js` sets `window.arkanScriptReady = true` on its
last line; if that flag is missing ~2.5s after `load`, the safety script hides the preloader, adds
`arkan-js-fallback` to `<html>` (which forces `.animate-box` visible), and logs a clear warning to
the console. There is also a hard 8s ceiling and a `<noscript>` fallback in `header.php`.

Worst case is now a site **without scroll animations**, never a blank one.

**If you still see a blank page**, check in this order:

1. Browser console — the safety net prints `[Arkan] Theme JavaScript did not finish initialising…`
   followed by the real error.
2. If the page is blank *and* the console is empty, it is a PHP fatal, not JS. Set
   `define( 'WP_DEBUG', true ); define( 'WP_DEBUG_DISPLAY', true );` in `wp-config.php` to see it.
3. Confirm ACF PRO is active — without it the theme renders demo fallbacks, but never blank.

---

## 9. Notes and known limits


- The theme targets **one homepage layout** (`index.html`). The other ten variants in `frontend/`
  are not converted; each would become an extra file in `page-templates/` reusing the same
  `template-parts/home/*` pieces.
- `blog2.html`, `post2.html`, `projects2.html` and `projects3.html` are likewise not converted.
- The archive slugs `projects/` and `services/` can be changed by setting the options
  `arkan_slug_project` / `arkan_slug_service` (see `arkan_cpt_slug()` in `inc/post-types.php`);
  flush permalinks afterwards.
- `.lint.py` is a development helper that structurally checks every PHP file (bracket balance,
  alternative-syntax balance, include targets, undefined `arkan_*` calls). It is safe to delete
  before shipping.
