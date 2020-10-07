<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<section id="pfhub_portfolio_content_<?php echo esc_attr($portfolioID); ?>"
         style="clear: both"
         class="portfolio-gallery-content <?php if ( $portfolioShowSorting == 'on' ) {
	         echo 'sortingActive ';
         }
         if ( $portfolioShowFiltering == 'on' ) {
	         echo 'filteringActive';
         } ?>"
         data-portfolio-id="<?php echo esc_attr($portfolioID); ?>">
	<div id="pfhub_portfolio-container-loading-overlay_<?php echo esc_attr($portfolioID); ?>"></div>
	<?php if ( ( $sortingFloatFullWidth == 'left' && $filteringFloatFullWidth == 'left' ) || ( $sortingFloatFullWidth == 'right' && $filteringFloatFullWidth == 'right' ) ) { ?>
	<div id="pfhub_portfolio_options_and_filters_<?php echo esc_attr($portfolioID); ?>">
		<?php } ?>
		<?php if ( $portfolioShowSorting == "on" ) { ?>
			<div id="pfhub_portfolio_options_<?php echo esc_attr($portfolioID); ?>"
			     data-sorting-position="<?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view1_sorting_float"]); ?>">
				<ul  class="sort-by-button-group clearfix" >
					<?php if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_default"] != '' ): ?>
						<li><a href="#sortBy=original-order" data-option-value="original-order" class="selected"
						       data><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_default"]); ?></a></li>
					<?php endif; ?>
					<?php if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_id"] != '' ): ?>
						<li><a href="#sortBy=load_date"
						       data-option-value="load_date"><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_id"]); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_name"] != '' ): ?>
						<li><a href="#sortBy=name"
						       data-option-value="name"><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_name"]); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_random"] != '' ): ?>
						<li id="shuffle"><a data-option-value="random"
								href='#shuffle'><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_random"]); ?></a>
						</li>
					<?php endif; ?>
				</ul>
				<ul id="port-sort-direction" class="option-set clearfix" data-option-key="sortAscending">
					<?php if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_asc"] != '' ): ?>
						<li><a href="#sortAscending=true" data-option-value="true" data-option-key="number"
						       class="selected"><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_asc"]); ?></a>
						</li>
					<?php endif; ?>
					<?php if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_desc"] != '' ): ?>
						<li><a href="#sortAscending=false" data-option-key="number"
						       data-option-value="false"><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_name_by_desc"]); ?></a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		<?php }
		if ( $portfolioShowFiltering == "on" ) { ?>
			<div id="pfhub_portfolio_filters_<?php echo esc_attr($portfolioID); ?>"
			     data-filtering-position="<?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view1_filtering_float"]); ?>">
				<ul>
					<li rel="*"><a><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view3_cat_all"]); ?></a></li>
					<?php
					$portfolioCats = explode( ",", $portfolioCats );
					foreach ( $portfolioCats as $portfolioCatsValue ) {
						if ( ! empty( $portfolioCatsValue ) ) {
							?>
							<li rel=".<?php echo str_replace( " ", "_", $portfolioCatsValue ); ?>">
								<a><?php echo str_replace( "_", " ", $portfolioCatsValue ); ?></a></li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		<?php } ?>
		<?php if ( ( $sortingFloatFullWidth == 'left' && $filteringFloatFullWidth == 'left' ) || ( $sortingFloatFullWidth == 'right' && $filteringFloatFullWidth == 'right' ) ) { ?>
	</div>
<?php } ?>
	<div id="pfhub_portfolio_container_<?php echo esc_attr($portfolioID); ?>"
	     data-show-loading="<?php echo esc_attr($portfolioShowLoading); ?>"
	     data-show-center="<?php echo esc_attr($portfolioposition); ?>"
	     class="pfhub_portfolio_container super-list variable-sizes clearfix view-<?php echo $view_slug; ?>" <?php if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_sorting_float"] == "top" && $pfhub_portfolio_get_options["pfhub_portfolio_view3_filtering_float"] == "top" ) {
		echo "style='clear: both;'";
	} ?>>
		<?php
		$group_key1 = 0;
		foreach ( $images as $key => $row ) {
			$group_key1 ++;
			$group_key    = (string) $group_key1;
			$portfolioID1 = (string) $portfolioID;
			$group_key    = $group_key . "-" . $portfolioID;
			$link         = $row->media_url;
			$catForFilter = explode( ",", $row->category );
			?>
			<div
				class="portelement portelement_<?php echo esc_attr($portfolioID); ?> colorbox_grouping  <?php foreach ( $catForFilter as $catForFilterValue ) {
					echo str_replace( " ", "_", $catForFilterValue ) . " ";
				} ?>" data-symbol="<?php echo esc_attr( $row->name ); ?>" data-category="alkaline-earth">
				<div class="left-block_<?php echo $portfolioID; ?>">
					<div class="main-image-block_<?php echo $portfolioID; ?> add-H-relative">
                        <p style="display:none;" class="load_date"><?php echo esc_attr( $row->publish_date ); ?></p>
                        <p style="display:none;" class="number"><?php echo esc_attr($row->id ); ?></p>
						<?php $imgurl = explode( ";", $row->image_url ); ?>
						<?php
						if ( $row->image_url != ';' ) {
							switch ( \PfhubPortfolio\Helpers\GridHelper::getVideoType( $imgurl[0] ) ) {
								case 'image':

									?>
									<a href="<?php echo esc_url( $imgurl[0] ); ?>"
									   class=" portfolio-group<?php echo esc_attr($group_key); ?>"
                                       data-description=" <?php echo esc_attr( $row->description ); ?>"
									   title="<?php echo esc_attr( $row->name ); ?>" data-groupID="<?php echo esc_attr($group_key);?>"><img
											alt="<?php echo esc_attr( $row->name ); ?>"
											id="wd-cl-img<?php echo esc_attr($key); ?>"
                                            data-title=" <?php echo \PfhubPortfolio\Helpers\GridHelper::getImageTitle($imgurl[0]); ?>"
											src="<?php echo esc_url( \PfhubPortfolio\Helpers\GridHelper::getImage( $imgurl[0], array(
												$pfhub_portfolio_get_options['pfhub_portfolio_view3_mainimage_width'],
												''
											), false ) ); ?>"></a>
									<?php
									break;
								case 'youtube':
									$videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId( $imgurl[0] ); ?>
									<a href="https://www.youtube.com/embed/<?php echo esc_attr($videourl[0]); ?>"
                                       data-description=" <?php echo esc_attr( $row->description ); ?>"
									   class="pfhub_portfolio_item pyoutube portfolio-group<?php echo esc_attr($group_key); ?> add-H-block"
									   title="<?php echo esc_attr( $row->name ); ?>" data-groupID="<?php echo esc_attr($group_key);?>">
										<img
											src="//img.youtube.com/vi/<?php echo esc_attr($videourl[0]); ?>/mqdefault.jpg">
										<div class="play-icon <?php echo esc_attr($videourl[1]); ?>-icon"></div>
									</a>

									<?php break;
								case 'vimeo':
									$videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId( $imgurl[0] );
									$hash = unserialize( wp_remote_fopen( "https://vimeo.com/api/v2/video/" . $videourl[0] . ".php" ) );
									$imgsrc = $hash[0]['thumbnail_large']; ?>
									<a class="pfhub_portfolio_item pvimeo portfolio-group<?php echo esc_attr($group_key); ?> add-H-block"
									   href="http://player.vimeo.com/video/<?php echo esc_attr($videourl[0]); ?>"
                                       data-description=" <?php echo esc_attr( $row->description ); ?>"
									   title="<?php echo esc_attr( $row->name ); ?>" data-groupID="<?php echo esc_attr($group_key);?>">
										<img src="<?php echo esc_attr( $imgsrc ); ?>"
										     alt="<?php echo esc_attr( $row->name ); ?>"/>
										<div class="play-icon <?php echo esc_attr($videourl[1]); ?>-icon"></div>
									</a>
									<?php
							}
						} else { ?>
							<a href="<?php echo esc_url( $imgurl[0] ); ?>"
							   class=" portfolio-group<?php echo esc_attr($group_key); ?>" data-groupID="<?php echo esc_attr($group_key);?>"><img
									alt="<?php echo esc_attr( $row->name ); ?>"
									id="wd-cl-img<?php echo esc_attr($key); ?>" src="images/noimage.jpg"></a>
							<?php
						}
						?>
					</div>
					<div class="thumbs-block">
						<?php
						if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_show_thumbs"] == 'on' ) {
							?>
							<ul class="thumbs-list_<?php echo esc_attr($portfolioID); ?>">
								<?php
								$imgurl             = explode( ";", $row->image_url );
								array_pop( $imgurl );
								array_shift( $imgurl );

								foreach ( $imgurl as $key => $img ) {
									switch ( \PfhubPortfolio\Helpers\GridHelper::getVideoType( $img ) ) {
										case 'image':
											?>
											<li><a href="<?php echo esc_url( $img ); ?>" data-description=" <?php echo esc_attr( $row->description ); ?>"
											       class=" portfolio-group<?php echo esc_attr($group_key); ?> " data-groupID="<?php echo esc_attr($group_key);?>"><img alt="<?php echo esc_attr( $row->name ); ?>"
                                                  data-title=" <?php echo \PfhubPortfolio\Helpers\GridHelper::getImageTitle($img); ?>"
														src="<?php echo esc_url( \PfhubPortfolio\Helpers\GridHelper::getImage( $img, array(
															$pfhub_portfolio_get_options['pfhub_portfolio_view3_thumbs_width'],
															$pfhub_portfolio_get_options['pfhub_portfolio_view3_thumbs_height']
														), true ) ); ?>"></a>
											</li>
											<?php break;
										case 'youtube':
											$videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId( $img ); ?>
											<li>
												<a href="https://www.youtube.com/embed/<?php echo esc_attr($videourl[0]); ?>"
												   class="pfhub_portfolio_item pyoutube portfolio-group<?php echo $group_key; ?>  add-H-relative"
                                                   data-description=" <?php echo esc_attr( $row->description ); ?>"
												   title="<?php echo esc_attr( $row->name ); ?>" data-groupID="<?php echo esc_attr($group_key);?>">
													<img alt="<?php echo esc_attr($row->name); ?>"
														src="//img.youtube.com/vi/<?php echo esc_attr($videourl[0]); ?>/mqdefault.jpg">
													<div
														class="play-icon <?php echo esc_attr($videourl[1]); ?>-icon"></div>
												</a>
											</li>
											<?php
											break;
										case 'vimeo':
											$videourl = \PfhubPortfolio\Helpers\GridHelper::getVideoId( $img );
											$hash   = unserialize( wp_remote_fopen( "https://vimeo.com/api/v2/video/" . $videourl[0] . ".php" ) );
											$imgsrc = $hash[0]['thumbnail_large']; ?>
											<li>
												<a class="pfhub_portfolio_item pvimeo portfolio-group<?php echo esc_attr($group_key); ?>  add-H-relative"
												   href="http://player.vimeo.com/video/<?php echo esc_attr($videourl[0]); ?>"
                                                   data-description=" <?php echo esc_attr( $row->description ); ?>"
												   title="<?php echo esc_attr( $row->name ); ?>" data-groupID="<?php echo esc_attr($group_key);?>">
													<img src="<?php echo esc_attr( $imgsrc ); ?>"
													     alt="<?php echo esc_attr( $row->name ); ?>"/>
													<div
														class="play-icon <?php echo esc_attr($videourl[1]); ?>-icon"></div>
												</a>
											</li>
											<?php
											break;
									}

								}
								?>
							</ul>
							<?php
						}
						?>
					</div>
				</div>
				<div class="right-block">
					<?php if ( $row->name != '' ) { ?>
					<div class="title-block_<?php echo esc_attr( $portfolioID); ?>">
						<h3 class="name" ><?php echo $row->name; ?></h3></div><?php } ?>
					<?php
					if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_show_description"] == 'on' ) {
						if ( $row->description != '' ) { ?>
							<div class="description-block_<?php echo esc_attr($portfolioID); ?>">
								<p><?php echo $row->description ; ?></p></div>
						<?php } ?>
					<?php }

					if ( $link != '' ) {
						if ( $pfhub_portfolio_get_options["pfhub_portfolio_view3_show_linkbutton"] == 'on' && $pfhub_portfolio_get_options["pfhub_portfolio_view3_linkbutton_text"] != '' && $link != '' ) {
							?>
							<div class="button-block">
								<a href="<?php echo esc_url( $link ); ?>" <?php if ( $row->link_target == "on" ) {
									echo 'target="_blank"';
								} ?>><?php echo esc_attr($pfhub_portfolio_get_options["pfhub_portfolio_view3_linkbutton_text"]); ?></a>
							</div>
						<?php }
					} ?>
				</div>
			</div>

			<?php
		}
		?>

	</div>
</section>
