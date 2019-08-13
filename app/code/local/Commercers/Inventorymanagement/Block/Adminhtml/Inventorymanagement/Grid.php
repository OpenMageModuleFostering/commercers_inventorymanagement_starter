<?php

/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Block_Adminhtml_Inventorymanagement_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('inventorymanagementGrid');
        $this->setDefaultSort('history_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
 
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('inventorymanagement/inventorymanagement')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    protected function _prepareColumns()
    {
        $this->addColumn('history_id', array(
            'header'    => Mage::helper('adminhtml')->__('ID'),
            'align'     =>'right',
            'width'     => '30px',
            'index'     => 'history_id',
        ));
 
        $this->addColumn('date', array(
            'header'    => Mage::helper('inventorymanagement')->__('Date, time'),
            'align'     =>'left',
            'width'     => '70px',
            'type'      => 'datetime',
            'index'     => 'date',
        ));
  
        $this->addColumn('execution', array(
            'header'    => Mage::helper('inventorymanagement')->__('Execution'),
            'align'     => 'left',
            'width'     => '120px',
            'index'     => 'execution',
        ));
        
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return false;
    }
 
    public function getGridUrl()
    {
      return $this->getUrl('*/*/grid', array('_current'=>true));
    }
  
 
}
?>
