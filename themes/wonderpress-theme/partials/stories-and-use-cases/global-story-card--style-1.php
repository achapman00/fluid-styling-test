<?php
/**
 * A reusable template for a vertical story card
 *
 * @package Wonderpress Theme
 */

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
?>

<article class="global-story__card global-story__card--style-1">
	<div class="global-story__image">
		<?php if ( has_post_thumbnail( $story_obj ) ) { ?>
			<?php
			// Image Template
			wonder_include_template_file(
				'partials/image.php',
				array(
					'src' => get_the_post_thumbnail_url( $story_obj, 'large' ),
					'alt' => get_the_title( $story_obj ),
				)
			);
			?>
		<?php } ?>
	</div>
	<div class="global-story__category">
		<?php
		$category = get_the_category( $story_obj );
		echo esc_html( $category[0]->cat_name );
		?>
	</div>

	<h3 class="global-story__title"><?php echo esc_html( get_the_title( $story_obj ) ); ?></h3>
	<p class="global-story__p"><?php echo esc_html( get_the_excerpt( $story_obj ) ); ?></p>
	<?php
	$eid = wonder_create_eid_string(
		$eid_page_slug,
		$eid_section_slug,
		get_the_title( $story_obj ),
		'click'
	);
	?>
	<a class="global-story__hotspot" href="<?php echo esc_url( get_permalink( $story_obj ) ); ?>" data-eid="<?php echo esc_attr( $eid ); ?>">
		<span class="global-screen-reader-copy">Read the article entitled “<?php echo esc_html( get_the_title( $story_obj ) ); ?>”</span>
	</a>
</article>

