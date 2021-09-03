<?php
/**
 * The template for displaying a footer.
 *
 * @package Wonderpress Theme
 */

?>
<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Footer ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
<footer id="footer" class="global-footer global-footer--style-1" role="contentinfo">
	<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Footer: Grid ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->
	<section id="footer-links" class="global-footer__section">
		<div class="global-footer__grid">
			<a class="global-footer__tm" title="Contact the GoDaddy Venture Forward Team by email" href="mailto:ventureforward@godaddy.com?subject=GoDaddy%20Venture%20Forward%20Inquiry&body=Dear%20Venture%20Forward%20Team%2C%0D%0A" data-eid="comms.microsites.venture-forward/footer.nav.mailto.click">
				<img alt="GoDaddy Venture Forward Trademark" width="155" height="31"
					 src="<?php WPStringUtil::get_base_uri(); ?>assets/imgs/global/godaddy-venture-forward_tm_1000x202_ffffff-on-trans.svg">
			</a>

			<div class="global-footer__menu global-footer__menu--style-1">
				<h2 class='global-footer__h2'>Sections</h2>
				<?php

				echo wonder_nav( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'footer-menu-1',
					array(
						'theme_location' => 'footer-menu-1',
						'container' => false,
						// 'container_class' => 'global-footer__menu global-footer__menu--style-1',
						// 'container_id' => '',
						// 'menu' => 'menu',
						// 'menu_class' => 'menu_class',
						// 'menu_id' => 'menu_id',
							'echo' => false,
						'fallback_cb' => 'wp_page_menu',
						'before' => '',
						'after' => '',
						'link_before' => '',
						'link_after' => '',
						'items_wrap' => '<ul>%3$s</ul>',
						'depth' => 0,
						'walker' => '',
					)
				);

				?>
			</div>

			<div class="global-footer__menu global-footer__menu--style-2">
				<h2 class='global-footer__h2'>About GoDaddy</h2>
				<?php

				echo wonder_nav( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'footer-menu-2',
					array(
						'theme_location' => 'footer-menu-2',
						'container' => false,
						// 'container_class' => 'global-footer__menu global-footer__menu--style-1',
						// 'container_id' => '',
						// 'menu' => 'menu',
						// 'menu_class' => 'menu_class',
						// 'menu_id' => 'menu_id',
							'echo' => false,
						'fallback_cb' => 'wp_page_menu',
						'before' => '',
						'after' => '',
						'link_before' => '',
						'link_after' => '',
						'items_wrap' => '<ul>%3$s</ul>',
						'depth' => 0,
						'walker' => '',
					)
				);

				?>
			</div>
		</div>
	</section>
	<hr class="global-footer__hr">
	<section id="footer-legal" class="global-footer__section">
		<div class="global-footer__grid">
			<div class="global-footer__menu-legal-links">
				<?php

				echo wonder_nav( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'footer-menu-legal-links',
					array(
						'theme_location' => 'footer-menu-legal-links',
						'menu' => '',
						'container' => false,
						// 'container_class' => 'global-footer__menu-legal-links',
						// 'container_id' => '',
							'menu_class' => 'global-footer__menu-legal-links',
						'menu_id' => '',
						'echo' => false,
						'fallback_cb' => 'wp_page_menu',
						'before' => '',
						'after' => '',
						'link_before' => '',
						'link_after' => '',
						'items_wrap' => '<ul>%3$s</ul>',
						'depth' => 0,
						'walker' => '',
					)
				);
				?>

			</div>

			<div class="global-footer__copyright">
				<p>
					<?php
					$copyright_start_year = 2020;
					$c = gmdate( 'Y' );
					$copyright_current_year = ( $copyright_start_year !== $c ) ? $c : null;
					/** Do not touch - If the time elements are kept on separate lines they will introduce a space */
					?>
					©
					<time datetime="<?php echo esc_html( $copyright_start_year ); ?>"><?php echo esc_html( $copyright_start_year ); ?></time><!--
					<?php
					if ( $copyright_current_year !== $copyright_start_year && ! is_null( $copyright_start_year ) ) {
						?>
						-->-<!--
					--><time datetime="<?php echo esc_html( $copyright_current_year ); ?>"><?php echo esc_html( $copyright_current_year ); ?></time>
					<?php } ?>&nbsp;GoDaddy Operating Company, LLC.<br>All Rights Reserved.
				</p>
			</div>
		</div>

	</section>

</footer>
<!-- ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ Footer: End ▓▓▓▓▓▓▓▓▓▓▓▓▓▓▓ -->

<?php wp_footer(); ?>
<?php
// Template: modal
wonder_include_template_file(
	'partials/modal.php'
);
?>
</body>
</html>
