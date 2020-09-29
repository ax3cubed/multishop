<div id="shipping_table_container" class="grid-view">
    <?php echo Chtml::dropDownList('Shipping[ids]', '',
                        $model->getShippingsArray(user()->getLocale()),
                        array('prompt'=>'',
                             'class'=>'chzn-select-shipping',
                             'multiple'=>true,
                             'options'=>SActiveSession::loadAsSelected(SActiveSession::CAMPAIGN_BGA_SHIPPING,'shipping_id'),
                             'data-placeholder'=>Sii::t('sii','Select Shipping'),
                             'style'=>'width:100%;'));
    ?>
    <table id="shipping_table" <?php echo SActiveSession::exists(SActiveSession::CAMPAIGN_BGA_SHIPPING)?'class="items"':''; ?>>
        <thead <?php echo SActiveSession::exists(SActiveSession::CAMPAIGN_BGA_SHIPPING)?'':'style="display:none"'; ?> >
            <tr>
                <th width="10%">
                    <?php echo Shipping::model()->getAttributeLabel('status');?>
                </th>        
                <th width="25%">
                    <?php echo Shipping::model()->getAttributeLabel('name');?>
                </th>        
                <th width="15%">
                    <?php echo Shipping::model()->getAttributeLabel('method');?>
                </th>
                <th width="35%">
                    <?php echo Shipping::model()->getAttributeLabel('rate');?>
                </th>
                <th width="15%">
                    <?php echo CampaignShipping::model()->getAttributeLabel('surcharge');?>
                </th>        
            </tr>
        </thead>
        <tbody>
            <?php foreach (SActiveSession::load(SActiveSession::CAMPAIGN_BGA_SHIPPING) as $shipping)
                      $this->renderPartial('_form_shipping',array('model'=>$shipping));
            ?>
        </tbody>
    </table>
</div>
