<?php

/*
 * commercers.com
 * Commercers Inventory management
 */

class Commercers_Inventorymanagement_Model_Mysql4_Inventorymanagement_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('inventorymanagement/inventorymanagement');
    }
    
    public function addAttributeToFilter($attribute, $condition = null)
    {
        $this->addFieldToFilter($this->_attributeToField($attribute), $condition);
        return $this;
    }
    
    protected function _attributeToField($attribute)
    {
        $field = false;
        if (is_string($attribute)) {
            $field = $attribute;
        } elseif ($attribute instanceof Mage_Eav_Model_Entity_Attribute) {
            $field = $attribute->getAttributeCode();
        }
        if (!$field) {
            Mage::throwException(Mage::helper('inventorymanagement')->__('Cannot determine the field name.'));
        }
        return $field;
    }
    
    protected function _joinFields($from = '', $to = '')
    {
        $this->addAttributeToFilter('created_at' , array("from" => $from, "to" => $to, "datetime" => true))
            ->getSelect()->group('("*")');

        return $this;
    }

    public function setDateRange($from, $to)
    {
        $this->_reset()
            ->_joinFields($from, $to);
        return $this;
    }
    
    public function addAttributeToSort($attribute, $dir = 'asc')
    {
        $this->addOrder($this->_attributeToField($attribute), $dir);
        return $this;
    }
}
?>
