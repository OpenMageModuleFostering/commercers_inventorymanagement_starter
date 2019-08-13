<?php
/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Adminhtml_InventorymanagementController extends Mage_Adminhtml_Controller_Action
{        
    protected function _initAction()
    {
        $this->loadLayout()
                ->_setActiveMenu('catalog/commercers/inventorymanagement')
            ->_addBreadcrumb(Mage::helper('adminhtml')->__('Inventory management'), Mage::helper('adminhtml')->__('Inventory management'));
        return $this;
    }   

    public function indexAction() 
    {
        $this->_initAction()
        ->renderLayout();
    }
  
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
               $this->getLayout()->createBlock('inventorymanagement/adminhtml_inventorymanagement_grid')->toHtml()
        );
    } 
   
    public function editAction()
    {
        $this->_redirect('*/*/');
    }

    public function runAction()
    {
        Mage::getSingleton('core/session')->setExecution(Mage::getSingleton('admin/session')->getUser()->getUsername());
        $cron = Mage::getModel('inventorymanagement/cron')->collectTransactions();
        $this->_getSession()->addSuccess($this->__("Collection of Transactions executed."));
        $this->_redirect('*/*/index');
    }
    
    public function exportAction()
    {
        $fileName   = 'inventorymanagement_history.csv';
        $content    = $this->getLayout()->createBlock('inventorymanagement/adminhtml_inventorymanagement_grid')->getCsv();
        $type       = 'text/csv';
        $this->_prepareDownloadResponse($fileName, $content, $type);
    }

    protected function _prepareDownloadResponse($fileName, $content, $contentType = 'application/octet-stream', $contentLength = null)
    {
        $session = Mage::getSingleton('admin/session');
        if ($session->isFirstPageAfterLogin()) {
            $this->_redirect($session->getUser()->getStartupPageUrl());
            return $this;
        }
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true)
            ->setHeader('Content-Length', is_null($contentLength) ? strlen($content) : $contentLength)
            ->setHeader('Content-Disposition', 'attachment; filename=' . $fileName)
            ->setHeader('Last-Modified', date('r'));
        if (!is_null($content)) {
            $this->getResponse()->setBody($content);
        }
        return $this;
    }
}
?>
