<?php
/**
 * Title: News starting layout
 * Slug: agarwal/news
 * Categories: agarwal
 * Block Types: core/post-content
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!-- wp:group {"className":"page wrap","layout":{"type":"default"}} -->
<div class="wp-block-group page wrap">
<!-- wp:group {"className":"page-heading","layout":{"type":"default"}} -->
<div class="wp-block-group page-heading">
<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">News</h1>
<!-- /wp:heading -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"news-list","layout":{"type":"default"}} -->
<div class="wp-block-group news-list">
<!-- wp:query {"queryId":2,"query":{"perPage":10,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false,"taxQuery":{"category":[<?php $news_category = get_category_by_slug( 'news' ); echo $news_category ? (int) $news_category->term_id : PHP_INT_MAX; ?>]}},"className":"agarwal-news-query"} -->
<div class="wp-block-query agarwal-news-query">
<!-- wp:post-template -->
<!-- wp:post-date {"format":"d M Y"} /-->
<!-- wp:post-title {"level":2,"isLink":true} /-->
<!-- wp:post-excerpt {"moreText":"","showMoreOnNewLine":false} /-->
<!-- /wp:post-template -->
<!-- wp:query-no-results -->
<!-- wp:paragraph -->
<p>News updates will appear here.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->
<!-- wp:query-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:query-pagination-previous {"label":"← Previous"} /-->
<!-- wp:query-pagination-numbers /-->
<!-- wp:query-pagination-next {"label":"Next →"} /-->
<!-- /wp:query-pagination -->
</div>
<!-- /wp:query -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
