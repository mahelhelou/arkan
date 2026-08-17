# Arkan — WordPress Theme

A WordPress conversion of the ArchSan architecture/interior HTML template found in `../frontend`. Every piece of markup, CSS and JS from the template is preserved; the static content has been replaced with WordPress loops, ACF fields and Contact Form 7.

---

## 1. Requirements

| Requirement                    | Why                                                                                                           |
| ------------------------------ | ------------------------------------------------------------------------------------------------------------- |
| WordPress 6.0+                 | Block editor, template hierarchy features used here                                                           |
| PHP 7.4+                       | Short array syntax, null coalescing                                                                           |
| **Advanced Custom Fields PRO** | Repeater, Gallery and Options Page — the theme registers all field groups in code, so nothing needs importing |
| **Contact Form 7**             | Renders the contact page form and the "Let's Talk" bar                                                        |

Without ACF the theme still renders — it falls back to the bundled demo images and the default strings — but nothing is editable. An admin notice reminds you which plugin is missing.

---

## 2. Install

1. Copy this folder to `wp-content/themes/arkan` (the theme root is the folder containing `style.css` and `functions.php`).
2. Activate **Appearance → Themes → Arkan**.
3. Install and activate **ACF PRO** and **Contact Form 7**.
4. Visit **Settings → Permalinks** and click Save once (registers the `projects/` and `services/` URLs).

> `frontend/` holds the original HTML template and is **not used at runtime**. It adds ~8 MB to the theme; delete it before deploying if you don't need the reference.

---

## 3. First-run setup

### 3.1 Home page

1. Create a page called `Home`.
2. **Settings → Reading → Your homepage displays → A static page → Homepage: Home.**
3. Edit the page — the **Home Page Sections** panel appears with tabs: Hero Slider, About Section, Projects Section, Testimonials Section, Latest News Section.

The same panel is also available on any page assigned the **Home Page** template, so a second landing page can be built without touching Reading settings.

### 3.2 Menu — hardcoded

The navbar is **not** editable from Appearance → Menus. It lives in `template-parts/global/navbar.php`
and reproduces `frontend/index.html` exactly — all 31 entries, three levels deep, same classes and
icons. Only the `href` values are dynamic.

Demo-only entries have no converted counterpart, so they resolve to the closest real destination:

| Menu entry                                | Goes to                                              |
| ----------------------------------------- | ---------------------------------------------------- |
| Home Layout 01–11                         | Front page                                           |
| About / Contact / Gallery / Faq / Process | Page with that slug                                  |
| Services                                  | "Services" page if it exists, else Services archive  |
| Projects 01 / 02 / 03                     | Projects archive                                     |
| Project Page                              | Newest project                                       |
| Services Page                             | Newest service                                       |
| Blog 01 / 02                              | Posts page                                           |
| Post Single                               | Newest post                                          |
| 404 Page                                  | `/404-not-found/` (deliberately unresolvable)        |

Rename a page and the menu follows it:

```php
add_filter( 'arkan_page_slugs', function ( $slugs ) {
    $slugs['about'] = 'about-us';   // now points at /about-us/
    return $slugs;
} );
```

To restore an editable WordPress menu, replace the `get_template_part( 'template-parts/global/navbar' )`
call in `header.php` with `wp_nav_menu()` and register a `primary` location in `functions.php`.

### 3.3 Pages to create

Create pages with these **slugs** — the matching template is applied automatically, with no template
assignment and no menu configuration needed:

| Page slug | Template used      | Assignable alternative                                     |
| --------- | ------------------ | ---------------------------------------------------------- |
| `about`   | `page-about.php`   | "About Page"                                               |
| `contact` | `page-contact.php` | "Contact Page"                                             |
| `gallery` | —                  | "Gallery Page"                                             |
| `faq`     | —                  | "FAQ Page"                                                 |
| `process` | —                  | "Process Page"                                             |
| `services`| —                  | "Services Page" _(or link straight to the Services archive)_ |
| Blog      | —                  | _(set as "Posts page" in Settings → Reading)_              |

ACF fields appear on **either** route: each page-level field group matches the assigned template
_or_ the page slug, via the custom "Page Slug" location rule in `inc/acf-location.php`.

### 3.4 Theme Options

**Theme Options** in the admin sidebar (ACF options page) holds everything global:

