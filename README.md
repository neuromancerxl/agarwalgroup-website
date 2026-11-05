# Agarwal Group WordPress theme

A block theme preserving the group's approved website layout. The repository root is the installable theme. There is no build step and no external font service.

Requires WordPress 6.6+ and PHP 7.4+. The first release works without Pods. Publication records still come from pubbase.json; moving records and shared image metadata into Pods is a later increment.

## Install from GitHub

1. Push the theme commit on main to neuromancerxl/agarwalgroup-website.
2. In Deployer for Git, install it as a **Theme**, selecting this repository and branch main.
3. Open Appearance → Themes and activate **Agarwal Group**. Activation does not import content or change Reading settings.
4. Open Appearance → Agarwal setup. The table identifies existing pages, which setup preserves.
5. Choose whether to publish new starter content or create drafts. Optionally select the published Home page as the front page. Click **Create missing starter content**.
6. Review the six pages, the news Posts, navigation, and media. If you created drafts, publish reviewed items in Pages/Posts, then choose Home under Settings → Reading.

Setup can be repeated: it reuses existing page/post slugs and imported media. It never updates an existing page's content or republishes a draft on a later run. If you already have a page named Home, Research, Publications, People, News, or Contact, edit it and insert the corresponding Agarwal starting layout from Patterns if wanted.

The Research and PI descriptions, six PhD biographies/portraits, and alumni rows still contain the existing placeholders. Sai's biography and photograph are preserved. Review placeholder content before publishing.

Keep the installed theme folder name unchanged for later deployments. A theme update changes code, not existing page content or uploaded images. Changes made to starter patterns apply when a pattern is inserted, not to previously created pages. Template changes saved in Appearance → Editor override matching template files until that customization is cleared or reconciled.

## Edit the website

- **Text and profiles:** Pages → choose the page → edit its blocks → Save. These are ordinary headings, paragraphs, images, groups, and tables.
- **Homepage slides:** Select the Gallery inside the slideshow. Use WordPress's Add/Replace and Gallery ordering controls. The Research slideshow style keeps a 4:3 frame and full-image containment. The editor shows the images together for editing; the website plays them sequentially.
- **Figure captions and portraits:** Select the Image block. Captions are editable below the image. Scientific figures use containment; portraits use a square crop. This release uses per-placement captions, not synchronized Media Library metadata.
- **Backgrounds:** The theme defaults scientific frames to white. The existing phase classes retain their dark backgrounds. A shared image color-picker form is deferred to the Pods increment.
- **Selected papers:** Each citation is a Publication reference block. Choose a paper in its block settings. Use WordPress's normal move/duplicate/remove controls to arrange references. Compact research links are an option in the same sidebar.
- **Publication catalog:** The Publication catalog block includes all records, with topic/search/year filtering and ten per page. The initial output, filtering form, and page links also work without JavaScript. Live search is progressively added in the browser.
- **News:** Use normal Posts in the News category. Set the title, date, and a manual Excerpt. The optional News source link field makes linked headlines open an external article; leave it empty for an on-site post. The full post body can contain the source link and longer text. Home shows two entries; the News page shows summaries and pagination. Uncategorized posts do not enter those feeds.
- **Header/footer/menu:** Appearance → Editor. The site title comes from WordPress's site settings. The affiliation line and contact links are editable blocks.

The native mobile navigation opens with WordPress's menu button. Research and People retain their section navigation. The slideshow pauses on hover, keyboard focus, and inactive tabs; a Pause/Play button and reduced-motion support are included.

## Publication records

pubbase.json is the sole citation source for this release. Keep permanent IDs, full author names, and the shared topic IDs. Use a DOI identifier or full URL; URL takes precedence. The existing incomplete-author flags and collective attribution remain intact.

Up to three non-Ritesh authors are shown using initials; longer or incomplete lists add et al. If present in the record, R. Agarwal follows a middle dot. Changes to a record update every reference without editing each page.

The catalog currently contains 13 records. Nine author lists are incomplete and five records have no topic assignment. No external metadata lookup or automatic JSON/dashboard synchronization runs.

## Source layout

| Path | Purpose |
| --- | --- |
| style.css | Theme header, original design, native-block layout adjustments |
| theme.json | Editor/global colors, local fonts, content widths |
| functions.php | Theme hooks, asset loading, block registration, news source link |
| inc/setup.php | Explicit starter-content and Media Library import |
| inc/publications.php | JSON loading, shared citation formatting, server filtering/pagination |
| templates/ | Front page, regular page, post, fallback, and 404 templates |
| parts/ | Shared header/footer entry points |
| patterns/ | Native-block starting layouts and shared site parts |
| blocks/ | Publication reference/catalog metadata and editor controls |
| editor.css | Editor-specific adjustments |
| script.js | Gallery slideshow and section/navigation enhancement |
| publications.js | Live catalog filtering/pagination enhancement |
| images/, fonts/ | Local assets and font license files |

The demo and layout Git branches preserve the static versions. main contains the WordPress theme. Open the theme in a WordPress installation to preview it; VS Code Live Server cannot execute PHP or the block templates.

## Verification

Verified in a disposable WordPress 7.1.4 installation with PHP 8.3.36: theme activation; PHP/JavaScript syntax; all 247 page/template blocks accepted by the editor; repeatable setup and preservation of existing content; server-side search/topic filtering and pagination; citation and catalog previews in the actual editor; and Home, Research, People, and Publications at a 390 px mobile viewport without page-wide overflow. Test installations and archives stay outside this repository. Review the installed theme on the Penn host after deployment as well.
