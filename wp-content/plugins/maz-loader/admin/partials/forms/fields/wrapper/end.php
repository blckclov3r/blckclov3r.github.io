<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
			<?php if ( ! empty( $this->get_field_data( 'description' ) ) ) { ?>
			<div class="mzldr-control-description">
				<?php
				echo wp_kses(
					$this->get_field_data( 'description' ),
					array(
						'br'     => [],
						'strong' => [],
						'a'		 => [
							'href' => [],
							'target' => []
						]
					)
				);
				?>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
