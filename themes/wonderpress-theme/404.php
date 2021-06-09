<?php
/**
 * The 404 page template.
 *
 * @package Wonderpress Theme
 */

// Set the <body> id
wonder_body_id( 'http-status-404' );

get_header();
?>

<main class="global-main" role="main">
	<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
	<section id="hero" class="global-http-status__section global-http-status__section--hero">
		<div class="global-http-status__grid">
			<h1 class="global-http-status__h1">Oops! Page not found.</h1>
			<h2 class="global-http-status__h2">We can’t seem to find what you’re looking for.<br>Let’s keep you moving.
			</h2>
			<div class="global-cta-area">
				<?php
				echo wonder_nav( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'http-status-menu',
					array(
						'container' => false,
						'theme_location' => 'http-status-menu',
						'menu_class' => 'global-http-status__ul',
						'list_item_classes' => 'global-http-status__li',
						'anchor_classes' => 'global-cta global-cta--http-status',
						'link_before' => '<span>',
						'link_after' => '</span>',
						'attachment' => 'right_arrow',
					)
				);
				?>
			</div>
		</div>
	</section>
</main>

<?php /*get_sidebar(); */ ?>
<?php get_footer(); ?>
