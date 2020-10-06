<?php

namespace FluentForm\App\Services\FormBuilder\Components;

use FluentForm\App\Helpers\Helper;
use FluentForm\Framework\Helpers\ArrayHelper;

class SubmitButton extends BaseComponent
{
    /**
     * Compile and echo the html element
     * @param  array $data [element data]
     * @param  stdClass $form [Form Object]
     * @return viod
     */
    public function compile($data, $form)
    {

        if(apply_filters('fluentform_is_hide_submit_btn_'.$form->id, false)) {
            return '';
        }

        $elementName = $data['element'];
        $data = apply_filters('fluenform_rendering_field_data_'.$elementName, $data, $form);

        $btnSize = 'ff-btn-';
        $color = isset($data['settings']['color']) ? $data['settings']['color'] : '#ffffff';
        $btnSize .= isset($data['settings']['button_size']) ? $data['settings']['button_size'] : 'md';
        $backgroundColor = isset($data['settings']['background_color']) ? $data['settings']['background_color'] : '#409EFF';
        $oldBtnType = isset($data['settings']['button_style']) ? '' : ' ff-btn-primary ';

        $align = 'ff-el-group ff-text-' . @$data['settings']['align'];
        $data['attributes']['class'] = trim(
            'ff-btn ff-btn-submit ' . ' ' .
            $oldBtnType . ' ' .
            $btnSize . ' ' .
            $data['attributes']['class']
        );

        if($tabIndex = \FluentForm\App\Helpers\Helper::getNextTabIndex()) {
            $data['attributes']['tabindex'] = $tabIndex;
        }

        $styles = '';
        if (ArrayHelper::get($data, 'settings.button_style') == '') {
            $data['attributes']['class'] .= ' wpf_has_custom_css';
            // it's a custom button
            $buttonActiveStyles = ArrayHelper::get($data, 'settings.normal_styles', []);
            $buttonHoverStyles = ArrayHelper::get($data, 'settings.hover_styles', []);

            $activeStates = '';
            foreach ($buttonActiveStyles as $styleAtr => $styleValue) {
                if (!$styleValue) {
                    continue;
                }
                if ($styleAtr == 'borderRadius') {
                    $styleValue .= 'px';
                }
                $activeStates .= ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '-$0', $styleAtr)), '_') . ':' . $styleValue . ';';
            }
            if ($activeStates) {
                $styles .= 'form.fluent_form_' . $form->id . ' .wpf_has_custom_css.ff-btn-submit { ' . $activeStates . ' }';
            }
            $hoverStates = '';
            foreach ($buttonHoverStyles as $styleAtr => $styleValue) {
                if (!$styleValue) {
                    continue;
                }
                if ($styleAtr == 'borderRadius') {
                    $styleValue .= 'px';
                }
                $hoverStates .= ltrim(strtolower(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '-$0', $styleAtr)), '-') . ':' . $styleValue . ';';
            }
            if ($hoverStates) {
                $styles .= 'form.fluent_form_' . $form->id . ' .wpf_has_custom_css.ff-btn-submit:hover { ' . $hoverStates . ' } ';
            }
        } else {
            $styles .= 'form.fluent_form_' . $form->id . ' .ff-btn-submit { background-color: '.ArrayHelper::get($data, 'settings.background_color').'; color: '.ArrayHelper::get($data, 'settings.color').'; }';
        }

        $atts = $this->buildAttributes($data['attributes']);
        $cls = trim($align . ' ' . $data['settings']['container_class']);


        $html = "<div class='{$cls} ff_submit_btn_wrapper'>";

        // ADDED IN v1.2.6 - updated in 1.4.4
        if (isset($data['settings']['button_ui'])) {
            if ($data['settings']['button_ui']['type'] == 'default') {
                $html .= '<button ' . $atts . '>' . $data['settings']['button_ui']['text'] . '</button>';
            } else {
                $html .= "<button class='ff-btn-submit' type='submit'><img style='max-width: 200px;' src='{$data['settings']['button_ui']['img_url']}' alt='Submit Form'></button>";
            }
        } else {
            $html .= '<button ' . $atts . '>' . $data['settings']['btn_text'] . '</button>';
        }

        if($styles) {
            if(did_action('wp_footer')) {
                $html .= '<style>'.$styles.'</style>';
            } else {
                add_action('wp_footer', function () use ($styles) {
                    echo '<style>'.$styles.'</style>';
                });
            }
        }

        $html .= '</div>';

        echo apply_filters('fluenform_rendering_field_html_'.$elementName, $html, $data, $form);
    }
}
