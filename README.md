# Agarwal Group demonstration website

This folder contains a static demonstration of the proposed Agarwal Group website. It uses ordinary HTML, CSS, and JavaScript. There is no framework, package manager, database, PHP, or build step.

The demonstration is intentionally provisional. The homepage has the approved visual direction, while unapproved research and profile descriptions use `Lorem ipsum` placeholder text. The site contains no author, creator, or designer attribution.

## 1. Open and edit the website in VS Code

### Recommended method: Live Server

1. Unzip the project folder if it arrived as a ZIP file.
2. Open Visual Studio Code.
3. Choose **File → Open Folder**.
4. Select the `agarwal-demo` folder, not only `index.html`.
5. In the left sidebar, open **Extensions** or press `Ctrl+Shift+X`.
6. Search for **Live Server** by Ritwick Dey and install it.
7. Open `index.html` in VS Code.
8. Right-click anywhere inside the file and choose **Open with Live Server**. You can also click **Go Live** in the lower-right corner.
9. The browser should open an address similar to `http://127.0.0.1:5500/index.html`.

Keep VS Code and the browser open together. Save a change with `Ctrl+S`; Live Server should refresh the page automatically.

Do not use the **Run Code** button for this project. HTML is displayed by a browser, and Live Server provides the small local web server that the browser expects.

### Quick method without an extension

Double-click `index.html`, or right-click it and choose Edge or Chrome. The site works when opened directly because it does not fetch local data with JavaScript. You will need to refresh the browser manually after saving changes.

### If images or fonts disappear

Open the whole folder and keep the directory structure intact:

```text
agarwal-demo/
├── index.html
├── style.css
├── script.js
├── fonts/
└── images/
```

For example, `src="images/phase.webp"` means the browser expects an `images` folder beside `index.html`. Moving `index.html` by itself breaks that path.

## 2. What each file does

| File or folder | Purpose |
| --- | --- |
| `index.html` | All page content, links, headings, paper entries, captions, and contact information |
| `style.css` | Colors, fonts, spacing, desktop layout, mobile layout, and visual states |
| `script.js` | Slideshow, active research-topic highlight, publication filtering, and navigation between sections |
| `images/` | Research figures, Ritesh Agarwal photograph, and Penn Engineering logo |
| `fonts/` | Local font files, so the design does not depend on Google Fonts loading |

The project is organized this way because it is easier to learn and edit than a single file containing 1.7 MB of embedded image and font data.

## 3. How the apparent pages work

This is one HTML document with six large containers:

```html
<div class="page" data-page="home">...</div>
<div class="page" data-page="research" hidden>...</div>
<div class="page" data-page="publications" hidden>...</div>
```

The navigation uses hash links such as `#research` and `#people`. The `openPage()` function in `script.js` reads the text after `#`, reveals the matching container, and hides the others.

Therefore:

- `index.html#home` shows the homepage.
- `index.html#research` shows Research.
- `index.html#publications` shows Publications.
- Refreshing a section keeps that section open because the hash remains in the URL.

For the eventual WordPress website, these sections will become separate WordPress pages or templates. The hash navigation exists only to make one portable demonstration file behave like a small site.

## 4. Page organization

### Header

The header contains the group name, Penn affiliation, and five navigation links. It uses the same dark background as the homepage opening so the two areas appear continuous.

### Homepage

The homepage has two parts:

1. A dark opening with the research heading and image slideshow.
2. A white lower area with recent papers and news.

The opening is a two-column CSS Grid. The text takes `0.85fr` and the slideshow takes `1.15fr`, with a `62px` gap. Below `760px`, the grid changes to one column.

The slideshow contains three ordinary `<figure>` elements. JavaScript changes their `hidden` property. Every slide has a `data-topic` value, and each phrase in the heading has a corresponding `data-hero-topic` value. When a slide appears, JavaScript adds the `active-topic` class to the matching phrase.

The slideshow advances every 6.5 seconds. It pauses while the pointer is over it and does not auto-advance when the visitor has enabled reduced motion in their operating system. Prev and Next buttons change the same `currentSlide` number manually.

### Research

The introductory sentence describes the group’s broad experimental approach. A topic menu provides direct links to the three research sections.

Each research area currently contains:

- a title;
- placeholder `Lorem ipsum` copy;
- selected paper links;
- one credited research image.

The placeholder copy prevents the demonstration from presenting an unreviewed interpretation of the group’s research as approved language.

