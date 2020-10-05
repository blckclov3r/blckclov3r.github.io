<?php
/**
 * Template Library Filter Item
 */
?>
<label class="premium-template-filter-label">
	<input type="radio" value="{{ slug }}" <# if ( '' === slug ) { #> checked<# } #> name="premium-template-filter">
	<span>{{ title.replace('&amp;', '&') }}</span>
</label>