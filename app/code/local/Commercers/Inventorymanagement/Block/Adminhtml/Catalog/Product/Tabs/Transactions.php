<?php
/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Block_Adminhtml_Catalog_Product_Tabs_Transactions extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('inventorymanagement_testgrid');
        $this->setDefaultSort('date');
        $this->setDefaultDir('DESC');
        $this->setSkipGenerateContent(true);
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    
    protected function _prepareCollection()
    {
        $product = Mage::registry('product');
        
        $collection = Mage::getModel('inventorymanagement/transactions')->getCollection()
                ->addAttributeToFilter('product_id', array('eq' => $product->getId()));
              
        $this->setCollection($collection);
  
        return parent::_prepareCollection();
    }
    
    protected function _prepareColumns()
    { 
        $this->addColumn('transaction_id', array(
            'header'    => Mage::helper('inventorymanagement')->__('ID'),
            'align'     =>'right',
            'width'     => 80,
            'index'     => 'transaction_id',
        ));
        
        $this->addColumn('date', array(
            'header'    => Mage::helper('adminhtml')->__('Date'),
            'align'     => 'left',
            'width'     => 200,
            'type'      => 'datetime',
            'index'     => 'date',
        ));
        
        $this->addColumn('type', array(
            'header'    => Mage::helper('inventorymanagement')->__('Type'),
            'align'     => 'left',
            'width'     => 400,
            'index'     => 'type',
            'renderer'  => 'Commercers_Inventorymanagement_Block_Adminhtml_Catalog_Product_Renderer_Translate',
        ));   
        
         $this->addColumn('reference', array(
            'header'    => Mage::helper('adminhtml')->__('Transaction reference'),
            'align'     => 'left',
            'width'     => 200,
            'index'     => 'reference',
            'renderer'  => 'Commercers_Inventorymanagement_Block_Adminhtml_Catalog_Product_Renderer_Translate',
        ));
        
        $this->addColumn('qty', array(
            'header'    => Mage::helper('inventorymanagement')->__('Difference'),
            'align'     => 'left',
            'width'     => 120,
            'index'     => 'qty',
        ));  
        
        $this->addColumn('stock', array(
            'header'    => Mage::helper('adminhtml')->__('Stock'),
            'align'     => 'left',
            'width'     => 120,
            'index'     => 'stock',
        ));  
       
        return parent::_prepareColumns();
    }
 
    public function getRowUrl($row)
    {
        return "http://www.commercers-shop.com/com_de/magento-extensions/magento-warenwirtschaft-pro.html";
    }
 
    public function getGridUrl()
    {
      return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/transactionsGrid', array('_current'=>true));
    }
}
?>
