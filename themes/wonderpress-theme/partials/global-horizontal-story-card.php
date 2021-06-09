<?php
/**
 * A reusable template for a horizontal story card
 *
 * @package Wonderpress Theme
 */

?>
<?php
// Image Template
// wonder_include_template_file(
// 'partials/image.php',
// array(
// 'src' => get_the_post_thumbnail_url( $story_obj, 'large' ),
// 'alt' => get_the_title( $story_obj ),
// 'srcset' => get_all_post_thumbnail_urls( $story_obj ),
// )
// );
//

$category = get_the_category( $story_obj );
?>
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
<div class=""><?php echo esc_html( $category[0]->name ); ?></div>
<h3 class=""><?php echo esc_html( get_the_title( $story_obj ) ); ?></h3>
<p class=""><?php echo esc_html( get_the_excerpt( $story_obj ) ); ?></p>
