<?php
/**
 * Title: Research starting layout
 * Slug: agarwal/research
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
<h1 class="wp-block-heading">Research</h1>
<!-- /wp:heading -->
<!-- wp:paragraph -->
<p>Our group combines materials synthesis, optical measurements, microscopy, and device experiments to study low-dimensional materials and their response to light and electric fields.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:group {"className":"research-layout","layout":{"type":"default"}} -->
<div class="wp-block-group research-layout">
<!-- wp:group {"tagName":"nav","className":"research-nav","layout":{"type":"default"}} -->
<nav class="wp-block-group research-nav">
<!-- wp:paragraph -->
<p><a href="#quantum-materials" class="active" aria-current="location">Quantum materials and topology</a></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><a href="#nanophotonics">Nanophotonics and structured light</a></p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p><a href="#phase-change">Phase-change materials</a></p>
<!-- /wp:paragraph -->
</nav>
<!-- /wp:group -->
<!-- wp:group {"className":"research-content","layout":{"type":"default"}} -->
<div class="wp-block-group research-content">
<!-- wp:group {"tagName":"section","anchor":"quantum-materials","className":"research-area","layout":{"type":"default"}} -->
<section id="quantum-materials" class="wp-block-group research-area">
<!-- wp:heading {"level":2,"anchor":"quantum-materials-title"} -->
<h2 id="quantum-materials-title" class="wp-block-heading">Quantum materials and topology</h2>
<!-- /wp:heading -->
<!-- wp:group {"className":"research-description","layout":{"type":"default"}} -->
<div class="wp-block-group research-description">
<?php echo agarwal_starter_image( 'quantum.webp', 'MoTe2 structures and photocurrent plots', 'MoTe₂ structures and photocurrent measurements. Fig. 1, Chen et al., <a href="https://arxiv.org/html/2609.38102v1" target="_blank" rel="noopener">2026 preprint</a>.', 'research-figure' ); ?>
<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec sed odio dui, et commodo cursus magna. Maecenas faucibus mollis interdum. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam quis risus eget urna mollis ornare vel eu leo. Aenean lacinia bibendum nulla sed consectetur. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Etiam porta sem malesuada mollis euismod. Fusce dapibus, tellus ac cursus commodo, tortor condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nulla vitae elit libero, a pharetra augue. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec id elit non mi porta gravida at eget metus.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Selected papers</h3>
<!-- /wp:heading -->
<!-- wp:group {"className":"research-papers","layout":{"type":"default"}} -->
<div class="wp-block-group research-papers">
<!-- wp:agarwal/paper {"paperId":"chen2026photogalvanic","compact":true} /-->
<!-- wp:agarwal/paper {"paperId":"shi2026quantummetric","compact":true} /-->
<!-- wp:agarwal/paper {"paperId":"khandelwal2025chiralresponse","compact":true} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
<!-- wp:group {"tagName":"section","anchor":"nanophotonics","className":"research-area","layout":{"type":"default"}} -->
<section id="nanophotonics" class="wp-block-group research-area">
<!-- wp:heading {"level":2,"anchor":"nanophotonics-title"} -->
<h2 id="nanophotonics-title" class="wp-block-heading">Nanophotonics and structured light</h2>
<!-- /wp:heading -->
<!-- wp:group {"className":"research-description","layout":{"type":"default"}} -->
<div class="wp-block-group research-description">
<?php echo agarwal_starter_image( 'structured-light.webp', 'Illustration of five optical vortex modes', 'Optical vortex modes. <a href="https://www.engineering.upenn.edu/stories/penn-engineers-develop-first-tunable-chip-based-vortex-microlaser-and-detector-encode-e490e7983997/" target="_blank" rel="noopener">Penn Engineering, 2020</a>.', 'research-figure' ); ?>
<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec sed odio dui, et commodo cursus magna. Maecenas faucibus mollis interdum. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam quis risus eget urna mollis ornare vel eu leo. Aenean lacinia bibendum nulla sed consectetur. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Etiam porta sem malesuada mollis euismod. Fusce dapibus, tellus ac cursus commodo, tortor condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nulla vitae elit libero, a pharetra augue. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec id elit non mi porta gravida at eget metus.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Selected papers</h3>
<!-- /wp:heading -->
<!-- wp:group {"className":"research-papers","layout":{"type":"default"}} -->
<div class="wp-block-group research-papers">
<!-- wp:agarwal/paper {"paperId":"wang2026flatbands","compact":true} /-->
<!-- wp:agarwal/paper {"paperId":"zhang2022microlaser","compact":true} /-->
<!-- wp:agarwal/paper {"paperId":"ji2020orbitalangularmomentum","compact":true} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
<!-- wp:group {"tagName":"section","anchor":"phase-change","className":"research-area","layout":{"type":"default"}} -->
<section id="phase-change" class="wp-block-group research-area">
<!-- wp:heading {"level":2,"anchor":"phase-change-title"} -->
<h2 id="phase-change-title" class="wp-block-heading">Phase-change materials</h2>
<!-- /wp:heading -->
<!-- wp:group {"className":"research-description","layout":{"type":"default"}} -->
<div class="wp-block-group research-description">
<?php echo agarwal_starter_image( 'phase.webp', 'Illustration of electrically driven amorphization in indium selenide', 'Phase-change illustration by Akanksha Jain, <a href="https://www.engineering.upenn.edu/stories/breakthrough-in-energy-efficient-avalanche-based-amorphization-could-revolutionize-data-storage/" target="_blank" rel="noopener">Penn Engineering, 2024</a>.', 'research-figure phase-research-image' ); ?>
<!-- wp:paragraph -->
<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Donec sed odio dui, et commodo cursus magna. Maecenas faucibus mollis interdum. Vestibulum id ligula porta felis euismod semper. Praesent commodo cursus magna, vel scelerisque nisl consectetur et. Nullam quis risus eget urna mollis ornare vel eu leo. Aenean lacinia bibendum nulla sed consectetur. Sed posuere consectetur est at lobortis. Cras mattis consectetur purus sit amet fermentum.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph -->
<p>Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Etiam porta sem malesuada mollis euismod. Fusce dapibus, tellus ac cursus commodo, tortor condimentum nibh, ut fermentum massa justo sit amet risus. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Nulla vitae elit libero, a pharetra augue. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Donec id elit non mi porta gravida at eget metus.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Selected papers</h3>
<!-- /wp:heading -->
<!-- wp:group {"className":"research-papers","layout":{"type":"default"}} -->
<div class="wp-block-group research-papers">
<!-- wp:agarwal/paper {"paperId":"modi2024amorphization","compact":true} /-->
<!-- wp:agarwal/paper {"paperId":"phasechange2011nanowires","compact":true} /-->
</div>
<!-- /wp:group -->
</section>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
