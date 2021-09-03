<?php
/**
 * A reusable template for a link
 *
 * @package Wonderpress Theme
 */

/**
TEMPLATE USAGE:

If explicitly passing each param (and NOT using ACF):

	wonder_include_template_file(
		'partials/link.php',
		array(
			'accessibility_title' => <string>,
			'attachment' => <string:'right_arrow'>,
			'attributes' => <associative_array>,
			'class' => <string|array>,
			'link_type' => <string:'email'>,
			'open_in_new_tab' => <boolean>,
			'text' => <string>,
			'url' => <string>,
		)
	);

If displaying an ACF component (this is the preferred way for most
cases, because all values are predictable):

	wonder_include_template_file(
		'partials/link.php',
		array(
			'acf' => get_field('acf_component-name'),
		)
	);
 **/

/**
 * Get the url
 */
$url = ( isset( $url ) ) ? $url : null;
if ( ! $url && isset( $acf ) ) {
	$url = ( isset( $acf['url'] ) ) ? $acf['url'] : $url;
}

/**
 * If the type is "email", then prepend mailto:
 */
if (
	( isset( $link_type ) && 'email' == $link_type ) ||
	( isset( $acf ) && $acf && isset( $acf['link_type'] ) && 'email' == $acf['link_type'] )
) {
	$url = 'mailto:' . $url;
}

/**
 * Check to see if the $url is a Page ID
 */
if ( is_numeric( $url ) ) {
	$url = get_permalink( $url );
}


/**
 * Determine class attribute structure
 * If no class is provided (either explicity or via ACF), then
 * default to .global-button.global-button--{size}
 */
$class = ( isset( $class ) ) ? $class : null;
if ( ! $class && isset( $acf ) ) {
	$class = ( isset( $acf['class'] ) ) ? $acf['class'] : $class;
}
$class = ( $class ) ? $class : 'global-button' . ( isset( $size ) ? ' global-button--' . $size : '' );
$class = ( is_array( $class ) ) ? implode( ' ', $class ) : $class;


/**
 * Set arbitrary attributes for the button (such as data attributes).
 *
 * Note: this is not currently available in the CMS.
 */
$attributes = ( isset( $attributes ) && is_array( $attributes ) ) ? $attributes : array();




/**
 * Set the button attachment. This will be an image or character
 * that is prepended or appended to the text inside the button.
 *
 * This should be a string, such as 'right_arrow'.
 */
$attachment = isset( $attachment ) ? $attachment : null;
if ( ! $attachment && isset( $acf ) && $acf ) {
	$attachment = ( isset( $acf['attachment'] ) ) ? $acf['attachment'] : $attachment;
}


/**
 * Determine whether or not this link should open in a new tab
 */
$open_in_new_tab = ( isset( $open_in_new_tab ) ) ? (bool) $open_in_new_tab : false;
if ( ! $open_in_new_tab && isset( $acf ) && $acf ) {
	$open_in_new_tab = ( isset( $acf['open_in_new_tab'] ) ) ? $acf['open_in_new_tab'] : $open_in_new_tab;
}



/**
 * Get the text to display in this link
 */
$text = ( isset( $text ) ) ? $text : null;
if ( ! $text && $acf ) {
	$text = ( isset( $acf['link_text'] ) ) ? $acf['link_text'] : $text;
}

/**
 * Get the title to use for this link
 */
$accessibility_title = ( isset( $accessibility_title ) ) ? $accessibility_title : null;
if ( ! $accessibility_title && isset( $acf ) && $acf ) {
	$accessibility_title = ( isset( $acf['accessibility_title'] ) ) ? $acf['accessibility_title'] : $text;
}
$accessibility_title = ( $accessibility_title ) ? $accessibility_title : $text;

/**
 * Concatenate all text and attachments into the content
 * for this link.
 */
$content = '<span alt="global-link__span">' . $text . '</span>';
if ( $attachment ) {
	switch ( $attachment ) {
		case 'download':
			$content .= '<img alt="download icon" class="download" src="' . get_template_directory_uri() . '/assets/imgs/global/home_download_d.svg">';
			break;
		case 'download-white':
			$content .= '<img alt="download icon" class="download" src="' . get_template_directory_uri() . '/assets/imgs/global/download-white.svg">';
			break;
		case 'right_arrow':
			$content .= '<img alt="right arrow" class="right-arrow" src="' . get_template_directory_uri() . '/assets/imgs/global/godaddy-venture-cta_arrow_21x12_00a4a6-on-trans.svg">';
			break;
		case 'download-ani':
			$content = $text . '<span title="download icon with animation when selected" class="download-ani"></span>';
			break;
		case 'lottie':
			$content = $text . '<div id="' . ( isset( $lottie_id ) ? $lottie_id : md5( uniqid() ) ) . '" class="lottie-container"></div>';
			break;
		default:
			$content = $content;
			break;
	}
}

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
$eid_widget_slug = isset( $eid_widget_slug ) ? $eid_widget_slug : null;
if ( ! $eid_widget_slug && isset( $acf ) && $acf ) {
	$eid_widget_slug = ( isset( $acf['eid_widget_slug'] ) ) ? $acf['eid_widget_slug'] : $eid_widget_slug;
}
if ( ! $eid_widget_slug ) {
	$eid_widget_slug = $text;
}

$eid = wonder_create_eid_string(
	$eid_page_slug,
	$eid_section_slug,
	$eid_widget_slug,
	'click'
);
?>
<a href="<?php echo esc_url( $url ); ?>"
	<?php
	if ( $class ) {
		?>
		 class="<?php echo esc_attr( $class ); ?>"<?php } ?>
	<?php
	if ( $accessibility_title ) {
		?>
		 aria-label="<?php echo esc_attr( trim( strip_tags( $accessibility_title ) ) ); ?>"<?php } ?>
	<?php
	if ( $accessibility_title ) {
		?>
		 title="<?php echo esc_attr( trim( strip_tags( $accessibility_title ) ) ); ?>"<?php } ?>
	<?php
	if ( $eid ) {
		?>
		 data-eid="<?php echo esc_attr( $eid ); ?>"<?php } ?>
	<?php if ( $open_in_new_tab ) { ?>
		target="_blank"
		rel="noopener"
	<?php } ?>
	<?php foreach ( $attributes as $attribute => $value ) { ?>
		<?php echo esc_html( $attribute ); ?>="<?php echo esc_attr( $value ); ?>"
	<?php } ?>
   role="link"
>
	<?php
	echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	?>
</a>
