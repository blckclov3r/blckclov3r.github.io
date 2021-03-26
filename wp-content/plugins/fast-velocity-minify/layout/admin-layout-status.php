<?php 

# server info
if( $active_tab == 'status' ) { 
?>
<div class="fvm-wrapper">

<div id="status">
<h2 class="title"><?php _e( 'Cache Stats', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-cache-stats fvm-bold-green"></h3>


<div style="height: 40px;"></div>
<h2 class="title"><?php _e( 'CSS Logs', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'In this section, you can check the latest CSS merging logs', 'fast-velocity-minify' ); ?></h3>
<textarea rows="10" cols="50" class="large-text code row-log log-css" disabled></textarea>


<div style="height: 40px;"></div>
<h2 class="title"><?php _e( 'JS Logs', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'In this section, you can check the latest JS merging logs', 'fast-velocity-minify' ); ?></h3>
<textarea rows="10" cols="50" class="large-text code row-log log-js" disabled></textarea>
<div style="height: 20px;"></div>


<div style="height: 40px;"></div>
<h2 class="title"><?php _e( 'Server Info', 'fast-velocity-minify' ); ?></h2>
<h3 class="fvm-bold-green"><?php _e( 'In this section, you can check some server stats and information', 'fast-velocity-minify' ); ?></h3>
<textarea rows="10" cols="50" class="large-text code row-log" disabled><?php fvm_get_generalinfo(); ?></textarea>


<script>fvm_get_logs();</script>
</div>

</div>
<?php 

}