<?php
/**
 * A reusable template for a button
 *
 * This partial will either display a <button> or use the link partial
 * to display a <a> tag with the correct classes for a button. It will
 * display the <a> tag if a $url value exists.
 *
 * @package Wonderpress Theme
 */

/**
TEMPLATE USAGE:

If explicitly passing each param (and NOT using ACF):

	wonder_include_template_file(
		'partials/button.php',
		array(
			'accessibility_title' => <string>,
			'attachment' => <string:'right_arrow'>,
			'attributes' => <associative_array>,
			'class' => <string|array>,
			'link_type' => <string:'email'>, // this is only passed to partials/link.php
			'open_in_new_tab' => <boolean>, // this is only passed to partials/link.php
			'text' => <string>,
			'size' => <string:'normal|medium|large'>,
			'url' => <string>,
		)
	);

If displaying an ACF component (this is the preferred way for most
cases, because all values are predictable):

	wonder_include_template_file(
		'partials/button.php',
		array(
			'acf' => get_field('acf_component-name'),
		)
	);
 **/

/**
 * Allow for the passing in of a reusable acf component
 * If this value is passed, we will attempt to use all the
 * values from the mapped ACF component in the CMS.
 */
$acf = ( isset( $acf ) && $acf && is_array( $acf ) ) ? $acf : null;

// Get the text value
$text = ( isset( $text ) ) ? $text : null;
if ( ! $text && $acf ) {
	$text = ( isset( $acf['link_text'] ) ) ? $acf['link_text'] : $text;
}

/**
 * Determine size
 * If no value is explicity passed, then use ACF (if exists)
 */
$size = isset( $size ) ? $size : null;
if ( ! $size && isset( $acf['size'] ) ) {
	switch ( $acf['size'] ) {
		case 'large':
			$size = 'large';
			break;
		case 'medium':
			$size = 'medium';
			break;
		default:
			$size = 'normal';
			break;
	}
}

/**
 * Determine class attribute structure
 * If no class is provided (either explicity or via ACF), then
 * default to .global-button.global-button--{size}
 *
 * Note: this is not currently available in the CMS.
 */
$class = ( isset( $class ) ) ? $class : null;
$class = ( $class ) ? $class : 'global-button' . ( $size ? ' global-button--' . $size : '' );
$class = ( is_array( $class ) ) ? implode( ' ', $class ) : $class;


/**
 * Get the url value
 * If no value is explicity passed, then use ACF (if exists)
 */
$url = ( isset( $url ) ) ? $url : null;
if ( ! $url && $acf ) {
	$url = ( isset( $acf['url'] ) ) ? $acf['url'] : $url;
}

/**
 * Set the button attachment. This will be an image or character
 * that is prepended or appended to the text inside the button.
 *
 * This should be a string, such as 'right_arrow'.
 */
$attachment = isset( $attachment ) ? $attachment : null;
if ( ! $attachment && $acf ) {
	$attachment = ( isset( $acf['attachment'] ) ) ? $acf['attachment'] : $attachment;
}


/**
 * Get the title to use for this button.
 */
$accessibility_title = ( isset( $accessibility_title ) ) ? $accessibility_title : null;
if ( ! $accessibility_title && $acf ) {
	$accessibility_title = ( isset( $acf['accessibility_title'] ) ) ? $acf['accessibility_title'] : $text;
}
$accessibility_title = ( $accessibility_title ) ? $accessibility_title : $text;


/**
 * Set arbitrary attributes for the button (such as data attributes).
 *
 * Note: this is not currently available in the CMS.
 */
$attributes = ( isset( $attributes ) && is_array( $attributes ) ) ? $attributes : array();


$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
$eid_widget_slug = isset( $eid_widget_slug ) ? $eid_widget_slug : null;
if ( ! $eid_widget_slug && isset( $acf ) && $acf ) {
	$eid_widget_slug = ( isset( $acf['eid_widget_slug'] ) ) ? $acf['eid_widget_slug'] : $eid_widget_slug;
}
if ( ! $eid_widget_slug ) {
	$eid_widget_slug = $text;
}


/**
 * If a url is provided, we should display an <a> tag
 */
if ( $url ) {
	wonder_include_template_file(
		'partials/link.php',
		array(
			'acf' => $acf,
			'accessibility_title' => $accessibility_title,
			'attachment' => $attachment,
			'attributes' => $attributes,
			'class' => $class,
			'link_type' => ( isset( $link_type ) ) ? $link_type : null,
			'open_in_new_tab' => ( isset( $open_in_new_tab ) ) ? $open_in_new_tab : null,
			'text' => $text,
			'url' => $url,
			'eid_page_slug' => $eid_page_slug,
			'eid_section_slug' => $eid_section_slug,
			'eid_widget_slug' => $eid_widget_slug,
			'lottie_id' => ( isset( $lottie_id ) ) ? $lottie_id : null,
		)
	);

	/**
	 * If no url is provided, we should display a <button> tag
	 */
} else {
	/**
	 * Concatenate all text and attachments into the content
	 * for this link.
	 */
	$content = $text;
	if ( $attachment ) {
		switch ( $attachment ) {
			case 'download':
				$content = $text . '<img alt="download icon" class="download" src="' . get_template_directory_uri() . '/assets/imgs/global/home_download_d.svg">';
				break;
			case 'download-white':
				$content .= '<img alt="download icon" class="download" src="' . get_template_directory_uri() . '/assets/imgs/global/download-white.svg">';
				break;
			case 'right_arrow':
				$content = $text . '<img alt="right arrow" class="right-arrow" src="' . get_template_directory_uri() . '/assets/imgs/global/godaddy-venture-cta_arrow_21x12_00a4a6-on-trans.svg">';
				break;
			case 'mail':
				$content = $text . '<img alt="mail icon" class="mail" src="' . get_template_directory_uri() . '/assets/imgs/global/mail.svg">';
				break;
			case 'download-ani':
				$content = $text . '<span title="download icon with animation when selected" class="download-ani"></span>';
				break;
			default:
				$content = $content;
				break;
		}
	}

	$eid = wonder_create_eid_string(
		$eid_page_slug,
		$eid_section_slug,
		$eid_widget_slug,
		'click'
	);
	?>
	<button type="submit"
		<?php
		if ( $class ) {
			?>
			 class="<?php echo esc_attr( $class ); ?>"<?php } ?>
		<?php
		if ( $accessibility_title ) {
			$accessibility_title = trim( strip_tags( $accessibility_title ) );
			?>
			 aria-label="<?php echo esc_attr( $accessibility_title ); ?>"<?php } ?>
		<?php
		if ( $eid ) {
			?>
			 data-eid="<?php echo esc_attr( $eid ); ?>"<?php } ?>
		<?php foreach ( $attributes as $attribute => $value ) { ?>
			<?php echo esc_html( $attribute ); ?>="<?php echo esc_attr( $value ); ?>"
		<?php } ?>
	>
		<?php
		echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		?>
	</button>
<?php } ?>
