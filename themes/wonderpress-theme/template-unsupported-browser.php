<?php
/**
 * Template Name: Unsupported Browser Page
 * Description: A custom Unsupported Browser Page template.
 *
 * @package Wonderpress Theme
 */

// Set the <body> id
wonder_body_id( 'unsupported-browser' );

get_header();


$supported_browsers = array();
$supported_browsers[0] = new stdClass();
$supported_browsers[0]->title = 'Google Chrome';
$supported_browsers[0]->src = 'assets/imgs/unsupported-browsers/browser-chrome.svg';
$supported_browsers[0]->href = 'https://www.google.com/chrome';
$supported_browsers[1]->title = 'Safari';
$supported_browsers[1]->src = 'assets/imgs/unsupported-browsers/browser-safari.svg';
$supported_browsers[1]->href = 'https://support.apple.com/downloads/safari';
$supported_browsers[2]->title = 'Firefox';
$supported_browsers[2]->src = 'assets/imgs/unsupported-browsers/browser-firefox.svg';
$supported_browsers[2]->href = 'https//www.mozilla.org';
$supported_browsers[3]->title = 'Opera';
$supported_browsers[3]->src = 'assets/imgs/unsupported-browsers/browser-opera.svg';
$supported_browsers[3]->href = 'http://www.opera.com';
$supported_browsers[4]->title = 'Microsoft Edge';
$supported_browsers[4]->src = 'assets/imgs/unsupported-browsers/browser-microsoft-edge.svg';
$supported_browsers[4]->href = 'https://www.microsoft.com/en-us/edge';
$supported_browsers[5]->title = 'Chromium';
$supported_browsers[5]->src = 'assets/imgs/unsupported-browsers/browser-chromium.svg';
$supported_browsers[5]->href = 'https://www.chromium.org/Home';

?>

<main class="global-main" role="main">

	<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Hero ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
	<section id="hero" class="unsupported-browsers__section unsupported-browsers__section--hero">
		<div class="unsupported-browsers-hero__grid">
			<h1 class="unsupported-browsers-hero__h1"><?php the_title(); ?></h1>
			<h2 class="unsupported-browsers-hero__h2"><?php the_content(); ?></h2>
			<?php
			foreach ( $supported_browsers as $browser ) {
				?>
				<div class="unsupported-browsers-hero__browser-card">
					<img class="unsupported-browsers-hero__img"
						 alt="<?php echo esc_attr( $browser->title ); ?> browser trademark"
						 src="
						 <?php
							WPStringUtil::get_base_uri();
							echo esc_attr( $browser->src )
							?>
						 ">
					<h3 class="unsupported-browsers-hero__h3"><?php echo esc_attr( $browser->title ); ?></h3>
					<p class="unsupported-browsers-hero__version">current +1</p>
					<a class="global-story__hotspot"
					   href="<?php echo esc_url( $browser->href ); ?>"
					   target="_blank">
						<span
							class="global-screen-reader-copy">Download the latest <?php echo esc_attr( $browser->title ); ?> web browser</span>
					</a>
				</div>

				<?php
			}
			?>
		</div>


	</section>
</main>

<?php get_footer(); ?>