- **General** — default page banner, preloader / scroll-ring / decorative-lines toggles
- **Let's Talk Bar** — background, overlay, copy and the CF7 form ID for the bar above the footer
- **Footer** — up to two offices with address, phone, email and social links, plus the copyright line
- **Archives** — banner and headings for the Projects, Services and Blog archives; projects per page
- **404 Page** — background, title and text

---

## 4. Content model

| HTML page            | WordPress equivalent                                             |
| -------------------- | ---------------------------------------------------------------- |
| `index.html`         | `front-page.php` + `template-parts/home/*`                       |
| `about.html`         | `page-about.php` + `template-parts/page/about.php`               |
| `services.html`      | `archive-service.php` and `page-templates/template-services.php` |
| `services-page.html` | `single-service.php`                                             |
| `projects.html`      | `archive-project.php`                                            |
| `project-page.html`  | `single-project.php`                                             |
| `blog.html`          | `index.php` (posts page) + `archive.php`                         |
| `post.html`          | `single.php` + `comments.php`                                    |
| `contact.html`       | `page-contact.php` + `template-parts/page/contact.php`           |
| `gallery.html`       | `page-templates/template-gallery.php`                            |
| `faq.html`           | `page-templates/template-faq.php`                                |
| `process.html`       | `page-templates/template-process.php`                            |
| `404.html`           | `404.php`                                                        |

### Post types

| Type          | Slug         | Public       | Fields                                                                                                |
| ------------- | ------------ | ------------ | ----------------------------------------------------------------------------------------------------- |
| `project`     | `/projects/` | yes          | number, intro, image slider (gallery), year / company / name / location, checklist, "feature in hero" |
| `service`     | `/services/` | yes          | number, card background, gallery                                                                      |
| `team`        | —            | no (UI only) | role, social links                                                                                    |
| `testimonial` | —            | no (UI only) | role/company; the quote is the post content, the photo is the featured image                          |

Taxonomies: `project_category` and `project_tag` (both attached to `project`). `project_category` drives the isotope filter on the home page and the filter row on the archive.

### Field → design mapping

- **Featured image** = the card / banner image for every type
- **Menu order** (Page Attributes) controls ordering of projects, services, team and testimonials
- Project and service numbers fall back to an auto-generated `01`, `02`… if left blank
- Any field left empty falls back to a bundled demo image, so no layout ever collapses

### Empty states

Every dynamic section handles having no content. `arkan_empty_notice()` prints:

- **to logged-in editors** — `No projects found. You can add an item from Projects in the dashboard.`
- **to visitors** — `No projects found.` (back-office instructions are not shown to the public)

Covered: hero slider, home projects, testimonials, latest news, team, projects archive, services
archive, services page, gallery, FAQ, process, and the contact page's offices and form.

Hide the notices entirely:

```php
add_filter( 'arkan_show_empty_notice', '__return_false' );
```

Show the full editor hint to everyone instead:

```php
// in a snippet or child theme
add_filter( 'user_has_cap', ... );   // or simply edit arkan_empty_notice() in inc/helpers.php
```

---

## 5. Contact Form 7

