<?php
/**
 * Title: Group header
 * Slug: agarwal/header
 * Categories: agarwal
 * Inserter: no
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!-- wp:group {"className":"site-header","layout":{"type":"default"}} -->
<div class="wp-block-group site-header">
<!-- wp:group {"className":"wrap header-inner","layout":{"type":"default"}} -->
<div class="wp-block-group wrap header-inner">
<!-- wp:group {"className":"header-brand","layout":{"type":"default"}} -->
<div class="wp-block-group header-brand">
<!-- wp:site-title {"level":0,"className":"site-name"} /-->
<!-- wp:paragraph {"className":"site-affiliation"} -->
<p class="site-affiliation">University of Pennsylvania · Materials Science and Engineering</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:navigation {"overlayMenu":"mobile","className":"main-nav","layout":{"type":"flex","justifyContent":"right"}} -->
<?php foreach ( agarwal_starter_pages() as $slug => $label ) : ?>
<!-- wp:navigation-link {"label":"<?php echo esc_attr( 'home' === $slug ? 'HOME' : $label ); ?>","url":"<?php echo esc_url( 'home' === $slug ? home_url( '/' ) : agarwal_page_url( $slug ) ); ?>","className":"<?php echo 'home' === $slug ? 'home-link' : ''; ?>","kind":"custom"} /-->
<?php endforeach; ?>
<!-- /wp:navigation -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
