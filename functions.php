<?php
/** Theme setup and asset loading. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/inc/publications.php';
require_once __DIR__ . '/inc/setup.php';

function agarwal_theme_setup() {
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( array( 'style.css', 'editor.css' ) );
	add_theme_support( 'responsive-embeds' );
	register_block_pattern_category( 'agarwal', array( 'label' => __( 'Agarwal Group', 'agarwal-group' ) ) );
}
add_action( 'after_setup_theme', 'agarwal_theme_setup' );

function agarwal_asset_version( $file ) {
	return (string) filemtime( get_theme_file_path( $file ) );
}

function agarwal_enqueue_assets() {
	wp_enqueue_style( 'agarwal-theme', get_stylesheet_uri(), array(), agarwal_asset_version( 'style.css' ) );
	wp_enqueue_script( 'agarwal-site', get_theme_file_uri( 'script.js' ), array(), agarwal_asset_version( 'script.js' ), true );
}
add_action( 'wp_enqueue_scripts', 'agarwal_enqueue_assets' );

function agarwal_register_blocks() {
	wp_register_script(
		'agarwal-editor',
		get_theme_file_uri( 'blocks/editor.js' ),
		array( 'wp-blocks', 'wp-block-editor', 'wp-components', 'wp-element', 'wp-server-side-render', 'wp-i18n' ),
		agarwal_asset_version( 'blocks/editor.js' ),
		true
	);

	$catalog = agarwal_publication_catalog();
	$options = array();
	if ( ! is_wp_error( $catalog ) ) {
		foreach ( $catalog['papers'] as $paper ) {
			$options[] = array( 'value' => $paper['id'], 'label' => $paper['year'] . ' — ' . $paper['title'] );
		}
	}
	wp_add_inline_script( 'agarwal-editor', 'window.agarwalPaperOptions = ' . wp_json_encode( $options ) . ';', 'before' );

	register_block_type( __DIR__ . '/blocks/paper', array( 'render_callback' => 'agarwal_render_paper_block' ) );
	register_block_type( __DIR__ . '/blocks/publications', array( 'render_callback' => 'agarwal_render_publications_block' ) );
	register_block_style( 'core/gallery', array( 'name' => 'agarwal-slideshow', 'label' => __( 'Research slideshow', 'agarwal-group' ) ) );
}
add_action( 'init', 'agarwal_register_blocks' );

/** Starter images use Media Library URLs after the optional setup action. */
function agarwal_image_url( $filename ) {
	$images = get_option( 'agarwal_theme_images', array() );
	if ( isset( $images[ $filename ] ) ) {
		$url = wp_get_attachment_url( (int) $images[ $filename ] );
		if ( $url ) {
			return $url;
		}
	}
	return get_theme_file_uri( 'images/' . $filename );
}

function agarwal_image_id( $filename ) {
	$images = get_option( 'agarwal_theme_images', array() );
	return isset( $images[ $filename ] ) ? (int) $images[ $filename ] : 0;
}

function agarwal_page_url( $slug ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	if ( $page && 'publish' === $page->post_status ) {
		return get_permalink( $page );
	}
	if ( ! get_option( 'permalink_structure' ) ) {
		return add_query_arg( 'pagename', $slug, home_url( '/' ) );
	}
	return home_url( '/' . $slug . '/' );
}

/** Seeded news keeps its source link in editable post metadata. */
function agarwal_register_news_meta() {
	register_post_meta( 'post', 'agarwal_news_url', array(
		'type'              => 'string',
		'single'            => true,
		'show_in_rest'      => true,
		'sanitize_callback' => 'esc_url_raw',
		'auth_callback'     => function ( $allowed, $meta_key, $post_id ) {
			return current_user_can( 'edit_post', $post_id );
		},
	) );
}
add_action( 'init', 'agarwal_register_news_meta' );

function agarwal_news_link( $content, $block, $instance ) {
	if ( empty( $instance->context['postId'] ) || empty( $block['attrs']['isLink'] ) ) {
		return $content;
	}
	$url = get_post_meta( $instance->context['postId'], 'agarwal_news_url', true );
	if ( $url && in_array( wp_parse_url( $url, PHP_URL_SCHEME ), array( 'http', 'https' ), true ) ) {
		$tag = new WP_HTML_Tag_Processor( $content );
		if ( $tag->next_tag( 'a' ) ) {
			$tag->set_attribute( 'href', esc_url( $url ) );
			$tag->set_attribute( 'target', '_blank' );
			$tag->set_attribute( 'rel', 'noopener' );
			return $tag->get_updated_html();
		}
	}
	return $content;
}
add_filter( 'render_block_core/post-title', 'agarwal_news_link', 10, 3 );

function agarwal_add_news_fields() {
	add_meta_box( 'agarwal-news-source', __( 'News source link', 'agarwal-group' ), 'agarwal_news_fields', 'post', 'normal' );
}
add_action( 'add_meta_boxes', 'agarwal_add_news_fields' );

function agarwal_news_fields( $post ) {
	wp_nonce_field( 'agarwal_news_fields', 'agarwal_news_nonce' );
	?>
	<p><label for="agarwal-news-url"><?php esc_html_e( 'External article URL (optional)', 'agarwal-group' ); ?></label></p>
	<input type="url" class="widefat" id="agarwal-news-url" name="agarwal_news_url" value="<?php echo esc_attr( get_post_meta( $post->ID, 'agarwal_news_url', true ) ); ?>">
	<p class="description"><?php esc_html_e( 'When supplied, linked news headlines open this article. Leave blank to open the WordPress post.', 'agarwal-group' ); ?></p>
	<?php
}

function agarwal_save_news_fields( $post_id ) {
	if ( ! isset( $_POST['agarwal_news_nonce'] ) || ! is_string( $_POST['agarwal_news_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['agarwal_news_nonce'] ) ), 'agarwal_news_fields' ) ) {
		return;
	}
	if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['agarwal_news_url'] ) && is_string( $_POST['agarwal_news_url'] ) ) {
		update_post_meta( $post_id, 'agarwal_news_url', esc_url_raw( wp_unslash( $_POST['agarwal_news_url'] ), array( 'http', 'https' ) ) );
	}
}
add_action( 'save_post_post', 'agarwal_save_news_fields' );
