<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of ShopFAQ
 *
 * @author kwlok
 */
class ShopFAQ extends SWidget
{
    /**
     * The path alias to access assets
     * @property string
     */
    public $pathAlias = 'shopwidgets.shopfaq.assets';
    /**
     * string the asset name of the widget
     */
    public $assetName = 'shopfaq';
    /**
     * array the questions and answers
     * [
     *   'question1'=>'answer2',
     *   'question2'=>'answer2',
     * ]
     */
    public $QnA = [];
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        $this->render('index');
    }

    public function getFAQItems()
    {
        $items = [];
        foreach ($this->QnA as $question => $answer){
            $items[] =         [
                'label' => Sii::t('sii','Q: ').$question,
                'content' => Sii::t('sii','A: ').$answer,
                // open its content by default
                'contentOptions' => [
 //                   'class' => 'in',
                ]
            ];
        }
        return $items;
    }
}
