<?php
/** An explicit, repeatable setup action; theme activation never imports content. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function agarwal_setup_menu() {
	add_theme_page( __( 'Agarwal theme setup', 'agarwal-group' ), __( 'Agarwal setup', 'agarwal-group' ), 'manage_options', 'agarwal-setup', 'agarwal_setup_screen' );
}
add_action( 'admin_menu', 'agarwal_setup_menu' );

function agarwal_starter_pages() {
	return array( 'home' => 'Home', 'research' => 'Research', 'publications' => 'Publications', 'people' => 'People', 'news' => 'News', 'contact' => 'Contact' );
}

function agarwal_pattern_content( $name ) {
	$allowed = array_merge( array_keys( agarwal_starter_pages() ), array( 'header', 'footer' ) );
	if ( ! in_array( $name, $allowed, true ) ) {
		return '';
	}
	ob_start();
	include get_theme_file_path( 'patterns/' . $name . '.php' );
	return trim( ob_get_clean() );
}

/** Match the native Image block markup so captions and replacements stay editable. */
function agarwal_starter_image( $filename, $alt, $caption = '', $class_name = '', $href = '' ) {
	$id = agarwal_image_id( $filename );
	$attributes = array( 'sizeSlug' => 'full', 'linkDestination' => $href ? 'custom' : 'none', 'className' => $class_name );
	if ( $id ) { $attributes['id'] = $id; }
	$image = '<img src="' . esc_url( agarwal_image_url( $filename ) ) . '" alt="' . esc_attr( $alt ) . '"' . ( $id ? ' class="wp-image-' . $id . '"' : '' ) . '/>';
	if ( $href ) {
		$attributes['href'] = $href;
		$attributes['linkTarget'] = '_blank';
		$attributes['rel'] = 'noopener';
		$image = '<a href="' . esc_url( $href ) . '" target="_blank" rel="noopener">' . $image . '</a>';
	}
	$html = '<figure class="wp-block-image size-full' . ( $class_name ? ' ' . esc_attr( $class_name ) : '' ) . '">' . $image .
		( $caption ? '<figcaption class="wp-element-caption">' . wp_kses_post( $caption ) . '</figcaption>' : '' ) . '</figure>';
	return serialize_block( array( 'blockName' => 'core/image', 'attrs' => $attributes, 'innerBlocks' => array(), 'innerHTML' => $html, 'innerContent' => array( $html ) ) );
}

/** Find existing imported assets before copying bytes to uploads. */
function agarwal_import_starter_images() {
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$images = get_option( 'agarwal_theme_images', array() );
	$files = array(
		'quantum.webp' => 'MoTe2 structures and photocurrent plots',
		'structured-light.webp' => 'Five optical vortex modes',
		'phase.webp' => 'Electrically driven amorphization in indium selenide',
		'ritesh.webp' => 'Ritesh Agarwal beside a microscope in his laboratory',
		'saidutt.jpg' => 'Sai Dutt Kotrakona',
		'penn-engineering.png' => 'Penn Engineering',
	);
	foreach ( $files as $filename => $alt ) {
		if ( ! empty( $images[ $filename ] ) && wp_get_attachment_url( $images[ $filename ] ) ) {
			continue;
		}
		$existing = get_posts( array( 'post_type' => 'attachment', 'post_status' => 'inherit', 'posts_per_page' => 1, 'meta_key' => '_agarwal_starter_file', 'meta_value' => $filename, 'fields' => 'ids' ) );
		if ( $existing ) {
			$images[ $filename ] = $existing[0];
			continue;
		}
		$source = get_theme_file_path( 'images/' . $filename );
		if ( ! is_readable( $source ) ) {
			return new WP_Error( 'agarwal_image', sprintf( __( 'Starter image is missing: %s', 'agarwal-group' ), $filename ) );
		}
		$upload = wp_upload_bits( $filename, null, file_get_contents( $source ) );
		if ( $upload['error'] ) {
			return new WP_Error( 'agarwal_upload', $upload['error'] );
		}
		$type = wp_check_filetype( $filename );
		$id = wp_insert_attachment( array( 'post_title' => pathinfo( $filename, PATHINFO_FILENAME ), 'post_mime_type' => $type['type'], 'post_status' => 'inherit' ), $upload['file'], 0, true );
		if ( is_wp_error( $id ) ) {
			wp_delete_file( $upload['file'] );
			return $id;
		}
		update_post_meta( $id, '_wp_attachment_image_alt', $alt );
		update_post_meta( $id, '_agarwal_starter_file', $filename );
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
		$images[ $filename ] = $id;
		// Save each successful import so retries cannot duplicate it after a later failure.
		update_option( 'agarwal_theme_images', $images );
	}
	update_option( 'agarwal_theme_images', $images );
	return $images;
}

