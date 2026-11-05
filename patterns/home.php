<?php
/**
 * Title: Home starting layout
 * Slug: agarwal/home
 * Categories: agarwal
 * Block Types: core/post-content
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!-- wp:group {"className":"page","layout":{"type":"default"}} -->
<div class="wp-block-group page">
<!-- wp:group {"className":"home-top","layout":{"type":"default"}} -->
<div class="wp-block-group home-top">
<!-- wp:group {"tagName":"section","className":"home-intro wrap","layout":{"type":"default"}} -->
<section class="wp-block-group home-intro wrap">
<!-- wp:group {"className":"home-text","layout":{"type":"default"}} -->
<div class="wp-block-group home-text">
<!-- wp:paragraph {"className":"eyebrow"} -->
<p class="eyebrow">Research / Penn Materials Science</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":1,"anchor":"home-title"} -->
<h1 id="home-title" class="wp-block-heading"><span class="hero-quantum">Quantum materials,</span><br>
              <span class="hero-photonics">photonics</span> and<br>
              <span class="hero-phase">phase change</span></h1>
<!-- /wp:heading -->
<!-- wp:paragraph {"className":"home-summary"} -->
<p class="home-summary">We study light–matter interactions and the structural and electronic properties of materials at the nanoscale.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"slideshow","layout":{"type":"default"}} -->
<div class="wp-block-group slideshow">
<!-- wp:gallery {"linkTo":"none","sizeSlug":"full","className":"is-style-agarwal-slideshow"} -->
<figure class="wp-block-gallery has-nested-images columns-default is-cropped is-style-agarwal-slideshow">
<?php echo agarwal_starter_image( 'quantum.webp', 'MoTe2 structures and photocurrent plots from a quantum geometry preprint', '<strong>Quantum geometry in MoTe₂.</strong> Fig. 1, Chen et al., <a href="https://arxiv.org/html/2609.38102v1" target="_blank" rel="noopener">arXiv preprint, 2026</a>.', 'slide quantum-slide' ); ?>
<?php echo agarwal_starter_image( 'structured-light.webp', 'Five optical vortex modes labeled from plus two to minus two', '<strong>Structured light.</strong> Optical vortex modes from <a href="https://www.engineering.upenn.edu/stories/penn-engineers-develop-first-tunable-chip-based-vortex-microlaser-and-detector-encode-e490e7983997/" target="_blank" rel="noopener">Penn Engineering, 2020</a>.', 'slide photonics-slide' ); ?>
<?php echo agarwal_starter_image( 'phase.webp', 'Illustration of electrically driven amorphization in indium selenide', '<strong>Phase-change materials.</strong> Illustration by Akanksha Jain, <a href="https://www.engineering.upenn.edu/stories/breakthrough-in-energy-efficient-avalanche-based-amorphization-could-revolutionize-data-storage/" target="_blank" rel="noopener">Penn Engineering, 2024</a>.', 'slide phase-slide' ); ?>
</figure>
<!-- /wp:gallery -->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"wrap home-lower","layout":{"type":"default"}} -->
<div class="wp-block-group wrap home-lower">
<!-- wp:group {"tagName":"section","className":"home-section","layout":{"type":"default"}} -->
<section class="wp-block-group home-section">
<!-- wp:group {"className":"section-title","layout":{"type":"default"}} -->
<div class="wp-block-group section-title">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">Recent papers</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( agarwal_page_url( 'publications' ) ); ?>">Publications</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"home-papers","layout":{"type":"default"}} -->
<div class="wp-block-group home-papers">
<!-- wp:agarwal/paper {"paperId":"chen2026photogalvanic","compact":false} /-->
<!-- wp:agarwal/paper {"paperId":"wang2026flatbands","compact":false} /-->
<!-- wp:agarwal/paper {"paperId":"khandelwal2025chiralresponse","compact":false} /-->
<!-- wp:agarwal/paper {"paperId":"modi2024amorphization","compact":false} /-->
<!-- wp:agarwal/paper {"paperId":"ji2024optotwistronic","compact":false} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
<!-- wp:group {"tagName":"section","className":"home-section home-news","layout":{"type":"default"}} -->
<section class="wp-block-group home-section home-news">
<!-- wp:group {"className":"section-title","layout":{"type":"default"}} -->
<div class="wp-block-group section-title">
<!-- wp:heading {"level":2} -->
<h2 class="wp-block-heading">News</h2>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p><a href="<?php echo esc_url( agarwal_page_url( 'news' ) ); ?>">More</a></p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:query {"queryId":1,"query":{"perPage":2,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":{"category":[<?php $news_category = get_category_by_slug( 'news' ); echo $news_category ? (int) $news_category->term_id : PHP_INT_MAX; ?>]}},"className":"agarwal-news-query"} -->
<div class="wp-block-query agarwal-news-query">
<!-- wp:post-template -->
<!-- wp:post-date {"format":"d M Y"} /-->
<!-- wp:post-title {"level":3,"isLink":true} /-->
<!-- /wp:post-template -->
<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>News updates will appear here.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->
</div>
<!-- /wp:query -->
</section>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
