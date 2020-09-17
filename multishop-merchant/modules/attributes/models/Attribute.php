<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */
Yii::import("common.components.validators.CompositeUniqueKeyValidator");
/**
 * This is the model class for table "s_attribute".
 *
 * The followings are the available columns in table 's_attribute':
 * @property integer $id
 * @property integer $account_id
 * @property string $obj_type
 * @property string $code
 * @property string $name
 * @property integer $type
 * @property integer $create_time
 * @property integer $update_time
 *
 * The followings are the available model relations:
 * @property Account $account
 * @property AttributeOption[] $attributeOptions
 * @property ProductAttribute[] $productAttributes
 *
 * @author kwlok
 */
class Attribute extends SActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Attribute the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    /**
     * Model display name 
     * @param $mode singular or plural, if the language supports, e.g. english
     * @return string the model display name
     */
    public function displayName($mode=Helper::SINGULAR)
    {
        return Sii::t('sii','Attribute|Attributes',array($mode));
    } 
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 's_attribute';
    }
    /**
     * Behaviors for this model
     */
    public function behaviors()
    {
        return array(
            'timestamp' => array(
                'class'=>'common.components.behaviors.TimestampBehavior',
            ),
            'account' => array(
                'class'=>'common.components.behaviors.AccountBehavior',
            ),
            'activity' => array(
                'class'=>'common.modules.activities.behaviors.ActivityBehavior',
                'iconUrlSource'=>'account',
            ),
            'locale' => array(
                'class'=>'common.components.behaviors.LocaleBehavior',
                'ownerParent'=>'account',
                'localeAttribute'=>'profileLocale',
            ),          
        );
   }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('account_id, obj_type, code, name, type', 'required'),
            array('account_id, type', 'numerical', 'integerOnly'=>true),
            array('obj_type', 'length', 'max'=>20),
            array('code', 'length', 'max'=>2),
            array('name', 'length', 'max'=>100),
            //validate options 
            array('id', 'ruleOptions'),
            //create scenario
            array('code', 'CompositeUniqueKeyValidator', 'keyColumns' => 'account_id, obj_type, code','errorMessage'=>Sii::t('sii','Code is already taken'),'on'=>'create'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, account_id, obj_type, code, name, type, create_time, update_time', 'safe', 'on'=>'search'),
        );
    }
    /**
     * Validate options
     */
    public function ruleOptions($attribute,$params) 
    {
       $validateFields = array('name','code');
       foreach ($this->options as $option) {
            if (!$option->validate($validateFields)){
                foreach ($validateFields as $field){
                    if ($option->hasErrors($field))
                        $this->addError('id',$option->getError($field));//use id as proxy
                }
            }
       }//end for loop
    }    

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'account' => array(self::BELONGS_TO, 'Account', 'account_id'),
            'options' => array(self::HAS_MANY, 'AttributeOption', 'attr_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => Sii::t('sii','ID'),
            'account_id' => Sii::t('sii','Account'),
            'obj_type' => Sii::t('sii','Class'),
            'code' => Sii::t('sii','Code'),
            'name' => Sii::t('sii','Name'),
            'type' => Sii::t('sii','Type'),
            'create_time' => Sii::t('sii','Create Time'),
            'update_time' => Sii::t('sii','Update Time'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('account_id',$this->account_id);
        $criteria->compare('obj_type',$this->obj_type,true);
        $criteria->compare('code',$this->code,true);
        $criteria->compare('name',$this->name,true);
        $criteria->compare('type',$this->type);
        $criteria->compare('create_time',$this->create_time);
        $criteria->compare('update_time',$this->update_time);

        return new CActiveDataProvider($this->mine(), array(
                'criteria'=>$criteria,
        ));
    }

    public function searchAttributeUsage()
    {
        $criteria=new CDbCriteria;
        $criteria->addColumnCondition(array('code'=>$this->code));
        return new CActiveDataProvider('ProductAttribute', array(
                'criteria'=>$criteria,
        ));
    }

    public function searchOptions()
    {
        $criteria=new CDbCriteria;
        $criteria->addColumnCondition(array('attr_id'=>$this->id));
        return new CActiveDataProvider('AttributeOption', array(
                'criteria'=>$criteria,
        ));
    }
    /**
     * Insert options, validation has to be done first before calling this method
     */
    public function insertOptions()
    {
        foreach ($this->options as $option){
            unset($option->id);//set id to null to have auto increment key
            $option->attr_id = $this->id;
            $option->insert();
        } 
    }
    /**
     * Update options, validation has to be done first before calling this method
     */
    public function updateOptions()
    {
       $deleteExcludeList = new CList();
       foreach ($this->options as $option){
            $found = AttributeOption::model()->findByPk($option->id);
            if ($found==null){//record not found
                $_o = new AttributeOption();//$option->id is auto incremented value in db
                $_o->attr_id=$this->id;
                $_o->attributes = $option->getAttributes(array('name','code'));
                $_o->insert();
                $deleteExcludeList->add($_o->id);
                logTrace('attribute option created successfully',$_o->getAttributes());
            }
            else{
                $found->attributes = $option->getAttributes(array('name','code'));
                $found->update();
                $deleteExcludeList->add($found->id);
                logTrace('attribute option updated successfully',$found->getAttributes());
            }
       } 
        $this->deleteOptions($deleteExcludeList->toArray());
    }
    public function deleteOptions($excludes=array())
    {
        $criteria = new CDbCriteria();
        $criteria->addColumnCondition(array('attr_id'=>$this->id));
        if (empty($excludes)){
            if ($this->type==self::TYPE_SELECT){
                AttributeOption::model()->deleteAll($criteria);
            }            
        }
        else {
            $criteria->addNotInCondition('id',$excludes);
            logTrace('delete options',$criteria);
            foreach (AttributeOption::model()->findAll($criteria) as $unwanted){
               try {
                    //delete db record
                    logTrace('delete unwanted'.$unwanted->id,$unwanted->getAttributes());
                    $unwanted->delete();
                } catch (CException $e) {
                    logError('unwanted delete error ',$e->getTrace());
                }
            }
        }
    }

    public function getObjectTypes()
    {
        return array(
            Product::model()->tableName() => 'Product',
            //CampaignBga::model()->tableName() => 'Campaign',
        );
    }    
    public function getObjectTypeText()
    {
        $types = $this->getObjectTypes();
        return $types[$this->obj_type];
    } 

    const TYPE_SELECT     = 1;
    const TYPE_TEXTFIELD  = 2;
    public function getTypes()
    {
        return array(
            self::TYPE_SELECT => Sii::t('sii','Select'),
            self::TYPE_TEXTFIELD => Sii::t('sii','Textfield'),
        );
    }    

    public function getTypeText()
    {
        $types = $this->getTypes();
        return $types[$this->type];
    } 

    public function getOptionsText()
    {
        if ($this->type==self::TYPE_SELECT){
            $text = '';
            foreach ($this->searchOptions()->data as $data) {
                $text .= $data->code.' - '.$data->name;
                $text .= '<br>';
            }
            return $text;
        }
        else
            return Sii::t('sii','NA');
    }  
    /**
     * Url to view this model
     * @return string url
     */
    public function getViewUrl()
    {
        return url('attribute/view/'.$this->id);
    }     
    
}