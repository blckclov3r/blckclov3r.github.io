<?php
/**
 * Template Insert Button
 */
?>
<# if ( 'valid' === window.PremiumTempsData.license.status || ! pro ) { #>
    <button class="elementor-template-library-template-action premium-template-insert elementor-button elementor-button-success">
        <i class="eicon-file-download"></i><span class="elementor-button-title"><?php
            echo __( 'Insert', 'premium-addons-for-elementor' );
        ?></span>
    </button>
<# } else { #>
<a class="template-library-activate-license elementor-button elementor-button-go-pro" href="{{{ window.PremiumTempsData.license.activateLink }}}" target="_blank">
    <i class="fa fa-external-link" aria-hidden="true"></i>
    {{{ window.PremiumTempsData.license.proMessage }}}
</a>
<# } #>