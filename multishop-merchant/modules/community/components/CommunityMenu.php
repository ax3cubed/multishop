<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.widgets.susermenu.components.*");
/**
 * Description of CommunityMenu
 * 
 * @author kwlok
 */
class CommunityMenu extends SUserMenu
{
    /**
     * Run widget
     * @throws CException
     */
    public function run()
    {
        if (!isset($this->user))
            throw new CException(__CLASS__." User cannot be null");
                
        $this->renderMenu('CommunityMenuContent', $this->offCanvas);
    }
}
/**
 * Description of CommunityMenuContent
 * 
 * @author kwlok
 */
class CommunityMenuContent extends UserMenu 
{
    public static $communityRoute = 'community';//for community pages
    public static $tutorials      = 'tutorials';
    public static $series         = 'series';
    public static $questions      = 'questions';
    public static $topics         = 'topics';
    /**
     * Constructor
     */
    public function __construct($user,$config=[]) 
    {
        $this->user = $user;
        $this->loadConfig($config);
        
        $this->items[static::$tutorials] = new UserMenuItem([
            'id'=> static::$tutorials,
            'label'=>Sii::t('sii','Tutorials'),
            'icon'=>'<i class="fa fa-file-text-o"></i>',
            'iconDisplay'=>$this->iconDisplay,
            'iconPlacement'=>$this->iconPlacement,
            'url'=>url('community/tutorials'),
            'cssClass'=>'community-menu '.static::$tutorials,
            'active'=>$this->isMenuActive(['community/tutorials/index','community/tutorials/view']),
        ]);
        $this->items[static::$series] = new UserMenuItem([
            'id'=> static::$series,
            'label'=>Sii::t('sii','Tutorial Series'),
            'icon'=>'<i class="fa fa-files-o"></i>',
            'iconDisplay'=>$this->iconDisplay,
            'url'=>url('community/tutorials/series'),
            'cssClass'=>'community-menu '.static::$series,
            'active'=>$this->isMenuActive(['community/tutorialSeries/index','community/tutorialSeries/view']),
        ]);
        $this->items[static::$questions] = new UserMenuItem([
            'id'=> static::$questions,
            'label'=>Sii::t('sii','Questions'),
            'icon'=>'<i class="fa fa-question-circle"></i>',
            'iconDisplay'=>$this->iconDisplay,
            'url'=>url('community/questions'),
            'cssClass'=>'community-menu '.static::$questions,
            'active'=>$this->isMenuActive(['community/questions/index','community/questions/view']),
        ]);        
        $this->items[static::$topics] = new UserMenuItem([
            'id'=> static::$topics,
            'label'=>Sii::t('sii','Topics'),
            'icon'=>'<i class="fa fa-tags"></i>',
            'iconDisplay'=>$this->iconDisplay,
            'url'=>url('community/topics'),
            'cssClass'=>'community-menu '.static::$topics,
            'active'=>$this->isMenuActive(['community/topics/index','community/topics/search']),
        ]);        
        
        $langMenu = new LangMenu($user);
        
        if ($user->isAuthenticated){
            if ($this->offCanvas){
                $loginMenu = new LoginMenu($user);
                $this->items = array_merge($this->items,$loginMenu->items,$langMenu->items);
            }
            else {
                $welcomeMenu = new WelcomeMenu($user,['offCanvas'=>$this->offCanvas]);
                $this->items = array_merge($this->items,$langMenu->items,$welcomeMenu->items);
            }
        }
        else {
            $siteMenu = new SiteMenu($user, false, [
                'signinScript'=>'redirect("'.app()->urlManager->createHostUrl().'/signin?returnUrl='.app()->urlManager->createCommunityUrl().'");',
                'signupScript'=>'redirect("'.app()->urlManager->createHostUrl().'/signup");',
                'iconDisplay'=>$this->iconDisplay,
                'signupLabel'=>Sii::t('sii','Sign up'),
            ]);
            $this->items = array_merge($this->items,$siteMenu->items,$langMenu->items);
        }
        
    }  
    
    public function getMobileButton()
    {
        $button = CHtml::openTag('div',['class'=>'mobile-button mobile-community']);
        
        if (!$this->user->isGuest)
            $button .= CHtml::link(CHtml::tag('i',['class'=>'fa avatar'],$this->user->getAvatar(Image::VERSION_XXSMALL)),'javascript:void(0);',['onclick'=>'openoffcanvascommunitymenu();']);
        else
            $button .= CHtml::link('<i class="fa fa-navicon"></i>','javascript:void(0);',['onclick'=>'openoffcanvascommunitymenu();']);
        
        $button .= CHtml::closeTag('div');
        return $button;        
    }
}