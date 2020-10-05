<style>
#wpcontent{
		  padding-left:0px !important;
		  
	  }
	  .panel-primary{
		  
		  border:0px !important;
	  }
	  .panel-default{
		 background:#f1f1f1 !important; 
		  
	  }
	  .postbox{
		  
		  border: 0px solid #e5e5e5 !important;
			background: #f1f1f1 !important;
	  }
		  
</style>
<div class="row">
	
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table2">
				
				<tr class="radio-span" style="border-bottom:none;">
					<td>
					<?php
					include( ABSPATH . "wp-admin/includes/plugin-install.php" );
					global $tabs, $tab, $paged, $type, $term;
					$tabs = array();
					$tab = "search";
					$per_page = 20;
					$args = array
					(
						"author"=> "wpshopmart",
						"page" => $paged,
						"per_page" => $per_page,
						"fields" => array( "last_updated" => true, "downloaded" => true, "icons" => true ),
						"locale" => get_locale(),
					);
					$arges = apply_filters( "install_plugins_table_api_args_$tab", $args );
					$api = plugins_api( "query_plugins", $arges );
					$item = $api->plugins;
					if(!function_exists("wp_star_rating"))
					{
						function wp_star_rating( $args = array() )
						{
							$defaults = array(
									'rating' => 0,
									'type' => 'rating',
									'number' => 0,
							);
							$r = wp_parse_args( $args, $defaults );
					
							// Non-english decimal places when the $rating is coming from a string
							$rating = str_replace( ',', '.', $r['rating'] );
					
							// Convert Percentage to star rating, 0..5 in .5 increments
							if ( 'percent' == $r['type'] ) {
								$rating = round( $rating / 10, 0 ) / 2;
							}
					
							// Calculate the number of each type of star needed
							$full_stars = floor( $rating );
							$half_stars = ceil( $rating - $full_stars );
							$empty_stars = 5 - $full_stars - $half_stars;
					
							if ( $r['number'] ) {
								/* translators: 1: The rating, 2: The number of ratings */
								$format = _n( '%1$s rating based on %2$s rating', '%1$s rating based on %2$s ratings', $r['number'] );
								$title = sprintf( $format, number_format_i18n( $rating, 1 ), number_format_i18n( $r['number'] ) );
							} else {
								/* translators: 1: The rating */
								$title = sprintf( __( '%s rating' ), number_format_i18n( $rating, 1 ) );
							}
					
							echo '<div class="star-rating" title="' . esc_attr( $title ) . '">';
							echo '<span class="screen-reader-text">' . $title . '</span>';
							echo str_repeat( '<div class="star star-full"></div>', $full_stars );
							echo str_repeat( '<div class="star star-half"></div>', $half_stars );
							echo str_repeat( '<div class="star star-empty"></div>', $empty_stars);
							echo '</div>';
						}
					}
					?>
						<form id="frmrecommendation" class="layout-form">
							<div id="poststuff" style="width: 99% !important;">
								<div id="post-body" class="metabox-holder">
									<div id="postbox-container-2" class="postbox-container">
										<div id="advanced" class="meta-box-sortables">
											<div id="gallery_bank_get_started" class="postbox" >
												<div class="handlediv" data-target="ux_recommendation" title="Click to toggle" data-toggle="collapse"><br></div>
												<h2 class="hndle" style="text-align:center;font-size:30px;"><span>More Free Plugins Recommendation From Wpshopmart</span></h3>
												<div class="inside">
													<div id="ux_recommendation" class="gallery_bank_layout">
														
														<div class="fluid-layout">
															<div class="layout-span12">
																<div class="wp-list-table plugin-install">
																	<div id="the-list">
																		<?php 
																		foreach ((array) $item as $plugin) 
																		{
																			
																			if (is_object( $plugin))
																			{
																				$plugin = (array) $plugin;
																				
																			}
																			if (!empty($plugin["icons"]["svg"]))
																			{
																				$plugin_icon_url = $plugin["icons"]["svg"];
																			} 
																			elseif (!empty( $plugin["icons"]["2x"])) 
																			{
																				$plugin_icon_url = $plugin["icons"]["2x"];
																			} 
																			elseif (!empty( $plugin["icons"]["1x"]))
																			{
																				$plugin_icon_url = $plugin["icons"]["1x"];
																			} 
																			else 
																			{
																				$plugin_icon_url = $plugin["icons"]["default"];
																			}
																			$plugins_allowedtags = array
																			(
																				"a" => array( "href" => array(),"title" => array(), "target" => array() ),
																				"abbr" => array( "title" => array() ),"acronym" => array( "title" => array() ),
																				"code" => array(), "pre" => array(), "em" => array(),"strong" => array(),
																				"ul" => array(), "ol" => array(), "li" => array(), "p" => array(), "br" => array()
																			);
																			$title = wp_kses($plugin["name"], $plugins_allowedtags);
																			$description = strip_tags($plugin["short_description"]);
																			$author = wp_kses($plugin["author"], $plugins_allowedtags);
																			$version = wp_kses($plugin["version"], $plugins_allowedtags);
																			$name = strip_tags( $title . " " . $version );
																			$details_link   = self_admin_url( "plugin-install.php?tab=plugin-information&amp;plugin=" . $plugin["slug"] .
																			"&amp;TB_iframe=true&amp;width=600&amp;height=550" );
																			
																			/* translators: 1: Plugin name and version. */
																			$action_links[] = '<a href="' . esc_url( $details_link ) . '" class="thickbox" aria-label="' . esc_attr( sprintf("More information about %s", $name ) ) . '" data-title="' . esc_attr( $name ) . '">' . __( 'More Details' ) . '</a>';
																			$action_links = array();
																			if (current_user_can( "install_plugins") || current_user_can("update_plugins"))
																			{
																				$status = install_plugin_install_status( $plugin );
																				switch ($status["status"])
																				{
																					case "install":
																						if ( $status["url"] )
																						{
																							/* translators: 1: Plugin name and version. */
																							$action_links[] = '<a class="install-now button" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf("Install %s now", $name ) ) . '">' . __( 'Install Now' ) . '</a>';
																						}
																					break;
																					case "update_available":
																						if ($status["url"])
																						{
																							/* translators: 1: Plugin name and version */
																							$action_links[] = '<a class="button" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf( "Update %s now", $name ) ) . '">' . __( 'Update Now' ) . '</a>';
																						}
																					break;
																					case "latest_installed":
																					case "newer_installed":
																						$action_links[] = '<span class="button button-disabled" title="' . esc_attr__( "This plugin is already installed and is up to date" ) . ' ">' . _x( 'Installed', 'plugin' ) . '</span>';
																					break;
																				}
																			}
																			?>
																			<div class="plugin-div plugin-div-settings">
																				<div class="plugin-div-top plugin-div-settings-top">
																					<div class="plugin-div-inner-content">
																						<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox plugin-icon plugin-icon-custom">
																							<img class="custom_icon" src="<?php echo esc_attr( $plugin_icon_url ) ?>" />
																						</a>
																						<div class="name column-name">
																							<h4>
																								<a href="<?php echo esc_url( $details_link ); ?>" class="thickbox"><?php echo $title; ?></a>
																							</h4>
																						</div>
																						<div class="desc column-description">
																							<p>
																								<?php echo $description; ?>
																							</p>
																							<p class="authors">
																								<cite>
																									By <?php echo $author;?>
																								</cite>
																							</p>
																						</div>
																					</div>
																					<div class="action-links">
																						<ul class="plugin-action-buttons-custom">
																							<li>
																								<?php
																									if ($action_links)
																									{
																										echo implode("</li><li>", $action_links);
																									}
																										
																									switch($plugin["slug"])
																									{
																										case "gallery-bank" :
																											?>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-gallery-bank/pricing/" target="_blank" >
																													<?php _e("Premium Editions", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-gallery-bank/" target="_blank" >
																													<?php _e("Visit Website", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																											<?php
																										break;
																										case "contact-bank" :
																											?>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-contact-bank/pricing/" target="_blank" >
																													<?php _e("Premium Editions", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-contact-bank/" target="_blank" >
																													<?php _e("Visit Website", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																											<?php
																										break;
																										case "captcha-bank" :
																											?>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-captcha-bank/pricing/" target="_blank" >
																													<?php _e("Premium Editions", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-captcha-bank/" target="_blank" >
																													<?php _e("Visit Website", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																											<?php 
																										break;
																										case "wp-clean-up-optimizer" :
																											?>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-clean-up-optimizer/pricing/" target="_blank" >
																													<?php _e("Premium Editions", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-clean-up-optimizer/" target="_blank" >
																													<?php _e("Visit Website", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																											<?php 
																										break;
																										case "google-maps-bank":
																											?>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-google-maps-bank/pricing/" target="_blank" >
																													<?php _e("Premium Editions", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-google-maps-bank/" target="_blank" >
																													<?php _e("Visit Website", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																											<?php
																										break;
																										case "wp-backup-bank":
																											?>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-backup-bank/pricing/" target="_blank" >
																													<?php _e("Premium Editions", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																												<a class="plugin-div-button install-now button" href="http://tech-banker.com/products/wp-backup-bank/" target="_blank" >
																													<?php _e("Visit Website", wpshopmart_accordion_shortcode_text_domain); ?>
																												</a>
																											<?php
																										break;
																									}
																								?>
																							</li>
																						</ul>
																					</div>
																				</div>
																				<div class="plugin-card-bottom plugin-card-bottom_settings">
																					<div class="vers column-rating">
																						<?php wp_star_rating( array( "rating" => $plugin["rating"], "type" => "percent", "number" => $plugin["num_ratings"] ) ); ?>
																						<span class="num-ratings">
																							(<?php echo number_format_i18n( $plugin["num_ratings"] ); ?>)
																						</span>
																					</div>
																					<div class="column-updated">
																						<strong><?php _e("Last Updated:"); ?></strong> <span title="<?php echo esc_attr($plugin["last_updated"]); ?>">
																							<?php printf("%s ago", human_time_diff(strtotime($plugin["last_updated"]))); ?>
																						</span>
																					</div>
																					<div class="column-downloaded">
																						<?php echo sprintf( _n("%s download", "%s downloads", $plugin["downloaded"]), number_format_i18n($plugin["downloaded"])); ?>
																					</div>
																					<div class="column-compatibility">
																						<?php
																						if ( !empty($plugin["tested"]) && version_compare(substr($GLOBALS["wp_version"], 0, strlen($plugin["tested"])), $plugin["tested"], ">"))
																						{
																							echo '<span class="compatibility-untested">' . __( "<strong>Untested</strong> with your version of WordPress" ) . '</span>';
																						} 
																						elseif (!empty($plugin["requires"]) && version_compare(substr($GLOBALS["wp_version"], 0, strlen($plugin["requires"])), $plugin["requires"], "<")) 
																						{
																							echo '<span class="compatibility-incompatible">' . __("Incompatible with your version of WordPress") . '</span>';
																						} 
																						else
																						{
																							echo '<span class="compatibility-compatible">' . __("Compatible with your version of WordPress") . '</span>';
																						}
																						?>
																					</div>
																				</div>
																			</div>
																		<?php
																		}
																		?>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</form>
					</td>
				</tr>
			</table>
		</div>
	</div>
	
   
	
</div>
<!-- /row -->
