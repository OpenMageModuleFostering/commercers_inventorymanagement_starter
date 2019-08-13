<?php
class Commercers_Inventorymanagement_Block_Adminhtml_Catalog_Product_Renderer_Translate extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        return Mage::helper('inventorymanagement')->__($value);
    }
}
?>
