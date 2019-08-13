<?php

/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Block_Adminhtml_Inventorymanagement extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_inventorymanagement';
        $this->_blockGroup = 'inventorymanagement';
        $this->_headerText = Mage::helper('inventorymanagement')->__('Commercers Inventory Management Update Log');
        
        $this->_addButton('run_now', array(
            'label'     => Mage::helper('inventorymanagement')->__('Run now'),
            'onclick'   => 'setLocation(\''.$this->getUrl('*/*/run').'\')',
            'class'     => 'save',
        ), -100);
        
        $this->_addButton('export', array(
            'label'     => Mage::helper('adminhtml')->__('Export'),
            'onclick'   => 'setLocation(\''.$this->getUrl('*/*/export').'\')',
            'class'     => 'save',
        ), -100);
        
        parent::__construct();
        $this->_removeButton('add');
    }
}

?>
