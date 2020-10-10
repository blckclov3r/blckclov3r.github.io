<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
		</div>
	</div>
	<!-- /MAZ Loader Content -->
	<!-- MAZ Loader Footer -->
	<div class="container-fluid mazloader-footer">
		<div class="row">
			<div class="col-md-12 mazl-padding-0">
				<div class="footer-inner">
					<?php _e( 'MAZ Loader', 'maz-loader' ); ?> &copy;
					<?php echo date('Y') . __( ' All Rights Reserved', 'maz-loader' ); ?>
					<span class="mazl-float-right"><?php _e( 'version', 'maz-loader' ); ?> <?php echo esc_html( MZLDR_Helper::getVersion() ); ?></span>
				</div>
			</div>
		</div>
	</div>
	<!-- /MAZ Loader Footer -->
	<?php
	
	include(MZLDR_ADMIN_PATH . 'partials/pro/popup.php');
	
	?>
	<p class="mazloader-footer-review">
		<?php _e('Support us by rating <strong>MAZ Loader</strong>', 'maz-loader') ?> <a href="https://wordpress.org/support/plugin/maz-loader/reviews/?filter=5#new-post" target="_blank" rel="noopener noreferrer">★★★★★</a> <?php _e('on', 'maz-loader') ?> <a href="https://wordpress.org/support/plugin/maz-loader/reviews/?filter=5#new-post" target="_blank" rel="noopener">WordPress.org</a> <?php _e('to help us spread the word. A thank you from the Feataholic team!', 'maz-loader'); ?>
	</p>
</div>
<!-- /MAZ Loader Admin Page -->
