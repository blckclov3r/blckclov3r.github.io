<?php
namespace MasterAddons\Admin\Dashboard\Addons;
use MasterAddons\Master_Elementor_Addons;
use MasterAddons\Admin\Dashboard\Addons\Forms;
use MasterAddons\Inc\Helper\Master_Addons_Helper;
include_once MELA_PLUGIN_PATH . '/inc/admin/jltma-elements/ma-forms.php';
?>

<div class="master_addons_feature">

	<h3><?php echo esc_html__('Form Addons', MELA_TD);?></h3>

	<?php foreach( array_slice( Master_Elementor_Addons::$maad_el_default_widgets, 37, 6 ) as
	$key=>$widget ) : ?>

	<div class="master-addons-dashboard-checkbox col">

		<div class="master-addons-dashboard-checkbox-content">

			<div class="master-addons-features-ribbon">
				<?php
					$is_pro = "";
					if ( isset( $widget ) ) {
						if ( is_array( $widget ) ) {
							$is_pro = $widget[1];
							$widget = $widget[0];

							if( !ma_el_fs()->can_use_premium_code()) {
								echo '<span class="pro-ribbon">';
								echo ucwords( $is_pro );
								echo '</span>';
							}
						}

					}
				?>
			</div>

			<div class="master-addons-el-title">
				<div class="master-addons-el-title-content">
					<?php echo $jltma_elements['jltma-forms']['elements'][$key]['title']; ?>
				</div> <!-- master-addons-el-title-content -->


				<div class="ma-el-tooltip">

					<?php
					Master_Addons_Helper::jltma_admin_tooltip_info('Demo',$jltma_elements['jltma-forms']['elements'][$key]['demo_url'], 'eicon-device-desktop' );
					Master_Addons_Helper::jltma_admin_tooltip_info('Documentation',$jltma_elements['jltma-forms']['elements'][$key]['docs_url'], 'eicon-info-circle-o' );
					Master_Addons_Helper::jltma_admin_tooltip_info('Video Tutorial',$jltma_elements['jltma-forms']['elements'][$key]['tuts_url'], 'eicon-video-camera' );
					?>

				</div>

			</div> <!-- .master-addons-el-title -->


			<?php if ( isset( $widget ) ) {
				if ( is_array( $widget ) ) {
					$is_pro = $widget[1];
				}
			} ?>

			<div class="master_addons_feature_switchbox">
				<label for="<?php echo esc_attr( $widget ); ?>" class="switch switch-text
					switch-primary switch-pill <?php if( !ma_el_fs()->can_use_premium_code() && isset($is_pro) && $is_pro !="") { echo "ma-el-pro";} ?>">


						<?php if ( ma_el_fs()->can_use_premium_code() ) { ?>

							<input type="checkbox"
							id="<?php echo esc_attr( $widget ); ?>"
							class="switch-input"
							name="<?php echo esc_attr( $widget ); ?>"
							<?php checked( 1, $this->maad_el_get_settings[$widget], true ); ?>>

							<?php } else { ?>

							<input
							type="checkbox" id="<?php echo esc_attr( $widget ); ?>"
							class="switch-input "
							name="<?php echo esc_attr( $widget ); ?>"

							<?php
							if( !ma_el_fs()->can_use_premium_code() && $is_pro =="pro") {
								checked( 0,$this->maad_el_get_settings[$widget], false );
								echo "disabled";
							}else{
								checked( 1, $this->maad_el_get_settings[$widget], true );
							}  ?>/>
						<?php } ?>

						<span data-on="On" data-off="Off" class="switch-label"></span>
						<span class="switch-handle"></span>
					</label>
				</div>
			</div>
		</div>

	<?php endforeach; ?>

</div> <!--  .master_addons_feature-->

