<?php

require_once("Mage/Adminhtml/controllers/Catalog/ProductController.php");
/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    public function transactionsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('inventorymanagement.catalog.product.tabs.transactions');
        $this->renderLayout();
    } 
    
    public function transactionsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('inventorymanagement.catalog.product.tabs.transactions');
        $this->renderLayout();
    } 
} 
?>
