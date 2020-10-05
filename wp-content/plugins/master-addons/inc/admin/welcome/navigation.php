
<!-- Start tabs -->
<ul class="wp-tab-bar master_addons_navbar">

	<li class="wp-tab-active">
		<a href="#welcome">
			<?php _e( 'Welcome', MELA_TD ); ?>
		</a>
	</li>

    <li>
        <a href="#ma-addons">
			<?php _e( 'Addons', MELA_TD ); ?>
        </a>
    </li>

	<li>
		<a href="#extensions">
			<?php _e( 'Extensions', MELA_TD ); ?>
		</a>
	</li>

	<li>
		<a href="#ma_api_keys">
			<?php _e( 'API', MELA_TD ); ?>
		</a>
	</li> 
	
	<?php //if ( ma_el_fs()->is_plan('developer') ) { ?>
		<!-- <li>
			<a href="#white-labeling">
				<?php //_e( 'Branding', MELA_TD ); ?>
			</a>
		</li> -->
	<?php //} ?>

	<li>
		<a href="#version">
			<?php _e( 'Version', MELA_TD ); ?>
		</a>
	</li>

	<li>
		<a href="#changelogs">
			<?php _e( 'Changelogs', MELA_TD ); ?>
		</a>
	</li>

	<?php if ( ma_el_fs()->is_not_paying() ) { ?>
		<a class="nav-upgrade-pro" href="https://master-addons.com/pricing" target="_blank">
			<?php _e( 'Upgrade Pro', MELA_TD ); ?>
		</a>
	<?php } ?>

</ul>
