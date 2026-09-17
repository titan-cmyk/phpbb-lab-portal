# Changelog

All notable changes to PHPBB Lab Portal are documented here.

## 1.2.1

- Replaces the temporary bare-root redirect with phpBB 3.3.17's native permanent controller redirect.
- Prevents anonymous crawlers from receiving `/portal?sid=...` as the portal destination.
- Adds an explicit self-referencing canonical URL to the public portal page.
- Keeps the portal route as the single indexable portal URL when the portal is configured as the site home page.
- Fixes the Search Console duplicate-without-user-selected-canonical condition caused by `/`, `/portal` and session-ID URL variants.
- Keeps zero CSS, theme independence and the existing portal configuration unchanged.

## 1.2.0

- Makes the ACP and public defaults topic-neutral so the portal can be used on any phpBB community, not only support/documentation sites.
- Renames source descriptions by function: main section, secondary section, content section, resources section and additional section.
- Adds editable labels for the Forum, main, secondary and resources action buttons.
- Adds an explicit "No source" option for every source selector, in addition to automatic detection and explicit forum/category selection.
- Replaces support/tutorial/download-specific default public labels with generic portal wording while preserving full title overrides for every block.
- Keeps all existing configuration keys and migration history for backward compatibility.
- Keeps zero CSS and full theme independence.

## 1.1.5

- Adds a guaranteed "Go to forum" / "Accéder au forum" action inside the portal itself, using phpBB's native button classes and the real `index.php` URL.
- Keeps the header Forum navigation link as an additional convenience when the active style renders phpBB's navigation event.
- The Forum header link no longer depends on the optional "Show Portal link" setting.
- Keeps zero CSS and theme independence.

## 1.1.4

- Adds a direct Forum link to phpBB's main navigation when the portal is configured as the site home page.
- Uses phpBB's native forum-index URL captured before breadcrumb rewriting.
- Uses the native phpBB `FORUM` language label and the standard header navigation event.
- Keeps the navigation item visible instead of moving it into prosilver's responsive overflow menu.

## 1.1.3

- When the portal is the configured site home page, the portal page shows only one breadcrumb: Portal.
- Forum/index pages keep the hierarchy Portal > forum index > current category/forum/topic.
- Uses phpBB template variables only; no core or style modification is required.

## 1.1.2

- When configured as the site home page, the portal becomes phpBB's site-home location throughout the public board.
- On the portal itself, the breadcrumb becomes Portal > forum index instead of forum index > Portal.
- Keeps the previous forum index > Portal order when the portal-home option is disabled.
- Uses phpBB page-header variables only; no core or style modification is required.

## 1.1.1

- Adds an ACP option, enabled by default, to use the portal as the board home page.
- Redirects only GET/HEAD requests made to the bare board root.
- Keeps `index.php` available as the normal phpBB forum index.
- Detects boards installed at the domain root or in a subdirectory without hard-coding a path.

## 1.1.0

- Adds configurable block order, visibility and titles.
- Adds Featured topics selected by topic ID with permission checks.
- Adds an optional portal announcement block.
- Adds an optional custom-links block.
- Adds configurable SEO meta description.
- Adds an optional portal-page footer credit linking to https://phpbb-lab.com/.
- Refactors the public template to render blocks in the configured order.
- Keeps zero CSS and full theme independence.

## 1.0.4

- Builds excerpts from phpBB-rendered content before converting them to plain text.
- Removes residual BBCode/list markers from excerpts.
- Simplifies guide metadata to the publication date only.

## 1.0.3

- Makes the public template independent of the PHPBB Lab theme using standard phpBB structural classes and native components.
- Shortens and cleans guide/recent excerpts.
- Adds explicit read/open links and accessible time metadata.

## 1.0.2

- Fixes the phpBB 3.3.17 SQL error caused by selecting `posts.bbcode_options`.
- Uses the real `enable_bbcode`, `enable_smilies` and `enable_magic_url` post fields.

## 1.0.1

- Adds breadcrumb, accessible forum selectors and 4-byte Unicode-safe editable text.

## 1.0.0

- Initial PHPBB Lab Portal package.
