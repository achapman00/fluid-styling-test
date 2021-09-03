<?php
/**
 * The template for displaying a header.
 *
 * @package Wonderpress Theme
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js html" prefix="og: https://ogp.me/ns#">
	<head>
		<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Head: Metas ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
		<?php /*<meta name="robots" content="noindex,nofollow"><meta name="description" content="<?php bloginfo( 'description' ); ?>">*/ ?>
		<meta name="theme-color" content="#174148">
		<meta name="application-name" content="Venture Forward by GoDaddy">
		<?php
		while ( have_rows( 'global_meta_tags', 'option' ) ) {
			the_row();
			?>
		<meta <?php echo esc_attr( ( get_sub_field( 'key_type' ) == 'name' ) ? 'name' : 'property' ); ?>="<?php the_sub_field( 'key' ); ?>" content="<?php the_sub_field( 'value' ); ?>">
		<?php } ?>
		<?php
		while ( have_rows( 'page_specific_meta_tags' ) ) {
			the_row();
			?>
		<meta <?php echo esc_attr( ( get_sub_field( 'key_type' ) == 'name' ) ? 'name' : 'property' ); ?>="<?php the_sub_field( 'key' ); ?>" content="<?php the_sub_field( 'value' ); ?>">
		<?php } ?>
		<?php if ( get_field( 'defined_meta_tag_format' ) ) { ?>
		<meta name="Format" content="<?php the_field( 'defined_meta_tag_format' ); ?>">
		<?php } ?>
		<?php if ( get_field( 'defined_meta_tag_city_size' ) ) { ?>
		<meta name="City Size" content="<?php the_field( 'defined_meta_tag_city_size' ); ?>">
		<?php } ?>
		<?php if ( get_field( 'defined_meta_tag_quality_of_city' ) ) { ?>
		<meta name="Quality of City" content="<?php the_field( 'defined_meta_tag_quality_of_city' ); ?>">
		<?php } ?>
		<?php if ( get_field( 'defined_meta_tag_region' ) ) { ?>
		<meta name="Region" content="<?php the_field( 'defined_meta_tag_region' ); ?>">
		<?php } ?>
		<?php if ( get_field( 'defined_meta_tag_topic' ) ) { ?>
		<meta name="Topic" content="<?php the_field( 'defined_meta_tag_topic' ); ?>">
		<?php } ?>
		<?php if ( get_field( 'defined_meta_tag_message' ) ) { ?>
		<meta name="Message" content="<?php the_field( 'defined_meta_tag_message' ); ?>">
		<?php } ?>

		<title><?php wp_title(); ?></title>
		<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Head: Links ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<link href="//www.google-analytics.com" rel="dns-prefetch">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link rel="preload" href="<?php WPStringUtil::get_base_uri(); ?>assets/fonts/GDSage-Bold.woff2" as="font" type="font/woff2" crossorigin>
		<link rel="preload" href="<?php WPStringUtil::get_base_uri(); ?>assets/fonts/GDSherpa-Bold.woff2" as="font" type="font/woff2" crossorigin>
		<link rel="preload" href="<?php WPStringUtil::get_base_uri(); ?>assets/fonts/GDSherpa-Regular.woff2" as="font" type="font/woff2" crossorigin>
		<link rel="preload" href="<?php WPStringUtil::get_base_uri(); ?>assets/fonts/GDSherpa-Semibold.woff2" as="font" type="font/woff2" crossorigin>
		<?php
		if ( is_singular() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );}
		?>

		<?php wp_head(); ?>
		<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Head: Analytics ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

		<script type="text/javascript">
			var _gaDataLayer = _gaDataLayer || [];
		</script>
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-SXRF" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0], j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src= '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
			})(window,document,'script','_gaDataLayer','GTM-SXRF');</script>

		<?php
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,Generic.Files.EndFileNewline.NotFound
		require_once 'lib/mobiledetect/mobiledetectlib/Mobile_Detect.php';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,Generic.Files.EndFileNewline.NotFound
		$detect = new Mobile_Detect();// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,Generic.Files.EndFileNewline.NotFound
		$device_type = ( $detect->isMobile() ? ( $detect->isTablet() ? 'tablet' : 'phone' ) : 'computer' );
		$device_ios = $detect->isIOS() ? 'yes' : 'no';// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,Generic.Files.EndFileNewline.NotFound
		$device_ios_version = $detect->version( 'iOS' );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped,Generic.Files.EndFileNewline.NotFound
		$vf_param_metro = false;
		if ( isset( $_GET['metro'] ) ) {
			$vf_param_metro = sanitize_text_field( wp_unslash( $_GET['metro'] ) );
		}
		?>
	</head>

	<body id="<?php echo esc_attr( wonder_body_id() ); ?>" <?php body_class( 'body' ); ?> data-device-type="<?php echo esc_attr( $device_type ); ?>" data-device-ios="<?php echo esc_attr( $device_ios ); ?>" data-device-ios-version="<?php echo esc_attr( $detect->version( 'iOS' ) ); ?>" data-uri-assets-imgs="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/" data-metro="<?php echo esc_attr( $vf_param_metro ); ?>">
		<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Global Nav ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
		<header id="global-nav" class="global-nav" role="banner">
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Global Nav: Desktop ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<nav class="global-nav-desktop">
				<a class="global-nav-desktop__tm" title="GoDaddy Venture Forward home" href="<?php echo esc_url( home_url() ); ?>">
					<img alt="GoDaddy Venture Forward Trademark" width="149" height="30"
						 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-forward_tm_1000x202_000000-on-trans.svg">
				</a>
				<?php
				echo wonder_nav( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'header-menu-1',
					array(
						'container' => false,
						'theme_location'    => 'header-menu-1',
						'menu_class'        => 'global-nav-desktop__ul',
						'list_item_classes' => 'global-nav-desktop__li',
						'anchor_classes'    => 'global-nav-desktop__a',
						'link_before' => '<span>',
						'link_after' => '</span>',
					)
				);
				$obj_id = get_queried_object_id();
				$current_url = get_permalink( $obj_id );
				// echo $current_url;
				?>

			</nav>
			<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Global Nav: Mobile/Tablet ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
			<nav id="global-nav-mobile-tablet_nav" class="global-nav-mobile-tablet">
				<section class="global-nav-mobile-tablet__section">
					<button class="global-nav-mobile-tablet__button" aria-controls="mobile-tablet-menu" aria-label="Toggle the sub menu between close and open states" data-ani-action="mobile-close-button">
						<img alt="menu" width="14" height="12" src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-forward_hamburger_14x12_004249-on-trans.svg">
						<div>
							<span class="global-screen-reader-copy">Close icon that transitions in from small to full size X</span>
						</div>
					</button>
					<a class="global-nav-mobile-tablet__tm" title="GoDaddy Venture Forward home" href="<?php echo esc_url( home_url() ); ?>">
						<img alt="GoDaddy Venture Forward Trademark" width="161" height="31" src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-forward_tm_1000x202_000000-on-trans.svg">
					</a>
				</section>
				<section id="mobile-tablet-menu" class="global-nav-mobile-tablet__section" aria-expanded="false">
					<?php
					echo wonder_nav( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'header-menu-1',
						array(
							'container' => false,
							'theme_location'    => 'header-menu-1',
							'menu_class'        => 'global-nav-mobile-tablet__ul',
							'list_item_classes' => 'global-nav-mobile-tablet__li',
							'anchor_classes'    => 'global-nav-mobile-tablet__a',
						)
					);
					?>
				</section>
			</nav>
		</header>
		<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Global Nav: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
