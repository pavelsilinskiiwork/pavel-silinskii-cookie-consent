# Pavel Silinskii Cookie Consent

![WordPress](https://img.shields.io/badge/WordPress-5.9%2B-21759B?logo=wordpress&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777BB4?logo=php&logoColor=white)
![License](https://img.shields.io/badge/License-GPL%20v2-green)
![Version](https://img.shields.io/badge/Version-1.0.0-blue)

Lightweight GDPR/CCPA cookie consent banner for WordPress with bar and popup layouts, light/dark themes, and one-click accept/decline. No external requests, no tracking, vanilla JavaScript on the frontend.

---

## Table of Contents

- [Features](#features)
- [How It Works](#how-it-works)
- [Installation](#installation)
- [Configuration](#configuration)
- [Settings Reference](#settings-reference)
- [Cookie](#cookie)
- [AJAX Endpoint](#ajax-endpoint)
- [Security](#security)
- [Project Structure](#project-structure)
- [Requirements](#requirements)
- [Changelog](#changelog)

---

## Features

- **Two layouts** — fixed bar or centered popup with overlay
- **Position** — top or bottom (bar layout)
- **Light / dark theme**
- **Custom text** — title, message, Accept / Decline labels
- **Optional Decline button**
- **Privacy Policy link** — pick any page
- **Consent duration** — 1–365 days
- **Custom Accept button color** — WordPress color picker
- **Lightweight** — assets load only while the banner is shown; no jQuery on the frontend
- **Private** — no external requests, nothing stored server-side

---

## How It Works

1. `PSCC_Frontend::should_show_banner()` runs on each front-end request: the banner is rendered only if the plugin is enabled and the visitor has no `pscc_consent` cookie.
2. The banner is printed in `wp_footer` with the `pscc-hidden` class; JavaScript reveals it after re-checking the cookie (so a cached page never shows it to a visitor who already decided).
3. Clicking **Accept** or **Decline** (or the popup overlay, which counts as Decline) sets the cookie in the browser, hides the banner, and sends a nonce-protected acknowledgement to `admin-ajax.php`.

---

## Installation

### Manual

1. Download or clone this repository.
2. Upload the `pavel-silinskii-cookie-consent` folder to `/wp-content/plugins/`.
3. Activate the plugin in **WordPress Admin → Plugins**.
4. Configure it in **Settings → Cookie Consent**.

### Via Git

```bash
cd wp-content/plugins
git clone https://github.com/pavelsilinskiiwork/pavel-silinskii-cookie-consent.git
```

Then activate in **WordPress Admin → Plugins**.

---

## Configuration

**Settings → Cookie Consent** (WordPress Settings API, saved via `options.php`):

| Section | Options |
|---|---|
| **General** | Enable banner, Position (top / bottom), Layout (bar / popup) |
| **Banner Text** | Title, Text, Accept label, Decline label, Show decline button |
| **Privacy Policy** | Privacy page, Link text |
| **Appearance** | Style (light / dark), Cookie duration (1–365 days), Button color |

---

## Settings Reference

Stored as a single option, `pscc_settings`.

| Key | Type | Default | Allowed values |
|---|---|---|---|
| `enabled` | bool | `true` | |
| `position` | string | `bottom` | `bottom`, `top` |
| `layout` | string | `bar` | `bar`, `popup` |
| `style` | string | `light` | `light`, `dark` |
| `banner_title` | string | `We use cookies` | |
| `banner_text` | string | `This website uses cookies to ensure you get the best experience on our website.` | |
| `accept_text` | string | `Accept` | empty falls back to default |
| `decline_text` | string | `Decline` | |
| `show_decline` | bool | `true` | |
| `privacy_page_id` | int | `0` | page ID, `0` = no link |
| `privacy_link_text` | string | `Privacy Policy` | |
| `cookie_duration` | int | `365` | clamped to 1–365 |
| `button_color` | string | `#2271b1` | hex color, invalid/empty → default |

---

## Cookie

| Name | Values | Set by | Flags |
|---|---|---|---|
| `pscc_consent` | `accepted` \| `declined` | JavaScript | `path=/`, `SameSite=Lax`, `max-age` = duration × 86400 |

The cookie is intentionally **not** `httpOnly`: JavaScript sets it and reads it to avoid showing the banner on cached pages.

---

## AJAX Endpoint

| Action | Auth | Handler |
|---|---|---|
| `pscc_consent` | Public (`nopriv` + logged-in) | `PSCC_Frontend::handle_consent()` |

**Request:** `action=pscc_consent`, `nonce=<pscc_nonce>`, `consent=accepted|declined`

**Responses:**

```json
{ "success": true,  "data": { "consent": "accepted" } }
{ "success": false, "data": "invalid" }
```

---

## Security

| Measure | Where |
|---|---|
| `check_ajax_referer( 'pscc_nonce', 'nonce' )` | `handle_consent()` |
| `wp_unslash()` + `sanitize_text_field()` + allow-list | `handle_consent()` |
| `current_user_can( 'manage_options' )` | settings page |
| Settings API nonce (`settings_fields()`) | settings form |
| `sanitize_callback` with allow-lists, `absint()`, `sanitize_hex_color()`, `sanitize_text_field()`, `sanitize_textarea_field()` | `PSCC_Admin::sanitize()` |
| `esc_html()` / `esc_attr()` / `esc_url()` / `esc_textarea()` | all output |
| `ABSPATH` guard | every PHP file |

---

## Project Structure

```
pavel-silinskii-cookie-consent/
├── pavel-silinskii-cookie-consent.php   # Main file: header, constants, bootstrap
├── readme.txt                           # WordPress.org readme
├── README.md                            # This file
├── uninstall.php                        # Deletes pscc_settings
├── includes/
│   ├── class-pscc-installer.php         # Activation
│   ├── class-pscc-settings.php          # Defaults + get()
│   └── class-pscc-frontend.php          # Banner, assets, AJAX
├── admin/
│   └── class-pscc-admin.php             # Settings page, fields, sanitization
├── templates/
│   └── banner.php                       # Bar / popup markup
├── assets/
│   ├── css/
│   │   ├── pscc-frontend.css
│   │   └── pscc-admin.css
│   └── js/
│       ├── pscc-frontend.js             # Vanilla JS: cookie, banner, fetch
│       └── pscc-admin.js                # Color picker init
└── languages/
    └── pavel-silinskii-cookie-consent.pot
```

---

## Requirements

- WordPress 5.9+
- PHP 8.0+

---

## Changelog

### 1.0.0

- Initial release.

---

## About the Developer

Built by **Pavel Silinskii** — Full-Stack PHP Developer.

- GitHub: [github.com/pavelsilinskiiwork](https://github.com/pavelsilinskiiwork)
- WordPress.org: [profiles.wordpress.org/pavelsilinskii](https://profiles.wordpress.org/pavelsilinskii/)

---

## License

Licensed under the [GPL v2 or later](https://www.gnu.org/licenses/gpl-2.0.html).
