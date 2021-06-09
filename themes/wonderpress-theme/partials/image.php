<?php
/**
 * A reusable template for an image
 *
 * @package Wonderpress Theme
 */

/*
 Check to see if an image object was passed
 * (this is optional)
 */
$acf = ( isset( $acf ) ) ? $acf : null;
$size = ( isset( $size ) ) ? $size : 'large';

/* If a srcset was provided */
$srcset = ( isset( $srcset ) ) ? $srcset : array();
if ( ! $srcset && $acf && isset( $acf['sizes'] ) ) {
	$srcset = $acf['sizes'];
}

/*
 * If no image object was provided, we also
 * accept detailed configs.
 */
$alt = ( isset( $alt ) ) ? $alt : null;
if ( ! $alt && $acf && isset( $acf['alt'] ) ) {
	$alt = $acf['alt'];
}

// If displaying a single source (not a picture)
$src = ( isset( $src ) ) ? $src : null;
if ( ! $src && $acf && isset( $acf['sizes'][ $size ] ) ) {
	$src = $acf['sizes'][ $size ];
}

// If displaying a picture w/ multiple sources
$acf_src_sizes = array(
	'medium' => array(
		'320' => 'medium',
	),
	'large' => array(
		'320' => 'medium',
		'768' => 'large',
	),
);
$srcs = ( isset( $srcs ) && is_array( $srcs ) ) ? $srcs : array();
if ( ! $srcs && $srcset && isset( $acf_src_sizes[ $size ] ) ) {
	foreach ( $acf_src_sizes[ $size ] as $min_breakpoint => $size_name ) {

		if ( isset( $srcset[ $size_name ] ) ) {
			$srcs[ $min_breakpoint ] = $srcset[ $size_name ];
		}
	}
}

/**
 * Set arbitrary attributes for the button (such as data attributes).
 *
 * Note: this is not currently available in the CMS.
 */
$attributes = ( isset( $attributes ) && is_array( $attributes ) ) ? $attributes : array();

if ( $srcs ) {
	?>
<picture>
	<?php foreach ( $srcs as $min => $srcset ) { ?>
	<source media="(min-width:<?php echo esc_attr( $min ); ?>px)" srcset="<?php echo esc_url( $srcset ); ?>">
	<?php } ?>
	<img src="<?php echo esc_url( reset( $srcs ) ); ?>" class="<?php echo esc_attr( ( isset( $class ) ) ? $class : '' ); ?>"
		alt="<?php echo esc_attr( $alt ); ?>" loading="lazy"
		<?php foreach ( $attributes as $attribute => $value ) { ?>
			<?php echo esc_html( $attribute ); ?>="<?php echo esc_attr( $value ); ?>"
		<?php } ?>
		/>
</picture><?php } else { ?>
<img src="<?php echo esc_url( $src ); ?>"
	class="<?php echo esc_attr( ( isset( $class ) ) ? $class : '' ); ?>"
	alt="<?php echo esc_attr( $alt ); ?>" loading="lazy"
	<?php foreach ( $attributes as $attribute => $value ) { ?>
		<?php echo esc_html( $attribute ); ?>="<?php echo esc_attr( $value ); ?>"
	<?php } ?>
	/><?php } ?>
