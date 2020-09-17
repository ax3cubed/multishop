<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
/**
 * Description of SubscriptionController
 *
 * @author kwlok
 */
class SubscriptionController extends BillingBaseController
{
    public function init()
    {
        parent::init();
        $this->modelType = 'Subscription';
    }    
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return [
            'view'=>[
                'class'=>'common.components.actions.ReadAction',
                'model'=>$this->modelType,
                'pageTitleAttribute'=>'subscription_no',
            ],                    
        ];
    }  
    /**
     * Update subscription - mainly change payment method
     */
    public function actionUpdate($shop)
    {
        $subscription = user()->getOnlineSubscription($shop);
        if ($subscription instanceof Subscription){
            if (isset($_POST['Subscription']['payment_token'])){

                $result = $this->module->serviceManager->updateSubscription($subscription,$_POST['Subscription']['payment_token']);
                if (is_bool($result) && $result==true){
                    user()->setFlash($this->modelType,[
                        'message'=>Sii::t('sii','Subscription "{id}" payment card is changed successfully',['{id}'=>$subscription->subscription_no]),
                        'type'=>'success',
                        'title'=>Sii::t('sii','Change Payment Card'),
                    ]);
                }
                else {
                    user()->setFlash($this->modelType,[
                        'message'=>$result,
                        'type'=>'error',
                        'title'=>Sii::t('sii','Change Payment Card'),
                    ]);
                }            
                unset($_POST);
            }
            $this->render('update',['model'=>$subscription]);
            Yii::app()->end();
        }
        
        throwError404('Page not found');
    }
    /**
     * Parse subscription plan details
     */
    public function getPlanDetails($subscription)
    {
        $featureData = function ($key) {
            $data = ['id' => null,'name' => null,'group' => null,];
            $feature = Feature::parseKey($key);
            if (isset($feature[0]))
                $data['id'] = $feature[0];
            if (isset($feature[1]))
                $data['name'] = $feature[1];
            if (isset($feature[2]))
                $data['group'] = $feature[2];
            return $data;
        };
        
        $details = CHtml::openTag('ul');
        foreach ($subscription->permissions as $permission) {
            $data = $featureData($permission->item_name);
            $name = isset(Feature::siiName()[$data['name']]) ? Feature::siiName()[$data['name']] : '';
            $params = json_decode($permission->item_params,true);
            if (strpos($name, '{n}')!==false && isset($params['upperLimit'])){
                $name = Sii::t('sii',$name,['{n}'=>$params['upperLimit']]);
            }
            $group = CHtml::tag('span',['class'=>'group-tag'], Feature::getGroupDesc($data['group']));
            $details .= CHtml::tag('li', [],  $group.$name);
        }
        $details .= CHtml::closeTag('ul');
        
        return [
            ['template'=>'<div class="{class}"><span class="key">{label}</span><div class="plan-rights">{value}</div></div>','label'=>Sii::t('sii','Plan Rights'),'type'=>'raw','value'=>$details],
        ];
    }    
    
}
