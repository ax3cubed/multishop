<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ShopThemeSettingForm
 *
 * @author kwlok
 */
class ShopThemeSettingForm extends CFormModel  
{
    public $id;
    public $name;//name in locale array form
    public $fields = [];
    
    public function getName($locale)
    {
        return $this->getLabel($this->name, $locale);
    }
    
    public function render($locale)
    {
        $html = '';
        foreach ($this->fields as $field) {
            switch ($field['type']) {
                case 'checkbox':
                    $html .= $this->renderCheckbox($field, $locale);
                    break;
                case 'select':
                    $html .= $this->renderDropdownList($field, $locale);
                    break;
                default:
                    break;
            }
        }
        return $html;
    }
    
    protected function renderCheckbox($field,$locale)
    {
        $html  = CHtml::openTag('div');
        $html .= $this->getLabel($field['name'], $locale);
        $html .= CHtml::checkBox($this->getFieldName($field['id']),$field['value'],['uncheckValue'=>0]);
        if (isset($field['remarks']))
            $html .= CHtml::tag('div',['class'=>'remarks'],$this->getLabel($field['remarks'], $locale));
        $html .= CHtml::closeTag('div');
        return $html;
    }
    
    protected function renderDropdownList($field,$locale)
    {
        $options = [];
        foreach ($field['options'] as $option) {
            $options[$option['value']] = $this->getLabel($option['label'],$locale);
        }
        $html  = CHtml::openTag('div');
        $html .= $this->getLabel($field['name'], $locale);
        $html .= CHtml::dropDownList($this->getFieldName($field['id']),$field['value'],$options);
        if (isset($field['remarks']))
            $html .= CHtml::tag('div',['class'=>'remarks'],$this->getLabel($field['remarks'], $locale));
        $html .= CHtml::closeTag('div');
        return $html;
    }
    
    protected function getFieldName($field)
    {
        return get_class($this).'['.$this->id.']['.$field.']';
    }
    
    protected function getLabel($label,$locale)
    {
        return isset($label[$locale]) ? $label[$locale] : $label[param('LOCALE_DEFAULT')] ;
    }
    
    protected function getRemarks($remarks,$locale)
    {
        return isset($remarks[$locale]) ? $remarks[$locale] : $remarks[param('LOCALE_DEFAULT')] ;
    }    
}
