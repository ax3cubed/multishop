<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import('common.modules.news.behaviors.NewsBehavior');
/**
 * Description of NewsForm
 *
 * @author kwlok
 */
class NewsForm extends LanguageForm 
{
    /*
     * Inherited attributes
     */
    public $model = 'News';
    /*
     * Local attributes
     */
    public $headline;
    public $content;
    public $status;
    public $create_time;
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(),array(
            'newsbehavior' => array(
                'class'=>'common.modules.news.behaviors.NewsBehavior',
            ),            
        ));
    }    
    /**
     * @return array Definitions of form attributes that requires multi languages
     */       
    public function localeAttributes()
    {
        return array(
            'headline'=>array(
                'required'=>true,
                'inputType'=>'textField',
                'inputHtmlOptions'=>array('size'=>70,'maxlength'=>100),
            ),
            'content'=>array(
                'required'=>true,
                'purify'=>true,
                'inputType'=>'textArea',
                'inputHtmlOptions'=>array('cols'=>45,'rows'=>5,'maxlength'=>1000),
            ),
        );
    }    
    /**
     * Validation rules for locale attributes
     * 
     * Note: that all different locale values of one attributes are to be stored in db table column
     * Hence, model attribute (table column) wil have separate validation rules following underlying table definition
     * 
     * @return array validation rules for locale attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(),array(
            array('headline, content', 'required'),
            array('headline', 'length', 'max'=>100),
            array('content', 'length', 'max'=>1000),
            array('status', 'length', 'max'=>20),
            array('headline','rulePurify'),
            array('content','rulePurify'),
        ));
    }    
    /**
     * Return status text
     * @param type $color
     * @return type
     */
    public function getStatusText($color=true)
    {
        return $this->modelInstance->getStatusText($color);
    }
    /**
     * @return array customized attribute tooltips (name=>label)
     */
    public function attributeToolTips()
    {
        return array_merge([
            'content'=>Sii::t('sii','You can use Markdown language to format news.'),
        ],parent::attributeToolTips());
    }
}