Each topic now has exactly 150 words of placeholder description in two paragraphs. On wide screens, a sticky menu on the left links to the three topics and marks the topic being read. Below 960 pixels, the menu moves above the content. Its links use ordinary section IDs, such as `#nanophotonics`; `openPage()` recognizes these IDs and keeps Research open on clicks, refreshes, and browser history navigation.

Research figures use a consistent 4:3 frame with `object-fit: contain`, so diagrams and labels remain visible. Descriptions wrap around the figures on wide screens, while selected papers stay below both text and figure. Below 760 pixels, the figures and text stack. To change a figure’s background, set `--research-image-background` on its figure class; `.phase-research-image` shows an example. Research figures remain static.

### Publications

The Publications page contains 13 papers written directly as HTML `<article>` elements. A horizontal row above the search controls filters the list by research topic:

- All;
- Quantum materials;
- Nanophotonics;
- Phase-change materials.

The active topic has a burgundy underline. On narrow screens, the topic row scrolls horizontally. Topic selection, text search, and the year filter work together; the existing newest-first order is preserved. An empty result shows “No papers match these filters.”

Each paper’s `data-topics` attribute contains topic IDs separated by spaces, for example `data-topics="quantum-materials nanophotonics"`. One paper can belong to multiple topics without duplicating its row. Initial assignments follow the existing Research page selections; five papers still need topic review and remain visible under All. To assign one, edit its `data-topics` attribute. These attributes are temporary metadata until the shared publication catalog is built.

The filtering code loops over the rows, checks their text, `data-year`, and `data-topics`, then sets `hidden`. There is no database, external metadata lookup, or special library.

### People

People has three sections: Principal investigator, PhD students, and Alumni. Its sticky PI / PhDs / Alumni menu uses the same styles and navigation behavior as Research, including direct section links such as `#phd-students` and an active marker while scrolling. On narrower screens, the menu moves above the content.

Ritesh Agarwal’s profile keeps his title, photograph, source credit, email, Penn profile, and publications link. His biography contains exactly 200 words of Lorem ipsum in two paragraphs.

There are seven PhD profile placeholders, arranged two per row above 760 pixels and one per row below. Each has a square photo placeholder, a name, a `YYYY–present` joining-year field, and exactly 100 words of Lorem ipsum. Replace the placeholder photo `<div>` with an `<img class="member-photo" src="images/your-photo.webp" alt="Name">` when a portrait is available. Student names, dates, and photographs are deliberately unspecified until the roster is supplied.

Alumni uses a three-column table: name, graduation year, and present position (role and institution). Three clearly marked example rows demonstrate the layout without claiming an actual alumni count. Replace those rows with approved information; remove or add rows as needed. On small screens, the table can scroll horizontally.

### News

The three demonstration entries are hardcoded in the HTML and link to Penn sources. In WordPress these should become ordinary Posts in a News category.

### Contact

The page introduction is exactly 20 words:

> Interested in joining the group, collaborating, or learning more about our work? Please contact us using the information listed below.

The two columns contain the PI contact and LRSM laboratory address.

### Footer

The footer contains a locally stored Penn Engineering logo and group email. The local image means the logo remains visible when the demonstration computer is offline.

## 5. Typography

The site uses two typefaces:

| Typeface | Weight | Use |
| --- | --- | --- |
| Instrument Serif | 400 | Large page titles, homepage research statement, and section headings |
| Manrope | 400 | Body text, navigation, captions, metadata, and controls |
| Manrope | 600 | Group name, paper titles, labels, and small emphasis |

The fonts are declared at the top of `style.css` with `@font-face` and loaded from the `fonts` folder. Fallbacks are included:

```css
font-family: "Instrument Serif", Georgia, serif;
font-family: "Manrope", Arial, sans-serif;
```

If a font file fails, the browser uses Georgia for headings and Arial for ordinary text. Instrument Serif gives the large headings an editorial scientific-publication quality; Manrope keeps the small information dense but readable.

## 6. Color system

The principal colors are declared once in `:root` near the top of `style.css`:

| CSS name | Hex | Use |
| --- | --- | --- |
| `--white` | `#ffffff` | Main page background and figure backgrounds |
| `--ink` | `#17211e` | Main dark green-black text |
| `--muted` | `#5b6762` | Secondary text, dates, captions, and metadata |
| `--line` | `#d4dcd5` | Dividers and light borders |
| `--accent` | `#803d50` | Links, years, roles, and restrained burgundy emphasis |
| `--dark` | `#131716` | Header and homepage opening |
| `--pink` | `#e5b3ac` | Active research phrase and slideshow hover details |

