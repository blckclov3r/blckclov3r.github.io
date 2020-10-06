<?php

namespace FluentForm\App\Services\FormBuilder\Components;

class Address extends BaseComponent
{
    /**
     * Wrapper class for address element
     * @var string
     */
	protected $wrapperClass = 'fluent-address';

    /**
     * Compile and echo the html element
     * @param  array $data [element data]
     * @param  stdClass $form [Form Object]
     * @return viod
     */
	public function compile($data, $form)
	{
        $elementName = $data['element'];
        $data = apply_filters('fluenform_rendering_field_data_'.$elementName, $data, $form);

        $rootName = $data['attributes']['name'];
		$hasConditions = $this->hasConditions($data) ? 'has-conditions ' : '';
		$data['attributes']['class'] .= ' ff-name-address-wrapper ' . $this->wrapperClass . ' ' . $hasConditions;
		$data['attributes']['class'] = trim($data['attributes']['class']);
		$atts = $this->buildAttributes(
            \FluentForm\Framework\Helpers\ArrayHelper::except($data['attributes'], 'name')
        );
		ob_start();
		echo "<div {$atts}>";
		if($data['settings']['label']):
			echo "<div class='ff-el-input--label'>";
	        echo "<label>{$data['settings']['label']}</label>";
			echo "</div>";
		endif;
		echo "<div class='ff-el-input--content'>";

        $visibleFields = array_chunk(array_filter($data['fields'], function($field) {
            return $field['settings']['visible'];
        }), 2);

        foreach ($visibleFields as $chunked) {
            echo "<div class='ff-t-container'>";
            foreach ($chunked as $item) {
                if($item['settings']['visible']) {
                    $itemName = $item['attributes']['name'];
                    $item['attributes']['name'] = $rootName.'['.$itemName.']';
                    $item = apply_filters('fluentform_before_render_item', $item, $form);
                    echo "<div class='ff-t-cell'>";
                    do_action('fluentform_render_item_'.$item['element'], $item, $form);
                    echo "</div>";
                }
            }
            echo "</div>";
        }

		echo "</div>";
		echo "</div>";

		$html = ob_get_clean();
        echo apply_filters('fluenform_rendering_field_html_'.$elementName, $html, $data, $form);
    }
}
