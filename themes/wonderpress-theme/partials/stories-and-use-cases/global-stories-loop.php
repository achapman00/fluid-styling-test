<?php
/**
 * A reusable WordPress Loop for Stories
 *
 * @package Wonderpress Theme
 */

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;

// Grab all the cards to intersperse
// into the loop grid
$extra_grid_cards = array();
while ( have_rows( 'stories_extra_grid_cards', 'option' ) ) {
	the_row();

	$extra_grid_cards[] = array(
		'slot' => get_sub_field( 'grid_slot' ),
		'type' => get_sub_field( 'type' ),
		'style' => get_sub_field( 'style' ),
		'image' => get_sub_field( 'image' ),
		'icon' => get_sub_field( 'icon' ),
		'preheadline' => get_sub_field( 'preheadline' ),
		'headline' => get_sub_field( 'headline' ),
		'paragraph' => get_sub_field( 'paragraph' ),
		'button' => get_sub_field( 'button' ),
		'eid_page_slug' => $eid_page_slug,
		'eid_section_slug' => $eid_section_slug,
	);
}

/**
 * A function to show an extra card and increment a counter
 *
 * @param Int   $c The counter.
 * @param Array $extra_grid_cards An array of available grid cards.
 **/
function try_to_show_extra_card( $c, $extra_grid_cards ) {
	// Check to see if there are any extra
	// cards intended for this current slot
	$extra_cards_to_display_now = array_filter(
		$extra_grid_cards,
		function ( $n ) use ( $c ) {
			return $n['slot'] == $c;
		}
	);

	foreach ( $extra_cards_to_display_now as $extra_grid_card ) {
		switch ( $extra_grid_card['type'] ) {
			case 'grid-item':
				// Template: Extra Story Card
				wonder_include_template_file(
					'partials/stories-and-use-cases/global-story-card--extra.php',
					array(
						'style' => $extra_grid_card['style'],
						'icon' => $extra_grid_card['icon'],
						'image' => $extra_grid_card['image'],
						'preheadline' => $extra_grid_card['preheadline'],
						'headline' => $extra_grid_card['headline'],
						'paragraph' => $extra_grid_card['paragraph'],
						'button' => $extra_grid_card['button'],
						'button_attachment' => 'download',
						'eid_page_slug' => $extra_grid_card['eid_page_slug'],
						'eid_section_slug' => $extra_grid_card['eid_section_slug'],
					)
				);
				break;
			case 'download-report':
				// Template: Download the Data CTA
				wonder_include_template_file(
					'partials/stories-and-use-cases/global-story-card--download-the-report.php',
					array()
				);
				break;
			case 'explore-data':
				// Template: Explore the Data
				wonder_include_template_file(
					'partials/stories-and-use-cases/global-story-card--explore-the-data.php',
					array()
				);
				break;
		}
	}

	if ( $extra_cards_to_display_now ) {
		$c++;
	}

	return $c;
}

?>

<?php
if ( have_posts() ) :

	?>
	<div class="global-story__grid">
		<?php

		// Loop through the posts
		$c = 1;
		while ( have_posts() ) :
			the_post();

			$c_compare = null;
			while ( $c != $c_compare ) {
				$c_compare = $c;
				$c = try_to_show_extra_card( $c, $extra_grid_cards );
			}

			// if ( ! is_sticky() ) {

				// Template: global-story-card--style-1
				wonder_include_template_file(
					'partials/stories-and-use-cases/global-story-card--style-1.php',
					array(
						'story_obj' => get_post(),
						'eid_page_slug' => $eid_page_slug,
						'eid_section_slug' => $eid_section_slug,
					)
				);
				$c++;
			// }
			?>

			<?php

		endwhile;
		?>
	</div>

<?php else : ?>

	<!-- article -->
	<article>
		<h2>
			<?php esc_html_e( 'Sorry, nothing to display.', 'bt' ); ?>
		</h2>
	</article>
	<!-- /article -->

<?php endif; ?>
