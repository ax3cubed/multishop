<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of MerchantController
 *
 * @author kwlok
 */
class MerchantController extends SPageIndexController 
{
    public function init()
    {
        parent::init();
        //-----------------
        // SPageIndex Configuration
        // @see SPageIndexController
        $this->modelType = 'Activity';
        $this->pageControl = SPageIndex::CONTROL_ARROW;
        $this->viewName = Sii::t('sii','Activities');
        $this->enableViewOptions = false;
        $this->enableSearch = false;
        //-----------------//
        $this->defaultScope = 'operational';
        $this->route = 'activities/merchant/index';
    }
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeFilters()
    {
        $filters = new CMap();
        $filters->add('operational',Helper::htmlIndexFilter(Sii::t('sii','All'), false));
        $filters->add('order',Helper::htmlIndexFilter(Sii::t('sii','Order'), false));
        $filters->add('item',Helper::htmlIndexFilter(Sii::t('sii','Item'), false));
        $filters->add('like',Helper::htmlIndexFilter(Sii::t('sii','Like'), false));
        $filters->add('comment',Helper::htmlIndexFilter(Sii::t('sii','Comment'), false));
        $filters->add('question',Helper::htmlIndexFilter(Sii::t('sii','Question'), false));
        return $filters->toArray();
    }
    /**
     * OVERRIDE METHOD
     * @see SPageIndexController
     * @return array
     */
    public function getScopeDescription($scope)
    {
        switch ($scope) {
            case 'operational':
                return Sii::t('sii','This lists all your activity records.');
            case 'order':
                return Sii::t('sii','This lists all your activity records related to orders.');
            case 'item':
                return Sii::t('sii','This lists all your activity records related to items.');
            case 'question':
                return Sii::t('sii','This lists all your activity records related to questions.');
            case 'like':
                return Sii::t('sii','This lists all your activity records related to likes.');
            case 'comment':
                return Sii::t('sii','This lists all your activity records related to comments.');
            case 'question':
                return Sii::t('sii','This lists all your activity records related to questions.');
            default:
                return null;
        }
    }        
}