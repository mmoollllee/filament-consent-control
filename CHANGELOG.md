# Changelog

All notable changes to `filament-consent-control` will be documented in this file.

## Unreleased

### Added
- Cookie-settings button as a **merge tag / shortcode** for sites on
  `mmoollllee/filament-cms`: editors pick "Cookie-Einstellungen (Button)" in the RichEditor
  or write `[consent_settings label="…" class="…"]` instead of typing raw HTML. Registers
  itself on boot and stays a no-op without the CMS (needs the filament-cms version that
  ships `Shortcodes::registerMergeTag()`).
- `Support\ConsentSettingsButton::html()` — the reopen-button markup for own views.
- Package translations (`filament-consent-control::consent`, de + en) for the button label
  and the merge-tag menu entry; publish with
  `vendor:publish --tag=filament-consent-control-translations`.

## 0.1.1 - 2026-07-15

### Fixed
- RichEditor consent-iframe extension failed to load in the browser
  (`TypeError: Module name, '@tiptap/core' does not resolve to a valid URL`).
  The build now resolves `@tiptap/core` from Filament's shared
  `window.FilamentRichEditor.tiptap` instance instead of emitting a bare import,
  following Filament's "Sharing the bundled TipTap/ProseMirror instance" guidance
  (no duplicate ProseMirror, no broken `instanceof` checks).

## 0.1.0 - 2026-07-09

Re-architected as the **Filament layer** on top of
[`mmoollllee/laravel-consent-control`](https://github.com/mmoollllee/laravel-consent-control)
(which itself builds on the [`consent-control`](https://github.com/mmoollllee/consent-control) npm runtime).

### Changed
- The frontend banner, Blade components, config, translations and runtime now live in
  `laravel-consent-control`; this package only provides the Filament admin UI.
- `ConsentControlPlugin` is now functional and **opt-in**: `->settingsPage()` registers a
  ready-made settings page. Without it, a single config file is enough.
- `ConsentSettingsForm` labels are translatable; updated to the Filament v5 Schema API.

### Added
- Pasted YouTube/Vimeo URLs in the RichEditor are auto-normalised to privacy-friendly
  embed URLs (YouTube → youtube-nocookie).

### Fixed
- RichEditor consent-iframe is no longer broken: the JS command matches the PHP action
  (`setConsentIframe`), the undefined `getYouTube/Vimeo` helpers are gone, and stored
  iframes now render as blocked `.consent-message--wrapper` markup (`data-src`) so they
  are actually gated until consent — wired by the shared runtime.
