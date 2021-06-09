<?php
/**
 * The tag page template.
 *
 * @package Wonderpress Theme
 */

// Set the <body> id
wonder_body_id( 'tag' );

get_header();
?>

	<main role="main">
		<!-- section -->
		<section>

			<h1>
			<?php
			esc_html_e( 'Tag Archive: ', 'bt' );
			echo single_tag_title( '', false );
			?>
			</h1>

			<?php get_template_part( 'loop' ); ?>

			<?php get_template_part( 'pagination' ); ?>

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
