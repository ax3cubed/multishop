<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of CommunityUrlRules
 *
 * @author kwlok
 */
class CommunityUrlRules 
{
    /**
     * @property array the community rules to be loaded into SUrlManager
     */
    public static $rules = [
        'community/topics/<topic>' => 'community/topics/search',
        'community/tutorials/series'=>'community/tutorialSeries/index',
        'community/tutorials/series/<slug>' => 'community/tutorialSeries/view',
        'community/tutorials/<slug>' => 'community/tutorials/view',
        'community/questions/<slug>' => 'community/questions/view',        
        'community/<controller>/index/*'=>'community/<controller>/index',
        'community/<controller>/<action:\w+>'=>'community/<controller>/<action>',        
    ];
}
