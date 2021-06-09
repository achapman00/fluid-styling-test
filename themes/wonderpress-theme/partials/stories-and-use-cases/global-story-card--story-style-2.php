<?php
/**
 * A reusable template for an extra grid card of type: explore
 *
 * @package Wonderpress Theme
 */

$story_obj = ( isset( $story_obj ) ) ? $story_obj : null;

$modifiers = array();

if ( isset( $class_modifier ) ) {
	if ( is_array( $class_modifier ) ) {
		foreach ( $class_modifier as $modifier ) {
			$modifiers[] = 'global-story__card--' . $modifier;
		}
	} else {
		$modifiers[] = 'global-story__card--' . $class_modifier;
	}
}

if ( empty( $modifiers ) ) {
	$modifiers[] = 'global-story__card--story-style-2';
}
?>

<div class="global-story__card global-story__card--extra-card <?php echo esc_attr( join( ' ', $modifiers ) ); ?>">
	<?php
	$base_uri = esc_url( content_url() . '/' );
	// Template: Image
	wonder_include_template_file(
		'partials/image.php',
		array(
			'src' => get_the_post_thumbnail_url( $story_obj, 'medium' ),
			'alt' => get_the_title( $story_obj ),
			'class' => 'global-story__img',
		)
	);
	?>
	<h3 class="global-story__h3">
		<?php echo esc_html( get_the_title( $story_obj ) ); ?>
	</h3>
	<p class="global-story__p">
		<?php echo esc_html( get_the_excerpt( $story_obj ) ); ?>
	</p>
	<div class="global-cta-area">
		<?php
		// Template: Button
		wonder_include_template_file(
			'partials/button.php',
			array(
				'text' => 'Read the Story',
				// 'attachment' => 'right_arrow',
												'class' => 'global-cta',
				'url' => get_the_permalink( $story_obj ),
			)
		);
		?>
	</div>
</div>
