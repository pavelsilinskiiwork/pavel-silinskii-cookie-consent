=== Pavel Silinskii Cookie Consent ===
Contributors: pavelsilinskii
Tags: cookie consent, gdpr, ccpa, cookie banner, privacy
Requires at least: 5.9
Tested up to: 7.1
Requires PHP: 8.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Lightweight GDPR/CCPA cookie consent banner with bar and popup layouts, light/dark themes, and one-click accept/decline.

== Description ==

Pavel Silinskii Cookie Consent displays a customizable cookie consent banner that lets visitors accept or decline cookies. Everything is configured from the WordPress admin — no coding required.

The visitor's choice is stored in a single first-party cookie (`pscc_consent`) in their own browser. No personal data is sent to any server and the plugin makes no external requests.

= Features =

* Bar or popup layout
* Fixed top or bottom position (bar layout)
* Light and dark theme
* Customizable title, message, and button labels
* Optional Decline button
* Link to your Privacy Policy page
* Consent remembered for 1–365 days
* Custom Accept button color
* No external requests, no tracking
* GDPR and CCPA ready
* Lightweight: vanilla JavaScript, no jQuery on the frontend, assets load only while the banner is shown

== Installation ==

1. Upload the `pavel-silinskii-cookie-consent` folder to `/wp-content/plugins/`, or install the plugin through the **Plugins → Add New** screen.
2. Activate the plugin through the **Plugins** screen.
3. Go to **Settings → Cookie Consent**.
4. Set the layout, texts, privacy page, and colors.
5. Click **Save Changes** and open your site in a private window to see the banner.

== Frequently Asked Questions ==

= Where is consent stored? =

In a first-party browser cookie named `pscc_consent`, with the value `accepted` or `declined`. It expires after the number of days set in **Settings → Cookie Consent → Appearance**.

= Does the plugin send data anywhere? =

No. There are no external requests. The only server request is a nonce-protected call to your own site's `admin-ajax.php` that acknowledges the choice; nothing is stored on the server.

= Is it GDPR compliant? =

The plugin provides the consent interface and records the visitor's choice. Legal compliance depends on your site configuration — for example, which cookies your theme and other plugins set before or regardless of consent. Consult a legal professional for your specific case.

= How do I change the button color? =

Go to **Settings → Cookie Consent → Appearance** and pick a color with the color picker.

= How do I show the banner again for testing? =

Delete the `pscc_consent` cookie in your browser, or open the site in a private window.

== Screenshots ==

1. Cookie consent bar on the frontend — light theme.
2. Cookie consent popup on the frontend — dark theme.
3. Admin settings page — General and Banner Text sections.
4. Admin settings page — Appearance section with color picker.

== Changelog ==

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
Initial release.
