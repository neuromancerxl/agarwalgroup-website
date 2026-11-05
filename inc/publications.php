<?php
/** Publication records remain in JSON during the first WordPress release. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function agarwal_publication_catalog() {
	static $catalog;
	if ( null !== $catalog ) {
		return $catalog;
	}
	$file = get_theme_file_path( 'pubbase.json' );
	$catalog = is_readable( $file ) ? json_decode( file_get_contents( $file ), true ) : null;
	if ( ! is_array( $catalog ) || 1 !== ( $catalog['schemaVersion'] ?? null ) || ! isset( $catalog['papers'], $catalog['topics'] ) || ! is_array( $catalog['papers'] ) || ! is_array( $catalog['topics'] ) ) {
		$catalog = new WP_Error( 'agarwal_catalog', __( 'The publication catalog is temporarily unavailable.', 'agarwal-group' ) );
		return $catalog;
	}
	$topic_ids = array();
	foreach ( $catalog['topics'] as $topic ) {
		if ( empty( $topic['id'] ) || ! is_string( $topic['id'] ) || 'all' === $topic['id'] || empty( $topic['label'] ) || ! is_string( $topic['label'] ) || in_array( $topic['id'], $topic_ids, true ) ) {
			$catalog = new WP_Error( 'agarwal_topics', __( 'The publication topics are temporarily unavailable.', 'agarwal-group' ) );
			return $catalog;
		}
		$topic_ids[] = $topic['id'];
	}
	$paper_ids = array();
	foreach ( $catalog['papers'] as $paper ) {
		$valid = is_array( $paper ) && isset( $paper['id'], $paper['title'], $paper['journal'], $paper['year'], $paper['authors'], $paper['topics'], $paper['status'] );
		if ( $valid ) {
			$valid = is_string( $paper['id'] ) && '' !== $paper['id'] && ! in_array( $paper['id'], $paper_ids, true ) &&
				is_string( $paper['title'] ) && is_string( $paper['journal'] ) && is_int( $paper['year'] ) &&
				is_array( $paper['authors'] ) && is_array( $paper['topics'] ) && in_array( $paper['status'], array( 'published', 'preprint' ), true );
		}
		if ( $valid ) {
			foreach ( $paper['authors'] as $author ) { if ( ! is_string( $author ) || '' === trim( $author ) ) { $valid = false; } }
			foreach ( $paper['topics'] as $topic ) { if ( ! in_array( $topic, $topic_ids, true ) ) { $valid = false; } }
			$doi = isset( $paper['doi'] ) && is_string( $paper['doi'] ) ? $paper['doi'] : '';
			$url = $paper['url'] ?? ( $doi ? 'https://doi.org/' . $doi : '' );
			if ( isset( $paper['authorNote'] ) && ! is_string( $paper['authorNote'] ) ) { $valid = false; }
			$valid = $valid && is_string( $url ) && preg_match( '~^https?://~i', $url );
		}
		if ( ! $valid ) {
			$catalog = new WP_Error( 'agarwal_papers', __( 'The publication records are temporarily unavailable.', 'agarwal-group' ) );
			return $catalog;
		}
		$paper_ids[] = $paper['id'];
	}
	return $catalog;
}

function agarwal_author_initials( $name ) {
	$parts = preg_split( '/\s+/u', trim( $name ) );
	$surname = array_pop( $parts );
	$particles = array( 'van', 'von', 'de', 'der', 'den', 'del', 'da', 'di', 'dos', 'la', 'le' );
	while ( count( $parts ) > 1 && in_array( strtolower( end( $parts ) ), $particles, true ) ) {
		$surname = array_pop( $parts ) . ' ' . $surname;
	}
	$initials = array();
	foreach ( $parts as $part ) {
		$hyphenated = array();
		foreach ( explode( '-', $part ) as $piece ) {
			preg_match( '/^./u', $piece, $first );
			$hyphenated[] = ( $first[0] ?? '' ) . '.';
		}
		$initials[] = implode( '-', $hyphenated );
	}
	return $initials ? implode( ' ', $initials ) . ' ' . $surname : $surname;
}

function agarwal_paper_authors( $paper ) {
	if ( empty( $paper['authors'] ) ) {
		return $paper['authorNote'] ?? '';
	}
	$others = array_values( array_filter( $paper['authors'], function ( $name ) { return 'Ritesh Agarwal' !== $name; } ) );
	$names = implode( ', ', array_map( 'agarwal_author_initials', array_slice( $others, 0, 3 ) ) );
	if ( $names && ( count( $others ) > 3 || ! empty( $paper['authorsIncomplete'] ) ) ) {
		$names .= ' et al.';
	}
	if ( in_array( 'Ritesh Agarwal', $paper['authors'], true ) ) {
		$names .= ( $names ? ' · ' : '' ) . 'R. Agarwal';
	}
	return $names;
}

function agarwal_paper_markup( $paper, $compact = false ) {
	$url = $paper['url'] ?? ( empty( $paper['doi'] ) ? '' : 'https://doi.org/' . $paper['doi'] );
	$link = '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener">' . esc_html( $paper['title'] ) . '</a>';
	$preprint = 'preprint' === $paper['status'];
	if ( $compact ) {
		return '<div class="paper-link">' . $link . '<span>' . esc_html( $paper['journal'] . ' · ' . $paper['year'] . ( $preprint ? ' · Preprint' : '' ) ) . '</span></div>';
	}
	return '<article class="paper-row" data-paper-id="' . esc_attr( $paper['id'] ) . '">' .
		'<span class="paper-year">' . esc_html( $paper['year'] ) . '</span>' .
		'<div><h3>' . $link . '</h3><p>' . esc_html( agarwal_paper_authors( $paper ) ) . '</p></div>' .
		'<div class="paper-journal">' . esc_html( $paper['journal'] ) . ( $preprint ? '<span>Preprint</span>' : '' ) . '</div></article>';
}

function agarwal_render_paper_block( $attributes ) {
	$catalog = agarwal_publication_catalog();
	if ( is_wp_error( $catalog ) ) {
		return '<p class="publication-message">' . esc_html( $catalog->get_error_message() ) . '</p>';
	}
	foreach ( $catalog['papers'] as $paper ) {
		if ( $paper['id'] === ( $attributes['paperId'] ?? '' ) ) {
			return '<div ' . get_block_wrapper_attributes() . '>' . agarwal_paper_markup( $paper, ! empty( $attributes['compact'] ) ) . '</div>';
		}
	}
	return '<p class="publication-message">' . esc_html__( 'Choose a publication in the block settings.', 'agarwal-group' ) . '</p>';
}

function agarwal_publication_query( $key, $fallback = '' ) {
	return isset( $_GET[ $key ] ) && is_string( $_GET[ $key ] ) ? sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) : $fallback;
}

function agarwal_publication_page_numbers( $current, $total ) {
	if ( $total <= 7 ) {
		return range( 1, max( 1, $total ) );
	}
	$numbers = array( 1, $total, $current );
	for ( $page = max( 1, $current - 1 ); $page <= min( $total, $current + 1 ); $page++ ) {
		$numbers[] = $page;
	}
	if ( $current <= 3 ) { $numbers = array_merge( $numbers, array( 2, 3 ) ); }
	if ( $current >= $total - 2 ) { $numbers = array_merge( $numbers, array( $total - 2, $total - 1 ) ); }
	$numbers = array_unique( $numbers );
	sort( $numbers );
	$result = array();
	$previous = 0;
	foreach ( $numbers as $number ) {
		if ( $previous && 2 === $number - $previous ) { $result[] = $previous + 1; }
		elseif ( $previous && $number - $previous > 2 ) { $result[] = '…'; }
		$result[] = $number;
		$previous = $number;
	}
	return $result;
}

function agarwal_render_publications_block() {
	$catalog = agarwal_publication_catalog();
	if ( is_wp_error( $catalog ) ) {
		return '<p class="publication-message">' . esc_html( $catalog->get_error_message() ) . '</p>';
	}
	$search = agarwal_publication_query( 'ag_search' );
	$topic = agarwal_publication_query( 'ag_topic', 'all' );
	$year = agarwal_publication_query( 'ag_year', 'all' );
	$topic_ids = array_column( $catalog['topics'], 'id' );
	if ( ! in_array( $topic, $topic_ids, true ) ) { $topic = 'all'; }
	$papers = array_values( array_filter( $catalog['papers'], function ( $paper ) use ( $search, $topic, $year ) {
		$haystack = implode( ' ', array_merge( array( $paper['title'], $paper['journal'], $paper['year'], agarwal_paper_authors( $paper ) ), $paper['authors'] ) );
		return ( 'all' === $topic || in_array( $topic, $paper['topics'], true ) ) &&
			( 'all' === $year || (string) $paper['year'] === $year ) &&
			( '' === $search || false !== stripos( $haystack, $search ) );
	} ) );
	$order = array_flip( array_column( $catalog['papers'], 'id' ) );
	usort( $papers, function ( $a, $b ) use ( $order ) {
		return ( $b['year'] <=> $a['year'] ) ?: ( $order[ $a['id'] ] <=> $order[ $b['id'] ] );
	} );
	$total_pages = (int) ceil( count( $papers ) / 10 );
	$current = max( 1, min( max( 1, $total_pages ), absint( agarwal_publication_query( 'ag_page', '1' ) ) ) );
	$visible = array_slice( $papers, ( $current - 1 ) * 10, 10 );
	$years = array_unique( array_column( $catalog['papers'], 'year' ) );
	rsort( $years );
	$base = get_permalink();
	$params = array( 'ag_search' => $search, 'ag_topic' => $topic, 'ag_year' => $year );
	wp_enqueue_script( 'agarwal-publications', get_theme_file_uri( 'publications.js' ), array(), agarwal_asset_version( 'publications.js' ), true );
	wp_add_inline_script( 'agarwal-publications', 'window.agarwalPublications = ' . wp_json_encode( array(
		'catalogUrl' => get_theme_file_uri( 'pubbase.json' ), 'topic' => $topic, 'page' => $current,
	) ) . ';', 'before' );

	ob_start();
	?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<div class="publication-topics" role="group" aria-label="<?php esc_attr_e( 'Filter publications by research topic', 'agarwal-group' ); ?>">
			<?php foreach ( array_merge( array( array( 'id' => 'all', 'label' => __( 'All', 'agarwal-group' ) ) ), $catalog['topics'] ) as $item ) : ?>
				<a class="<?php echo $topic === $item['id'] ? 'active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array_merge( $params, array( 'ag_topic' => $item['id'] ) ), $base ) ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
			<?php endforeach; ?>
		</div>
		<form class="publication-tools" method="get" action="<?php echo esc_url( $base ); ?>">
			<?php if ( ! get_option( 'permalink_structure' ) ) : ?><input type="hidden" name="page_id" value="<?php echo absint( get_the_ID() ); ?>"><?php endif; ?>
			<input type="hidden" name="ag_topic" value="<?php echo esc_attr( $topic ); ?>">
			<label for="paper-search"><?php esc_html_e( 'Search papers', 'agarwal-group' ); ?><input id="paper-search" name="ag_search" type="search" placeholder="Title, author, or journal" value="<?php echo esc_attr( $search ); ?>"></label>
			<label for="year-filter"><?php esc_html_e( 'Filter by year', 'agarwal-group' ); ?><select id="year-filter" name="ag_year"><option value="all">All years</option><?php foreach ( $years as $item ) : ?><option value="<?php echo absint( $item ); ?>" <?php selected( $year, (string) $item ); ?>><?php echo esc_html( $item ); ?></option><?php endforeach; ?></select></label>
			<button type="submit" class="publication-submit"><?php esc_html_e( 'Apply filters', 'agarwal-group' ); ?></button>
		</form>
		<div id="publication-list" role="region" aria-label="<?php esc_attr_e( 'Publication results', 'agarwal-group' ); ?>" tabindex="-1" aria-busy="false">
			<?php foreach ( $visible as $paper ) { echo agarwal_paper_markup( $paper ); } ?>
		</div>
		<p id="no-publications" class="publication-empty" role="status" <?php echo $papers ? 'hidden' : ''; ?>><?php esc_html_e( 'No papers match these filters.', 'agarwal-group' ); ?></p>
		<nav id="publication-pagination" class="publication-pagination" aria-label="<?php esc_attr_e( 'Publication pages', 'agarwal-group' ); ?>" <?php echo $total_pages <= 1 ? 'hidden' : ''; ?>>
			<?php
			$links = agarwal_publication_page_numbers( $current, $total_pages );
			if ( $current > 1 ) { array_unshift( $links, array( $current - 1, '← Previous' ) ); }
			if ( $current < $total_pages ) { $links[] = array( $current + 1, 'Next →' ); }
			foreach ( $links as $link ) {
				if ( '…' === $link ) { echo '<span aria-hidden="true">…</span>'; continue; }
				$number = is_array( $link ) ? $link[0] : $link;
				$label = is_array( $link ) ? $link[1] : $link;
				echo '<a href="' . esc_url( add_query_arg( array_merge( $params, array( 'ag_page' => $number ) ), $base ) ) . '"' . ( $current === $number ? ' aria-current="page"' : '' ) . '>' . esc_html( $label ) . '</a>';
			}
			?>
		</nav>
		<p id="publication-status" class="screen-reader-text" role="status"><?php echo esc_html( sprintf( __( '%d matching publications.', 'agarwal-group' ), count( $papers ) ) ); ?></p>
	</div>
	<?php
	return ob_get_clean();
}
