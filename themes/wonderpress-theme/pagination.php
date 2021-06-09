<?php
/**
 * The pagination template.
 *
 * @package Wonderpress Theme
 */

?>
<footer class="global-pagination__footer">
	<div class="global-pagination__grid">
		<div class="global-pagination__links-area">
			<?php previous_posts_link( 'Previous' ); ?><?php next_posts_link( 'Next' ); ?>
		</div>
	</div>
</footer>
