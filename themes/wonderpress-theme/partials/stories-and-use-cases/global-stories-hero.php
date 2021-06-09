<?php
/**
 * A reusable template for Global Stories hero
 *
 * @package Wonderpress Theme
 */

?>

<?php
$story_obj = get_field( 'stories_hero_featured_story', 'option' );
$page_theme = has_post_thumbnail( get_the_ID() ) ? '' : 'dark';
$first_name = get_the_author_meta( 'first_name' );
$last_name = get_the_author_meta( 'last_name' );
$full_name = '';
if ( empty( $first_name ) ) {
	$full_name = $last_name;
} elseif ( empty( $last_name ) ) {
	$full_name = $first_name;
} else {
	$full_name = "{$first_name}&nbsp;{$last_name}";
}

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
?>

<section id="hero"
		 class="global-stories-hero__section global-stories-hero__section--hero global-stories-hero__section--hero-<?php echo esc_attr( $page_theme ); ?>">
	<?php if ( 'dark' !== $page_theme ) { ?>
		<?php
		// Template: Image
		wonder_include_template_file(
			'partials/image.php',
			array(
				'src' => esc_url( get_the_post_thumbnail_url( $story_obj, 'banner' ) ),
				'class' => 'global-stories-hero__bkgd-img',
			)
		);
		?>
		<div class="global-stories-hero__bkgd-img-shade"></div>
		<?php
	}
	?>
	<div class="global-stories-hero__grid global-stories-hero__grid--<?php echo esc_attr( $page_theme ); ?>">
		<?php if ( 'dark' !== $page_theme ) { ?>
			<div class="global-stories-hero__time global-stories-hero__time--<?php echo esc_attr( $page_theme ); ?>">
				<time class="global-stories-hero__time-date"><?php echo esc_html( get_the_date( 'M d, Y', $story_obj ) ); ?></time>&nbsp;|&nbsp;<span
						class="global-stories-hero__min-read-wrapper"><span
							class="global-stories-hero__time-min"><?php echo esc_html( get_estimated_reading_time( get_the_content() ) ); ?></span>
				min read</span>
			</div>
			<?php
		}
		?>
		<div class="global-stories-hero__category-h1-cont">
			<h6 class="global-stories-hero__category global-stories-hero__category--<?php echo esc_attr( $page_theme ); ?>">
				<?php
				$category = get_the_category( $story_obj );
				echo esc_html( $category[0]->cat_name );
				?>
			</h6>
			<h1 class="global-stories-hero__h1"><?php echo esc_html( get_the_title( $story_obj ) ); ?></h1>
			<?php
			$obj_id = get_queried_object_id();
			$current_url = get_permalink( $obj_id );
			if ( get_permalink( $story_obj ) !== $current_url ) {
				$eid = wonder_create_eid_string(
					$eid_page_slug,
					$eid_section_slug,
					get_the_title( $story_obj ),
					'click'
				);
				?>
			<a data-eid="<?php echo esc_attr( $eid ); ?>" class="global-cta global-cta--dark-2" href="<?php echo esc_url( get_permalink( $story_obj ) ); ?>">
				Read the Story
				<img alt="right arrow" class="right-arrow" src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/imgs/global/godaddy-venture-cta_arrow_21x12_00a4a6-on-trans.svg">
				<span
						class="global-screen-reader-copy">Read the article entitled “<?php echo esc_html( get_the_title( $story_obj ) ); ?>”</span>
			</a>
		</div>
		<?php } ?>


		<?php if ( 'dark' !== $page_theme ) { ?>
			<?php
			/*
			<div class="global-stories-hero__author">By&nbsp;<?php echo esc_html( $full_name ); ?></div>
			*/
			?>
			<?php
		} else {
			?>
			<div class="global-stories-hero__dark-theme-time-author-cont">
				<div class="global-stories-hero__time global-stories-hero__time--<?php echo esc_attr( $page_theme ); ?>">
					<time class="global-stories-hero__time-date"><?php echo esc_html( get_the_date( 'M d, Y', $story_obj ) ); ?></time>&nbsp;|&nbsp;<span
							class="global-stories-hero__min-read-wrapper global-stories-hero__min-read-wrapper--<?php echo esc_attr( $page_theme ); ?>"><span
								class="global-stories-hero__time-min"><?php echo esc_html( get_estimated_reading_time( get_the_content() ) ); ?></span>
	min read</span>
				</div>
				<?php
				/*
				<div class="global-stories-hero__author global-stories-hero__author--<?php echo esc_attr( $page_theme ); ?>">By&nbsp;<?php echo esc_html( $full_name ); ?></div>
				*/
				?>

			</div>

			<?php
		}
		?>
	</div>

</section>
