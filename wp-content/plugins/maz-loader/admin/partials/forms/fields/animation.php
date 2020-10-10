<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$bind_atts = MZLDR_Helper::getHTMLAttributes( $this->get_field_data( 'bind' ) );
$id = $this->get_field_data( 'id' );
$name = $this->get_field_data( 'name' );
$field_id = $this->get_field_data( 'field_id' );
$animations = MZLDR_Animations_Helper::getAnimations();
$value = $this->get_field_data('value');
?>
<select
	<?php echo $bind_atts; ?>
	<?php if ( ! empty( $field_id ) ) { ?>
	data-field-id="<?php echo esc_attr( $field_id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $id ) ) { ?>
	id="<?php echo esc_attr( $id ); ?>"
	<?php } ?>
	<?php if ( ! empty( $name ) ) { ?>
	name="<?php echo esc_attr( $name ); ?>"
	<?php } ?>
>
	<option value="none"><?php _e('None', 'maz-loader'); ?></option>
<?php foreach ($animations as $a) { ?>
	<optgroup label="<?php echo $a['name']; ?>">
		<?php foreach ($a['items'] as $item) { ?>
			<option value="animated <?php echo $item; ?>"<?php echo ($value == 'animated ' . $item) ? ' selected="selected"' : ''; ?>><?php echo $item; ?></option>
		<?php } ?>
	</optgroup>
<?php } ?>
</select>
