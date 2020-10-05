<?php
	namespace MasterAddons\Admin\Dashboard\Extensions;
	use MasterAddons\Master_Elementor_Addons;
    use MasterAddons\Inc\Helper\Master_Addons_Helper;
    use MasterAddons\Admin\Dashboard\Addons\ThirdPartyPlugins;

	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 9/5/19
	 */

	include_once MELA_PLUGIN_PATH . '/inc/admin/jltma-elements/ma-third-party-plugins.php';
	?>

	<h3><?php echo esc_html__('Third Party Plugins', MELA_TD);?></h3>

	<!-- Third Party Plugins -->
	<?php foreach( Master_Elementor_Addons::$jltma_third_party_plugins as $key=>$jltma_plugins ) { 

		// if($jltma_elements['jltma-plugins']['plugin'][$key]['key'] === "custom-breakpoints"){
		// 	if ( !ma_el_fs()->is_plan('developer', true) ) {
		// 		continue;
		// 	}			
		// }

        $plugin_file = $jltma_elements['jltma-plugins']['plugin'][$key]['plugin_file'];
        $plugin_slug = $jltma_elements['jltma-plugins']['plugin'][$key]['wp_slug'];
	?>

		<div class="master-addons-dashboard-checkbox col">
			<div class="master-addons-dashboard-checkbox-content">

				<div class="master-addons-features-ribbon">
					<?php
						$is_pro = "";
						if ( isset( $jltma_plugins ) ) {
							if ( is_array( $jltma_plugins ) ) {
								$is_pro = $jltma_plugins[1];
								$jltma_plugins = $jltma_plugins[0];

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
						<?php echo $jltma_elements['jltma-plugins']['plugin'][$key]['title']; ?>
					</div> <!-- master-addons-el-title-content -->
					<div class="ma-el-tooltip">
						<?php 
							if ($plugin_slug and $plugin_file) {
								if ( Master_Addons_Helper::is_plugin_installed($plugin_slug, $plugin_file) ) {
									if ( ! current_user_can( 'install_plugins' ) ) { return; }
									if( !jltma_is_plugin_active( $plugin_file ) ){
								        $activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin_file . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin_file );
					                    $html = '<a class="thrd-party-plgin-dnld thrd-party-plgin-dnld-active" href="' . $activation_url . '" ><span class="thrd-party-plgin-dnld thrd-party-plgin-dnld-active pr-1">' . esc_html__('Activate', MELA_TD) . '</span><i class="dashicons dashicons-yes-alt"></i></a>';
		        					}else{
		        						$html ='';
		        					}

		        				} else{

	        						$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ), 'install-plugin_' . $plugin_slug );
		                    		$html = '<a class="thrd-party-plgin-dnld" href="' . $install_url . '"><span class="thrd-party-plgin-dnld pr-1">' . esc_html__('Download', MELA_TD) . '</span><i class="dashicons dashicons-download"></i></a>';

		                    		activate_plugin($plugin_file);
		        					
		        				}
								echo $html;
							}
	        			?>
					</div>
				</div> <!-- .master-addons-el-title -->


				<div class="master_addons_feature_switchbox">
					<label for="<?php echo esc_attr( $jltma_plugins ); ?>"
						class="switch switch-text switch-primary switch-pill <?php
						if( !ma_el_fs()->can_use_premium_code() && isset($is_pro) && $is_pro !="") { echo "ma-el-pro";} ?>">

						<?php if ( ma_el_fs()->can_use_premium_code() ) { ?>

							<input type="checkbox"
							id="<?php echo esc_attr( $jltma_plugins ); ?>"
							class="switch-input"
							name="<?php echo esc_attr( $jltma_plugins ); ?>"
							<?php checked( 1, $this->jltma_get_third_party_plugins_settings[$jltma_plugins], true ); ?>>

						<?php } else {

							if ( isset( $jltma_plugins ) ) {
								if ( is_array( $jltma_plugins ) ) {
									$is_pro = $jltma_plugins[1];
								}
							} ?>

							<input
							type="checkbox" id="<?php echo esc_attr( $jltma_plugins ); ?>"
							class="switch-input "
							name="<?php echo esc_attr( $jltma_plugins ); ?>"

							<?php
							if( !ma_el_fs()->can_use_premium_code() && $is_pro =="pro") {
								checked( 0,$this->jltma_get_third_party_plugins_settings[$jltma_plugins], false );
								echo "disabled";
							}else{
								checked( 1, $this->jltma_get_third_party_plugins_settings[$jltma_plugins], true );
							}  ?>/>
						<?php  }?>

						<span data-on="On" data-off="Off" class="switch-label"></span>
						<span class="switch-handle"></span>

					</label>
				</div>
			</div>
		</div>

	<?php } ?>