function agarwal_create_starter_content( $publish = false, $make_home = false ) {
	if ( ! current_user_can( 'manage_options' ) || ! current_user_can( 'upload_files' ) ) {
		return new WP_Error( 'agarwal_permissions', __( 'Administrator and media upload permissions are required.', 'agarwal-group' ) );
	}
	$import = agarwal_import_starter_images();
	if ( is_wp_error( $import ) ) {
		return $import;
	}
	$status = $publish ? 'publish' : 'draft';
	$term = term_exists( 'news', 'category' );
	if ( ! $term ) { $term = wp_insert_term( 'News', 'category', array( 'slug' => 'news' ) ); }
	if ( is_wp_error( $term ) ) { return $term; }
	$category_id = (int) ( is_array( $term ) ? $term['term_id'] : $term );
	$pages = array();
	foreach ( agarwal_starter_pages() as $slug => $title ) {
		$existing = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $existing ) {
			$pages[ $slug ] = $existing->ID;
			continue;
		}
		$id = wp_insert_post( array(
			'post_type' => 'page', 'post_status' => $status, 'post_name' => $slug,
			'post_title' => $title, 'post_content' => agarwal_pattern_content( $slug ),
			'menu_order' => count( $pages ), 'comment_status' => 'closed',
		), true );
		if ( is_wp_error( $id ) ) { return $id; }
		$pages[ $slug ] = $id;
	}

	update_option( 'agarwal_news_category', $category_id );

	$items = array(
		array( 'slug' => 'ritesh-agarwal-aps-fellow', 'title' => 'Ritesh Agarwal elected an APS Fellow', 'date' => '2025-10-21 12:00:00', 'excerpt' => 'Penn Almanac reports his election to the 2025 class of APS Fellows.', 'url' => 'https://almanac.upenn.edu/articles/ritesh-agarwal-and-douglas-jerolmack-2025-american-physical-society-fellows', 'source' => 'Penn Almanac' ),
		array( 'slug' => 'electrically-driven-amorphization', 'title' => 'Electrically driven amorphization in In₂Se₃', 'date' => '2024-11-14 12:00:00', 'excerpt' => 'Penn Today covers the group’s 2024 Nature paper on solid-state amorphization.', 'url' => 'https://penntoday.upenn.edu/news/breakthrough-energy-saving-process-could-transform-data-storage', 'source' => 'Penn Today' ),
		array( 'slug' => 'srinivasa-ramanujan-distinguished-scholar', 'title' => 'Srinivasa Ramanujan Distinguished Scholar', 'date' => '2022-12-06 12:00:00', 'excerpt' => 'Penn Almanac announces Ritesh Agarwal’s distinguished scholar appointment.', 'url' => 'https://almanac.upenn.edu/articles/ritesh-agarwal-srinivasa-ramanujan-distinguished-scholar', 'source' => 'Penn Almanac' ),
	);
	foreach ( $items as $item ) {
		if ( get_page_by_path( $item['slug'], OBJECT, 'post' ) ) { continue; }
		$content = '<!-- wp:paragraph --><p>' . esc_html( $item['excerpt'] ) . '</p><!-- /wp:paragraph -->' .
			'<!-- wp:paragraph --><p><a href="' . esc_url( $item['url'] ) . '" target="_blank" rel="noopener">' . esc_html( $item['source'] ) . '</a></p><!-- /wp:paragraph -->';
		$id = wp_insert_post( array(
			'post_type' => 'post', 'post_status' => $status, 'post_name' => $item['slug'],
			'post_title' => $item['title'], 'post_excerpt' => $item['excerpt'], 'post_content' => $content,
			'post_date' => $item['date'], 'post_date_gmt' => get_gmt_from_date( $item['date'] ),
			'post_category' => array( $category_id ), 'comment_status' => 'closed',
			'meta_input' => array( 'agarwal_news_url' => $item['url'] ),
		), true );
		if ( is_wp_error( $id ) ) { return $id; }
	}
	if ( $make_home && 'publish' === get_post_status( $pages['home'] ) ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $pages['home'] );
	}
	return $pages;
}

