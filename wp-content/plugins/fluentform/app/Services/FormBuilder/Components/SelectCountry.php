<?php

namespace FluentForm\App\Services\FormBuilder\Components;

use FluentForm\App;
use FluentForm\App\Helpers\Helper;
use FluentForm\Framework\Helpers\ArrayHelper;

class SelectCountry extends BaseComponent
{
    /**
     * Compile and echo the html element
     * @param array $data [element data]
     * @param stdClass $form [Form Object]
     * @return viod
     */
    public function compile($data, $form)
    {
        $elementName = $data['element'];
        $data = apply_filters('fluenform_rendering_field_data_' . $elementName, $data, $form);

        $data = $this->loadCountries($data);
        $defaultValues = (array)$this->extractValueFromAttributes($data);
        $data['attributes']['class'] = trim('ff-el-form-control ' . $data['attributes']['class']);
        $data['attributes']['id'] = $this->makeElementId($data, $form);

        if ($tabIndex = \FluentForm\App\Helpers\Helper::getNextTabIndex()) {
            $data['attributes']['tabindex'] = $tabIndex;
        }

        $placeholder = ArrayHelper::get($data, 'attributes.placeholder');

        $activeList = ArrayHelper::get($data, 'settings.country_list.active_list');

        $elMarkup = "<select " . $this->buildAttributes($data['attributes']) . "><option value=''>".$placeholder."</option>";

        if ($activeList == 'priority_based') {
            $selectCountries = ArrayHelper::get($data, 'settings.country_list.priority_based', []);
            $priorityCountries = $this->getSelectedCountries($selectCountries);
            $primaryListLabel = ArrayHelper::get($data, 'settings.primary_label');
            $otherListLabel = ArrayHelper::get($data, 'settings.other_label');
            $elMarkup .= '<optgroup label="'.$primaryListLabel.'">';
            $elMarkup .=  $this->buildOptions($priorityCountries, $defaultValues);
            $elMarkup .= '</optgroup><optgroup label="'.$otherListLabel.'">';
            $elMarkup .=  $this->buildOptions($data['options'], $defaultValues);
            $elMarkup .= '</optgroup>';
        } else {
            $elMarkup .=  $this->buildOptions($data['options'], $defaultValues);
        }

        $elMarkup .= "</select>";

        $html = $this->buildElementMarkup($elMarkup, $data, $form);
        echo apply_filters('fluenform_rendering_field_html_' . $elementName, $html, $data, $form);
    }

    /**
     * Load countt list from file
     * @param array $data
     * @return array
     */
    protected function loadCountries($data)
    {
        $app = App::make();
        $data['options'] = array();
        $activeList = ArrayHelper::get($data, 'settings.country_list.active_list');
        $countries = $app->load($app->appPath('Services/FormBuilder/CountryNames.php'));

        if ($activeList == 'visible_list') {
            $selectCountries = ArrayHelper::get($data, 'settings.country_list.' . $activeList, []);
            foreach ($selectCountries as $value) {
                $data['options'][$value] = $countries[$value];
            }
        } elseif ($activeList == 'hidden_list' || $activeList == 'priority_based') {
            $data['options'] = $countries;
            $selectCountries = ArrayHelper::get($data, 'settings.country_list.' . $activeList, []);
            foreach ($selectCountries as $value) {
                unset($data['options'][$value]);
            }
        } else {
            $data['options'] = $countries;
        }

        return $data;
    }

    /**
     * Build options for country list/select
     * @param array $options
     * @return string/html [compiled options]
     */
    protected function buildOptions($options, $defaultValues = [])
    {
        $opts = '';
        foreach ($options as $value => $label) {
            if (in_array($value, $defaultValues)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $opts .= "<option value='{$value}' {$selected}>{$label}</option>";
        }
        return $opts;
    }

    protected function getSelectedCountries($keys = [])
    {
        $app = App::make();
        $options = [];
        $countries = $app->load($app->appPath('Services/FormBuilder/CountryNames.php'));
        foreach ($keys as $value) {
            $options[$value] = $countries[$value];
        }

        return $options;
    }

}
