<?php

class Commercers_Inventorymanagement_Block_Adminhtml_Catalog_Product_Tabs extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs
{
    private $parent;
 
    protected function _prepareLayout()
    {
        $this->parent = parent::_prepareLayout();
        $this->addTab('transactions', array(
                    'label'     => Mage::helper('catalog')->__('Inventory management'),
                    'title'     => Mage::helper('inventorymanagement')->__('Inventory management'),
                    'url'       => $this->getUrl('*/*/transactions', array('_current' => true)),
                    'class'     => 'ajax',
        ));
        return $this->parent;
    }
}
?>
