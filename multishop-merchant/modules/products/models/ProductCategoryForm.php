<?php
/**
 * This file is part of Multishop.org (http://multishop.org)
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code. 
 */

/**
 * Description of ProductCategoryForm
 *
 * @author kwlok
 */
class ProductCategoryForm extends LanguageChildForm 
{
    private $_c;//category model instance
    private $_s;//subcategory model instance
    /*
     * Inherited attributes
     */
    public $model = 'ProductCategory';    
    /**
     * Controlled attributes
     * @see LanguageChildForm
     */
    public $keyAttribute = 'product_id';    
    protected $persistentAttributes = array();    
    /*
     * Local attributes
     */    
    public $product_id;
    public $category_id;
    public $subcategory_id;
    public $create_time;
    /**
     * locale attributes
     */
    public function localeAttributes() 
    {
        return array();//this form has no locale attributes
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
        return array(
            array('product_id, category_id', 'required'),
            array('id, product_id, category_id, subcategory_id', 'numerical', 'integerOnly'=>true),
        );
    }    
    /**
     * If $this->category_id has value, it try load underlying model from db
     * @return Category model
     */
    public function getCategory() 
    {
        if (isset($this->category_id)){
            if (!isset($this->_c))
                $this->_c = Category::model()->findByPk($this->category_id);
            return $this->_c;
        }
        else 
            return null;
    } 
    /**
     * If $this->category_id has value, it try load underlying model from db
     * @return Category model
     */
    public function getSubcategory() 
    {
        if (isset($this->subcategory_id)){
            if (!isset($this->_s))
                $this->_s = CategorySub::model()->findByPk($this->subcategory_id);
            return $this->_s;
        }
        else 
            return null;
    }      
}