function agarwal_setup_screen() {
	if ( ! current_user_can( 'manage_options' ) ) { return; }
	$result = null;
	if ( isset( $_POST['agarwal_setup'] ) ) {
		check_admin_referer( 'agarwal_setup' );
		$result = agarwal_create_starter_content( ! empty( $_POST['publish_starters'] ), ! empty( $_POST['make_home'] ) );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Agarwal theme setup', 'agarwal-group' ); ?></h1>
		<p><?php esc_html_e( 'Create the starting pages, import the bundled images into Media, and add the three existing news entries. Existing pages and posts are left unchanged.', 'agarwal-group' ); ?></p>
		<?php if ( is_wp_error( $result ) ) : ?>
			<div class="notice notice-error"><p><?php echo esc_html( $result->get_error_message() ); ?></p></div>
		<?php elseif ( is_array( $result ) ) : ?>
			<div class="notice notice-success"><p><?php esc_html_e( 'Starter content is ready. Review each page and its publication references in the editor.', 'agarwal-group' ); ?></p></div>
		<?php endif; ?>
		<table class="widefat striped" style="max-width:850px;margin:24px 0"><thead><tr><th>Page</th><th>Current status</th><th>Action</th></tr></thead><tbody>
		<?php foreach ( agarwal_starter_pages() as $slug => $title ) : $page = get_page_by_path( $slug, OBJECT, 'page' ); ?>
			<tr><td><?php echo esc_html( $title ); ?></td><td><?php echo $page ? esc_html( $page->post_status . ' — preserved' ) : esc_html__( 'Not created yet', 'agarwal-group' ); ?></td><td><?php if ( $page ) : ?><a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>">Edit page</a><?php else : ?>Will create<?php endif; ?></td></tr>
		<?php endforeach; ?>
		</tbody></table>
		<form method="post">
			<?php wp_nonce_field( 'agarwal_setup' ); ?>
			<p><label><input type="checkbox" name="publish_starters" value="1"> <?php esc_html_e( 'Publish newly created starter pages and news. Otherwise, create drafts for review.', 'agarwal-group' ); ?></label></p>
			<p><label><input type="checkbox" name="make_home" value="1"> <?php esc_html_e( 'Use the published Home page as the website front page.', 'agarwal-group' ); ?></label></p>
			<?php submit_button( __( 'Create missing starter content', 'agarwal-group' ), 'primary', 'agarwal_setup' ); ?>
		</form>
		<p><?php esc_html_e( 'Research descriptions, the PI biography, six student biographies/photos, and alumni entries still contain the existing placeholders. Review these before publishing.', 'agarwal-group' ); ?></p>
		<p><?php esc_html_e( 'To change the front page later, use Settings → Reading. To edit the menu and header/footer, use Appearance → Editor. Repeating setup does not overwrite existing content or duplicate imported images.', 'agarwal-group' ); ?></p>
	</div>
	<?php
}
