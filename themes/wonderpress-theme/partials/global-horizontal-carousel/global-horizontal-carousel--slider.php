<?php
/**
 * A reusable template for a featured stories slider
 *
 * @package Wonderpress Theme
 */

// Allow for the passing in of a reusable acf component
$acf        = ( isset( $acf ) && is_array( $acf ) ) ? $acf : null;
$iter_x     = 1;
$slider_id  = isset( $slider_id ) ? $slider_id : 'slider';
$story_objs = isset( $story_objs ) ? $story_objs : array();

if ( ! $story_objs && $acf ) {
	$story_objs = ( isset( $acf['stories'] ) ) ? $acf['stories'] : $story_objs;
}

$eid_page_slug = isset( $eid_page_slug ) ? $eid_page_slug : null;
$eid_section_slug = isset( $eid_section_slug ) ? $eid_section_slug : null;
?>

<div class="global-horizontal-carousel" role="complementary" aria-labelledby="global-horizontal-carousel_title" aria-describedby="global-horizontal-carousel_desc">
	<h3 id="global-horizontal-carousel_title" class="global-screen-reader-copy">Carousel content with slides.</h3>
	<p id="global-horizontal-carousel_desc" class="global-screen-reader-copy">A carousel is a rotating set of images with associated copy, rotation stops on keyboard focus on carousel tab controls or hovering the mouse pointer over images. Use the tabs or the previous and next buttons to change the displayed slide.</p>
	<div id="<?php echo esc_attr( $slider_id ); ?>" class="global-horizontal-carousel__slider">
		<?php foreach ( $story_objs as $story_obj ) { ?>
			<div id="global-horizontal-carousel_<?php echo esc_html( $iter_x ); ?>_slide" class="keen-slider__slide number-slide<?php echo esc_html( $iter_x ); ?>" role="tabpanel" aria-labelledby="global-horizontal-carousel_<?php echo esc_html( $iter_x ); ?>_slide">
				<?php
				$img = get_field( 'horizontal_slider_image', $story_obj );

				wonder_include_template_file(
					'partials/image.php',
					array(
						'src' => $img ? $img['sizes']['large'] : get_the_post_thumbnail_url( $story_obj, 'large' ),
						'alt' => get_the_title( $story_obj ),
						'class' => 'global-horizontal-carousel__img',
					)
				);
				?>
				<div class="global-horizontal-carousel__copyCont">
					<h4 class="global-horizontal-carousel__category"><?php echo esc_html( get_the_category( $story_obj )[0]->name ); ?></h4>
					<h3 class="global-horizontal-carousel__title"><?php echo esc_html( get_the_title( $story_obj ) ); ?></h3>
					<p class="global-horizontal-carousel__p"><?php echo esc_html( get_the_excerpt( $story_obj ) ); ?></p>
				</div>
				<?php
				$iter_x++;
				?>
				<?php
				$eid = wonder_create_eid_string(
					$eid_page_slug,
					$eid_section_slug,
					get_the_title( $story_obj ),
					'click'
				);
				?>
				<a data-eid="<?php echo esc_attr( $eid ); ?>" class="global-story__hotspot" href="<?php echo esc_url( get_permalink( $story_obj ) ); ?>" title="<?php echo esc_attr( get_the_title( $story_obj ) ); ?>"><span class="global-screen-reader-copy">Read the article entitled “<?php echo esc_html( get_the_title( $story_obj ) ); ?>”</span></a>
			</div>
		<?php } ?>
		<button id="slider-left_btn" class="global-direction-controls__button"
				aria-controls="slider" aria-label="Left" disabled>
			<img alt=""
				 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-carousel_arrow_44x44_ffffff-on-trans.svg">
		</button>
		<button id="slider-right_btn" class="global-direction-controls__button"
				aria-controls="slider" aria-label="Right">
			<span></span>
			<img alt=""
				 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-carousel_arrow_44x44_ffffff-on-trans.svg">
		</button>
	</div>
	<div class="global-horizontal-carousel__pagination-dots" role="tablist"></div>
</div>