The site is white below the homepage opening because the earlier off-white background felt wrong. The dark opening gives the research imagery more contrast. Burgundy relates loosely to Penn’s red without turning the entire site red and blue. The pale pink is used only inside the dark opening.

To change a sitewide color, change the hex value in `:root`; every selector using that variable updates.

## 7. Layout and spacing

The main content width is controlled by:

```css
--page-width: 1160px;
```

The `.wrap` class centers content, limits it to 1160 pixels, and adds `28px` side padding. On screens narrower than `760px`, the side padding becomes `20px` and multi-column layouts stack vertically.

The site primarily uses CSS Grid for large page layouts:

- homepage opening: text plus slideshow;
- homepage lower area: papers plus news;
- research areas: copy plus image;
- People: portrait plus profile;
- Contact: two information columns.

Flexbox is used for smaller one-dimensional arrangements such as the header, navigation, section-title rows, and footer.

Horizontal rules are usually `1px` solid `--line`. There are few boxes or rounded cards. This keeps the page closer to a research group site than a product landing page.

## 8. Common edits

### Change ordinary text

Open `index.html`, use `Ctrl+F` to find the visible sentence, edit it, and save.

### Replace placeholder research copy

Find the three `research-description` blocks in `index.html`. Each contains two placeholder paragraphs totaling 150 words. Replace both paragraphs with approved copy. The PI description also remains placeholder text; the Publications introduction has been removed.

### Change a homepage slide

Find the relevant `<figure class="slide">` in `index.html`. Replace the file in its `<img src>` or add an image to `images/` and change the filename. Keep the `data-topic` value as `quantum`, `photonics`, or `phase` so the correct heading phrase is highlighted.

### Add a publication

Copy one complete publication `<article class="paper-row">` inside `#publication-list`. Update:

- `data-year`;
- `data-topics` (space-separated topic IDs, or empty until reviewed);
- the visible year;
- paper URL;
- title;
- author line;
- journal;
- optional Preprint label.

If the year is new, add a matching `<option>` to the `year-filter` select. Search works automatically because it reads the row’s visible text.

### Add a news item

Copy one `<article>` inside `.news-list` and edit its date, title, description, link, and source. If it should also appear on the homepage, copy a shorter version into `.home-news`.

### Change the slideshow speed

In `script.js`, find:

```js
}, 6500);
```

`6500` means 6,500 milliseconds, or 6.5 seconds.

### Change the mobile breakpoint

In `style.css`, find:

```css
@media (max-width: 760px)
```

The mobile rules apply at 760 pixels and below.

## 9. What is real and what is provisional

Real demonstration content:

- group and university names;
- Ritesh Agarwal’s listed position and contact links;
- selected paper titles, author lines, years, venues, and links;
- news entries linked to Penn sources;
- laboratory address;
- credited research figures.

Provisional content:

- research-area descriptions;
- PI biographical paragraph;
- complete current group roster;
- the selection and ordering of papers and news;
- final image permissions and approved Penn branding asset.

The research figures retain source credits. Image reuse should be confirmed before public launch. The logo file in this demonstration is the same publicly available Penn Engineering mark used in the earlier prototype; the production WordPress site should use the asset supplied or approved by Penn Engineering.

## 10. How this maps to WordPress later

The demonstration shows appearance and behavior. In WordPress, content that future group members change should be stored as editable records:

| Demonstration item | WordPress destination |
| --- | --- |
| Header, footer, fonts, colors, layout | Block theme and Site Editor |
| Homepage introduction | Editable page/block fields |
| Research slides | Research Slide entries with image, caption, credit, order, and topic |
| Publications | Publication entries with title, authors, year, venue, link, and status |
| Research text | Normal page blocks or structured research-area entries |
| People | Person entries with name, role, photo, bio, and links |
| News | Regular WordPress Posts |
| Contact information | Editable page blocks or site settings |

The custom code should handle presentation and slideshow mechanics. A future administrator should change a slide, paper, person, or news item in the WordPress dashboard rather than editing this HTML.

## 11. Demonstration limitations

- It is one static HTML document pretending to contain multiple pages.
- Publication search only searches the visible text already loaded in the page.
- There is no WordPress admin, database, analytics, contact form, authentication, or content workflow.
- External publication and Penn links need internet access.
- Content changes are made in code until the WordPress conversion.

Those limits are appropriate for a design demonstration. The file shows the proposed structure and interaction without claiming that the backend has already been built.