Set the numeric form ID in Theme Options (Let's Talk) and on the Contact page. Paste the markup below into the CF7 **Form** tab so the fields inherit the design's styling.

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

Comments use WordPress' native `comment_form()`, restyled in `comments.php` and `inc/comment-walker.php` to match `post.html`.

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
│   ├── nav.php                 URL/active-state resolvers for the hardcoded navbar
│   ├── acf-location.php        custom "Page Slug" ACF location rule
│   ├── post-types.php          CPTs, taxonomies, archive query tweaks, rewrite flush
│   ├── acf-fields.php          options page + every field group, registered in PHP
│   ├── template-tags.php       banner, section heading, cards, pagination, social links
│   ├── comment-walker.php      comment markup matching .comments .wrap
│   ├── customizer.php          logo, blog layout, accent colour
│   └── admin-notices.php       missing-plugin warnings
├── template-parts/
│   ├── home/                   hero, about, projects, testimonials, blog
│   ├── page/                   about, contact (shared by both routing methods)
│   ├── global/                 navbar.php, lets-talk.php
│   └── content/                content-excerpt, content-none
├── page-templates/             home, about, services, contact, gallery, faq, process
├── page-about.php  page-contact.php     slug-matched, no assignment needed
├── header.php  footer.php  sidebar.php  comments.php  searchform.php
├── front-page.php  index.php  page.php  archive.php  single.php  search.php  404.php
├── archive-project.php  single-project.php  taxonomy-project_category.php  taxonomy-project_tag.php
├── archive-service.php  single-service.php
└── assets/                     css, js, images, fonts, modules (copied from frontend/)
```

---

## 7. Changes made to the original template assets

Four edits were made to `assets/js/script.js`; everything else is byte-identical to `frontend/`. See §8 if the site ever renders blank.

1. **jQuery wrapper (critical)** — the template's IIFE was `(function () { ... $(window) ... })();`, relying on a global `$`. **WordPress ships jQuery in noConflict mode, so `$` does not exist on the front end.** The wrapper is now `(function ($) { ... })(jQuery);`. See §9.
2. **Logo path** — the scroll handler hardcoded `images/logo.png`. It now reads the URL injected from PHP (`wp_localize_script` → `arkanVars`), so the Customizer logo is respected.
3. **Scroll-to-top ring** — `document.querySelector('.progress-wrap path')` threw when the ring was absent, which killed every script after it. It is now guarded, so the ring can be switched off in Theme Options.
4. **Contact AJAX** — the legacy `.contact__form` handler now excludes `.wpcf7-form` so it can never fight with Contact Form 7's own AJAX.

New files:

- `assets/js/wp-safety.js` — dependency-free fail-safe (see §9)
- `assets/css/wp-overrides.css` — styles for everything WordPress emits that the template never had (CF7, comment list, core alignment/caption/gallery classes, pagination `<span class="current">`, admin bar offset, screen-reader helpers, footer menu)
- `assets/css/editor-style.css` — dark block-editor styling
- `assets/js/customizer-preview.js` — live preview for the site title and accent colour

---

## 8. Troubleshooting: blank / all-dark page

**Symptom.** The site loads but shows nothing — a solid dark screen, no header, no footer.

**Why this template is prone to it.** Two rules in `assets/css/style.css` make the theme's JavaScript a single point of failure:

```css
.preloader-bg,
#preloader {
	position: fixed;
	width: 100%;
	height: 100%;
	background: #1b1b1b;
	z-index: 999999;
}
.js .animate-box {
	opacity: 0;
}
```

The preloader is a full-screen opaque overlay that is **only** removed by `$("#preloader").fadeOut(700)` in `script.js`, and most sections start invisible until the Waypoints handler in the same file reveals them. So _any_ uncaught error in `script.js` — a jQuery clash, a plugin conflict, a blocked asset — produces a completely blank dark page with no clue as to why.

**The original cause here** was the jQuery wrapper (§7.1): `$` is undefined under WordPress, so `var wind = $(window);` threw `TypeError: $ is not a function` on the first line of the IIFE.

**What now prevents a recurrence.** `assets/js/wp-safety.js` is enqueued with **no dependencies**, so it still runs even when `script.js` dies. `script.js` sets `window.arkanScriptReady = true` on its last line; if that flag is missing ~2.5s after `load`, the safety script hides the preloader, adds `arkan-js-fallback` to `<html>` (which forces `.animate-box` visible), and logs a clear warning to the console. There is also a hard 8s ceiling and a `<noscript>` fallback in `header.php`.

Worst case is now a site **without scroll animations**, never a blank one.

**If you still see a blank page**, check in this order:

1. Browser console — the safety net prints `[Arkan] Theme JavaScript did not finish initialising…` followed by the real error.
2. If the page is blank _and_ the console is empty, it is a PHP fatal, not JS. Set `define( 'WP_DEBUG', true ); define( 'WP_DEBUG_DISPLAY', true );` in `wp-config.php` to see it.
3. Confirm ACF PRO is active — without it the theme renders demo fallbacks, but never blank.

---

## 9. Notes and known limits

- The theme targets **one homepage layout** (`index.html`). The other ten variants in `frontend/` are not converted; each would become an extra file in `page-templates/` reusing the same `template-parts/home/*` pieces.
- `blog2.html`, `post2.html`, `projects2.html` and `projects3.html` are likewise not converted.
- The archive slugs `projects/` and `services/` can be changed by setting the options `arkan_slug_project` / `arkan_slug_service` (see `arkan_cpt_slug()` in `inc/post-types.php`); flush permalinks afterwards.
- `.lint.py` is a development helper that structurally checks every PHP file (bracket balance, alternative-syntax balance, include targets, undefined `arkan_*` calls). It is safe to delete before shipping.

---

## 10. ACF field reference

Every field below is registered in code by `inc/acf-fields.php` — **you do not need to create
any of them by hand.** This reference exists so you can (a) confirm the admin screens match,
(b) rebuild a group manually if you ever move off the bundled registration, and (c) look up the
exact field name to use in a template.

> **Field names are the contract.** Templates read them with `arkan_field( 'name' )` (post/page
> context) or `arkan_option( 'name' )` (Theme Options). If you recreate a field in the ACF UI,
> the **Field Name** must match the `name` column exactly — the label can be anything.

Repeater sub-fields are shown indented with `└`. Their names are read from the row array, e.g.
`$row['office_city']`.

**Reading a field in a template:**

```php
// Post / page context — second argument is the post ID.
$subtitle = arkan_field( 'home_about_subtitle', get_the_ID() );

// Theme Options context.
$copyright = arkan_option( 'footer_copyright' );

// Both accept a third argument used when ACF is inactive or the value is empty.
$overlay = arkan_field( 'banner_overlay', get_the_ID(), 4 );

// Repeater rows are plain arrays keyed by the sub-field name.
foreach ( arkan_option( 'footer_offices', array() ) as $office ) {
    echo esc_html( $office['office_city'] );
}
```

### Group index

| #   | Group                   | Where it appears                                                                                | Fields |
| --- | ----------------------- | ----------------------------------------------------------------------------------------------- | ------ |
| 1   | **Theme Options**       | Theme Options page                                                                              | 35     |
| 2   | **Home Page Sections**  | front page **or** page template `page-templates/template-home.php`                              | 30     |
| 3   | **Page Banner**         | post type `page` **or** `post` **or** `project` **or** `service`                                | 4      |
| 4   | **Project Details**     | post type `project`                                                                             | 10     |
| 5   | **Service Details**     | post type `service`                                                                             | 3      |
| 6   | **Team Member Details** | post type `team`                                                                                | 4      |
| 7   | **Testimonial Details** | post type `testimonial`                                                                         | 1      |
| 8   | **About Page**          | page template `page-templates/template-about.php` **or** page slug `about`                      | 14     |
| 9   | **Contact Page**        | page template `page-templates/template-contact.php` **or** page slug `contact`                  | 12     |
| 10  | **Gallery Page**        | page template `page-templates/template-gallery.php` **or** page slug `gallery`                  | 4      |
| 11  | **FAQ Page**            | page template `page-templates/template-faq.php` **or** page slug `faq`                          | 3      |
| 12  | **Process Page**        | page template `page-templates/template-process.php` **or** page slug `process`                  | 6      |
| 13  | **Services Page**       | page template `page-templates/template-services.php` **or** page slug `services`                | 2      |

Total: **128 fields across 13 groups** (sub-fields included).

PRO-only field types used: **Repeater**, **Gallery**, **Options Page**. Everything else (Text, Text
Area, WYSIWYG, Image, Select, URL, Email, Number, Range, True/False, Link) exists in free ACF.

### Theme Options

**Location:** Theme Options page  
**Group key:** `group_arkan_options`

#### Tab — General

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `default_banner` | Default Page Banner Image | Image | — | `inc/template-tags.php` |
| `preloader_enabled` | Show Preloader | True / False | `1` | `header.php` |
| `scroll_top_enabled` | Show Scroll-To-Top Ring | True / False | `1` | `header.php` |
| `content_lines_enabled` | Show Decorative Vertical Lines | True / False | `1` | `header.php` |

#### Tab — Let's Talk Bar

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `lets_talk_enabled` | Show Let's Talk section | True / False | `1` | `template-parts/global/lets-talk.php` |
| `lets_talk_image` | Background Image | Image | — | `template-parts/global/lets-talk.php` |
| `lets_talk_overlay` | Overlay Darkness | Range | `6` | `template-parts/global/lets-talk.php` |
| `lets_talk_subtitle` | Subtitle | Text | `Contact Us` | `template-parts/global/lets-talk.php` |
| `lets_talk_title` | Title | Text | `Let's discuss your project` | `template-parts/global/lets-talk.php` |
| `lets_talk_text` | Text | Text Area | `Fill out the form and our manager will contact you for consultation.` | `template-parts/global/lets-talk.php` |
| `lets_talk_form_id` | Contact Form 7 ID | Text | — | `template-parts/global/lets-talk.php` |

#### Tab — Footer

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `footer_subtitle` | Footer Subtitle | Text | `Contact Us` | `footer.php` |
| `footer_offices` | Offices | Repeater | — | `footer.php` |
| └ `office_city` | City | Text | — | `footer.php` |
| └ `office_label` | Accent Word | Text | `Office` | `footer.php` |
| └ `office_address` | Address | Text Area | — | `footer.php` |
| └ `office_phone` | Phone | Text | — | `footer.php` |
| └ `office_email` | Email | Email | — | `footer.php` |
| └ `office_socials` | Social Links | Repeater | — | `footer.php` |
| &nbsp;&nbsp;&nbsp;└ `office_social_icon` | Icon | Select | — | `footer.php` |
| &nbsp;&nbsp;&nbsp;└ `office_social_url` | URL | URL | — | `footer.php` |
| `footer_copyright` | Copyright Line | Text | `© 2026 Arkan. All rights reserved.` | `footer.php` |

#### Tab — Archives

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `projects_archive_subtitle` | Projects Archive Subtitle | Text | `Projects` | `archive-project.php` |
| `projects_archive_title` | Projects Archive Title | Text | `Creative Projects` | `archive-project.php` |
| `projects_archive_image` | Projects Archive Banner | Image | — | `archive-project.php` |
| `projects_per_page` | Projects Per Page | Number | `9` | `archive-project.php`<br>`inc/post-types.php` |
| `services_archive_subtitle` | Services Archive Subtitle | Text | `What We Do` | `archive-service.php` |
| `services_archive_title` | Services Archive Title | Text | `Our Services` | `archive-service.php` |
| `services_archive_image` | Services Archive Banner | Image | — | `archive-service.php` |
| `blog_archive_subtitle` | Blog Subtitle | Text | `Blog` | `index.php` |
| `blog_archive_title` | Blog Title | Text | `Latest News` | `archive.php`<br>`index.php` |
| `blog_archive_image` | Blog Banner | Image | — | `archive.php`<br>`index.php`<br>`search.php` |

#### Tab — 404 Page

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `error_image` | 404 Background | Image | — | `404.php` |
| `error_title` | 404 Title | Text | `Sorry We Can't Find That Page!` | `404.php` |
| `error_text` | 404 Text | Text Area | `The page you are looking for was moved, removed, renamed or never existed.` | `404.php` |

### Home Page Sections

**Location:** front page **or** page template `page-templates/template-home.php`  
**Group key:** `group_arkan_front`

#### Tab — Hero Slider

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `hero_slides` | Slides | Repeater | — | `template-parts/home/hero.php` |
| └ `slide_image` | Image | Image | — | `template-parts/home/hero.php` |
| └ `slide_overlay` | Overlay Darkness | Range | `4` | `template-parts/home/hero.php` |
| └ `slide_subtitle` | Subtitle | Text | — | `template-parts/home/hero.php` |
| └ `slide_title` | Title | Text | — | `template-parts/home/hero.php` |
| └ `slide_text` | Text | Text Area | — | `template-parts/home/hero.php` |
| └ `slide_button_text` | Button Text | Text | `View Project` | `template-parts/home/hero.php` |
| └ `slide_button_link` | Button Link | Link | — | `template-parts/home/hero.php` |

#### Tab — About Section

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `home_about_enabled` | Show About Section | True / False | `1` | `template-parts/home/about.php` |
| `home_about_subtitle` | Subtitle | Text | `Who are we?` | `template-parts/home/about.php` |
| `home_about_title` | Title | Text | — | `template-parts/home/about.php` |
| `home_about_text` | Text | WYSIWYG | — | `template-parts/home/about.php` |
| `home_about_boxes` | Highlight Boxes | Repeater | — | `template-parts/home/about.php` |
| └ `box_icon` | Icon Image | Image | — | `template-parts/home/about.php` |
| └ `box_title` | Title | Text | — | `template-parts/home/about.php` |

#### Tab — Projects Section

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `home_projects_enabled` | Show Projects Section | True / False | `1` | `template-parts/home/projects.php` |
| `home_projects_subtitle` | Subtitle | Text | `Discover` | `template-parts/home/projects.php` |
| `home_projects_title` | Title | Text | `Creative <span>Projects</span>` | `template-parts/home/projects.php` |
| `home_projects_text` | Text | Text Area | — | `template-parts/home/projects.php` |
| `home_projects_filter` | Show Category Filter | True / False | `1` | `template-parts/home/projects.php` |
| `home_projects_count` | Number of Projects | Number | `6` | `template-parts/home/projects.php` |

#### Tab — Testimonials Section

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `home_testimonials_enabled` | Show Testimonials | True / False | `1` | `template-parts/home/testimonials.php` |
| `home_testimonials_image` | Background Image | Image | — | `template-parts/home/testimonials.php` |
| `home_testimonials_overlay` | Overlay Darkness | Range | `6` | `template-parts/home/testimonials.php` |
| `home_testimonials_subtitle` | Subtitle | Text | `Testimonials` | `template-parts/home/testimonials.php` |
| `home_testimonials_title` | Title | Text | `What Client's Say?` | `template-parts/home/testimonials.php` |

#### Tab — Latest News Section

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `home_blog_enabled` | Show Latest News | True / False | `1` | `template-parts/home/blog.php` |
| `home_blog_subtitle` | Subtitle | Text | `Blog` | `template-parts/home/blog.php` |
| `home_blog_title` | Title | Text | `<span>Latest</span> News` | `template-parts/home/blog.php` |
| `home_blog_count` | Number of Posts | Number | `3` | `template-parts/home/blog.php` |

### Page Banner

**Location:** post type `page` **or** post type `post` **or** post type `project` **or** post type `service`  
**Group key:** `group_arkan_banner`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `banner_image` | Banner Image | Image | — | `inc/template-tags.php`<br>`index.php` |
| `banner_overlay` | Overlay Darkness | Range | `4` | `inc/template-tags.php` |
| `banner_subtitle` | Banner Subtitle | Text | — | `inc/template-tags.php`<br>`single-project.php`<br>`single-service.php`<br>`single.php` |
| `banner_title` | Banner Title | Text | — | `inc/template-tags.php` |

### Project Details

**Location:** post type `project`  
**Group key:** `group_arkan_project`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `project_number` | Project Number | Text | — | `inc/template-tags.php` |
| `project_intro` | Intro Paragraph | Text Area | — | `single-project.php` |
| `project_gallery` | Image Slider | Gallery | — | `single-project.php` |
| `project_year` | Year | Text | — | `single-project.php` |
| `project_company` | Company | Text | — | `single-project.php` |
| `project_name` | Name | Text | — | `single-project.php` |
| `project_location` | Location | Text | — | `single-project.php` |
| `project_features` | Checklist | Repeater | — | `single-project.php` |
| └ `feature_text` | Text | Text | — | `single-project.php` |
| `project_featured_home` | Feature in Home Hero Slider | True / False | — | `template-parts/home/hero.php` |

### Service Details

**Location:** post type `service`  
**Group key:** `group_arkan_service`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `service_number` | Number | Text | — | `inc/template-tags.php` |
| `service_card_image` | Card Background | Image | — | `inc/template-tags.php` |
| `service_gallery` | Gallery | Gallery | — | `single-service.php` |

### Team Member Details

**Location:** post type `team`  
**Group key:** `group_arkan_team`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `team_role` | Role / Qualification | Text | — | `inc/template-tags.php` |
| `team_socials` | Social Links | Repeater | — | `inc/template-tags.php` |
| └ `team_social_icon` | Icon | Select | — | `inc/template-tags.php` |
| └ `team_social_url` | URL | URL | — | `inc/template-tags.php` |

### Testimonial Details

**Location:** post type `testimonial`  
**Group key:** `group_arkan_testimonial`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `testimonial_role` | Role / Company | Text | — | `template-parts/home/testimonials.php` |

### About Page

**Location:** page template `page-templates/template-about.php` **or** page slug `about`  
**Group key:** `group_arkan_about_tpl`

#### Tab — Intro

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `about_subtitle` | Subtitle | Text | `About` | `template-parts/page/about.php` |
| `about_title` | Title | Text | — | `template-parts/page/about.php` |
| `about_stat_number` | Stat Number | Text | `24` | `template-parts/page/about.php` |
| `about_stat_label` | Stat Label | Text Area | `Years
Of Experience` | `template-parts/page/about.php` |
| `about_portraits` | Portraits | Repeater | — | `template-parts/page/about.php` |
| └ `portrait_image` | Image | Image | — | `template-parts/page/about.php` |
| └ `portrait_name` | Name | Text | — | `template-parts/page/about.php` |

#### Tab — Skills

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `about_skills` | Skill Bars | Repeater | — | `template-parts/page/about.php` |
| └ `skill_title` | Title | Text | — | `template-parts/page/about.php` |
| └ `skill_value` | Percent | Number | `80` | `template-parts/page/about.php` |

#### Tab — Team

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `about_team_enabled` | Show Team Section | True / False | `1` | `template-parts/page/about.php` |
| `about_team_subtitle` | Subtitle | Text | `Creative Thinkers` | `template-parts/page/about.php` |
| `about_team_title` | Title | Text | `Team <span>Members</span>` | `template-parts/page/about.php` |
| `about_team_text` | Text | Text Area | — | `template-parts/page/about.php` |

### Contact Page

**Location:** page template `page-templates/template-contact.php` **or** page slug `contact`  
**Group key:** `group_arkan_contact_tpl`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `contact_locations` | Offices | Repeater | — | `template-parts/page/contact.php` |
| └ `location_city` | City | Text | — | `template-parts/page/contact.php` |
| └ `location_label` | Accent Word | Text | `Office` | `template-parts/page/contact.php` |
| └ `location_address` | Address | Text Area | — | `template-parts/page/contact.php` |
| └ `location_phone` | Phone | Text | — | `template-parts/page/contact.php` |
| └ `location_email` | Email | Email | — | `template-parts/page/contact.php` |
| └ `location_socials` | Social Links | Repeater | — | `template-parts/page/contact.php` |
| &nbsp;&nbsp;&nbsp;└ `location_social_icon` | Icon | Select | — | `template-parts/page/contact.php` |
| &nbsp;&nbsp;&nbsp;└ `location_social_url` | URL | URL | — | `template-parts/page/contact.php` |
| `contact_form_title` | Form Heading | Text | `Have a Project? - <span>Lets Talk</span>` | `template-parts/page/contact.php` |
| `contact_form_id` | Contact Form 7 ID | Text | — | `template-parts/page/contact.php` |
| `contact_map` | Google Maps Embed | Text Area | — | `template-parts/page/contact.php` |

### Gallery Page

**Location:** page template `page-templates/template-gallery.php` **or** page slug `gallery`  
**Group key:** `group_arkan_gallery_tpl`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `gallery_subtitle` | Subtitle | Text | `Images` | `page-templates/template-gallery.php` |
| `gallery_title` | Title | Text | `<span>Image</span> Gallery` | `page-templates/template-gallery.php` |
| `gallery_text` | Text | Text Area | — | `page-templates/template-gallery.php` |
| `gallery_images` | Images | Gallery | — | `page-templates/template-gallery.php` |

### FAQ Page

**Location:** page template `page-templates/template-faq.php` **or** page slug `faq`  
**Group key:** `group_arkan_faq_tpl`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `faq_items` | Questions | Repeater | — | `page-templates/template-faq.php` |
| └ `faq_question` | Question | Text | — | `page-templates/template-faq.php` |
| └ `faq_answer` | Answer | Text Area | — | `page-templates/template-faq.php` |

### Process Page

**Location:** page template `page-templates/template-process.php` **or** page slug `process`  
**Group key:** `group_arkan_process_tpl`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `process_subtitle` | Subtitle | Text | `How We Work` | `page-templates/template-process.php` |
| `process_title` | Title | Text | `Our <span>Process</span>` | `page-templates/template-process.php` |
| `process_steps` | Steps | Repeater | — | `page-templates/template-process.php` |
| └ `step_image` | Image | Image | — | `page-templates/template-process.php` |
| └ `step_title` | Title | Text | — | `page-templates/template-process.php` |
| └ `step_text` | Text | Text Area | — | `page-templates/template-process.php` |

### Services Page

**Location:** page template `page-templates/template-services.php` **or** page slug `services`  
**Group key:** `group_arkan_services_tpl`

| Field name (code) | Label | Type | Default | Used in |
| --- | --- | --- | --- | --- |
| `services_intro` | Intro Text | Text Area | — | `page-templates/template-services.php` |
| `services_count` | Number of Services | Number | — | `page-templates/template-services.php` |
