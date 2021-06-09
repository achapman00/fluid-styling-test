<?php
/**
 * A reusable template for a featured small dual bar chart
 *
 * @package Wonderpress Theme
 */

// Allow for the passing in of a reusable acf component
$acf = ( isset( $acf ) && is_array( $acf ) ) ? $acf : null;

// Grab the left bar
$left_bar = isset( $left_bar ) ? $left_bar : null;
if ( ! $left_bar && $acf ) {
	$left_bar = ( isset( $acf['left_bar'] ) ) ? $acf['left_bar'] : $left_bar;
	$left_height = ( isset( $left_bar['height_percentage'] ) ? intval( $left_bar['height_percentage'] ) : 0 );
}

// Grab the right bar
$right_bar = isset( $right_bar ) ? $right_bar : null;
if ( ! $right_bar && $acf ) {
	$right_bar = ( isset( $acf['right_bar'] ) ) ? $acf['right_bar'] : $right_bar;
	$right_height = ( isset( $right_bar['height_percentage'] ) ? intval( $right_bar['height_percentage'] ) : 0 );
}
?>
<div class="global-small-dual-bar-chart">
	<div class="global-small-dual-bar-chart__background"></div>
	<?php if ( $left_bar ) { ?>
		<div class="global-small-dual-bar-chart__bar"
			 style="height: <?php echo esc_attr( $left_height ); ?>%"
			 aria-valuenow="<?php echo esc_attr( $left_height ); ?>"
			 aria-valuemin="0"
			 aria-valuemax="100">

			<?php if ( isset( $left_bar['number_above_bar'] ) && '' !== $left_bar['number_above_bar'] ) { ?>
				<span class="global-small-dual-bar-chart__above-number"><?php echo esc_html( $left_bar['number_above_bar'] ); ?></span>
			<?php } ?>

			<?php if ( isset( $left_bar['number_inside_bar'] ) && '' !== $left_bar['number_inside_bar'] ) { ?>
				<span class="global-small-dual-bar-chart__inside-number"><?php echo esc_html( $left_bar['number_inside_bar'] ); ?></span>
			<?php } ?>

			<?php if ( isset( $left_bar['label_inside_bar'] ) && '' !== $left_bar['label_inside_bar'] ) { ?>
				<span class="global-small-dual-bar-chart__inside-label"><?php echo esc_html( $left_bar['label_inside_bar'] ); ?></span>
			<?php } ?>
		</div>
	<?php } ?>

	<?php if ( $right_bar ) { ?>
		<div class="global-small-dual-bar-chart__bar"
			 style="height: <?php echo esc_attr( $right_height ); ?>%"
			 aria-valuenow="<?php echo esc_attr( $right_height ); ?>"
			 aria-valuemin="0"
			 aria-valuemax="100">

			<?php if ( isset( $right_bar['number_above_bar'] ) && '' !== $right_bar['number_above_bar'] ) { ?>
				<span class="global-small-dual-bar-chart__above-number"><?php echo esc_html( $right_bar['number_above_bar'] ); ?></span>
			<?php } ?>

			<?php if ( isset( $right_bar['number_inside_bar'] ) && '' !== $right_bar['number_inside_bar'] ) { ?>
				<span class="global-small-dual-bar-chart__inside-number"><?php echo esc_html( $right_bar['number_inside_bar'] ); ?></span>
			<?php } ?>

			<?php if ( isset( $right_bar['label_inside_bar'] ) && '' !== $right_bar['label_inside_bar'] ) { ?>
				<span class="global-small-dual-bar-chart__inside-label"><?php echo esc_html( $right_bar['label_inside_bar'] ); ?></span>
			<?php } ?>
		</div>
	<?php } ?>
</div>
