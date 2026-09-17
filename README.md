# PHPBB Lab Portal

Accessible, theme-independent and topic-neutral portal extension for phpBB 3.3.17.

PHPBB Lab Portal adds a configurable portal to a phpBB board without modifying the phpBB core and without imposing its own CSS. The active phpBB style remains responsible for colours, typography, spacing and responsive presentation.

## Compatibility

- phpBB: `>= 3.3.17` and `< 3.4.0`
- PHP: `>= 7.2.5`
- Extension name: `phpbblab/portal`
- Current version: `1.2.1`
- License: GNU GPL v2 (`GPL-2.0-only`)

## Main features

- Optional portal route at `/portal`.
- Optional use of the portal as the board home page.
- When used as the home page, the board root serves the portal directly and `/portal` permanently redirects to the root.
- Canonical home-page handling without session IDs in public portal URLs.
- Configurable portal title, introduction and meta description.
- Configurable navigation and action labels.
- Reusable source selectors for portal sections.
- Independent block ordering, visibility and titles.
- Latest content and recent topics.
- Up to 12 featured topics, preserving administrator-defined order.
- Optional plain-text announcement with a safe link.
- Up to 12 custom links.
- Optional footer credit on the portal page only.
- Reversible ASCII-safe storage for editable text so 4-byte Unicode characters are preserved even with a more limited database character set.

## Theme independence

The extension ships no CSS, SCSS, SASS or LESS and injects no inline stylesheet. Public templates use standard phpBB/prosilver structural classes and components. A compatible phpBB style does not need the PHPBB Lab theme.

## Accessibility

- The page/site H1 remains owned by the active phpBB style.
- Portal sections expose real H2 headings.
- Linked forum and topic items expose H3 headings.
- Native links and buttons are used.
- Important content does not depend on JavaScript.
- ACP ordering is numeric and remains usable with a keyboard and screen reader.
- Visibility choices use explicit labelled form controls.

## Security and permissions

- Only forums the current visitor may list/read are exposed.
- Password-protected forums are excluded from portal topic and featured-topic queries.
- Only approved, non-moved topics with an approved first post are displayed.
- Featured topic IDs do not bypass phpBB permissions.
- Custom and announcement URLs reject dangerous URI schemes.
- ACP changes use phpBB form keys and admin logging.
- No credentials, private keys or secrets are stored by the extension.

## Installation

The repository root is the extension root. For a phpBB installation, the files must end up here:

```text
ext/phpbblab/portal/
```

The final path must therefore contain:

```text
ext/phpbblab/portal/composer.json
```

Then enable **PHPBB Lab Portal** from the phpBB Administration Control Panel.

## Updating

For a normal update:

1. Disable the extension.
2. Replace its files with the new release.
3. Purge the phpBB cache.
4. Enable the extension again so migrations can run.

Do not use `extension:purge` for a normal update.
