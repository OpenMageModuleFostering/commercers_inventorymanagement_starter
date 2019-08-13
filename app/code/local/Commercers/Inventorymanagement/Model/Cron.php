<?php
/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Model_Cron extends Mage_Core_Model_Abstract
{   
    const TYPE_CORRECTION    = 'Correction';
    const TYPE_RECEIVING     = 'Receiving';
    const TYPE_INVENTORY     = 'Stock taking';
    const TYPE_SALE          = 'Sale';
    const TYPE_CREDITMEMO    = 'Credit memo';
    const TYPE_CANCELLATION  = 'Cancellation';
    const EXECUTION_CRON     = 'Cron';
    const LIGHT_TEXT         = 'More Information in Pro Version';
    
    public function collectTransactions()
    {
        $this->_errors = array();
        try 
        {
            $data = Mage::getModel('inventorymanagement/transactions')->getCollection()->getData();
            if(empty($data))
            {
                $this->initiateData();
                $lastDate = now();
            }
            else
            {
                $history = Mage::getModel('inventorymanagement/inventorymanagement')->getCollection()
                    ->addAttributeToSort('date', 'desc');

                foreach ($history as $entry)
                {
                    $lastDate = $entry->getDate();
                    break;
                }
            
                if(Mage::helper('core')->isModuleEnabled('Commercers_Receivinglog'))
                {
                    $this->writeReceiving($lastDate);
                }
                if(Mage::helper('core')->isModuleEnabled('Commercers_Inventorylog'))
                {
                    $this->writeStocktaking($lastDate);
                }
                $this->writeSales($lastDate);
                $this->writeCreditmemo($lastDate);
                $this->writeCancellations($lastDate);
                $this->writeCorrections($lastDate);
            }
            $this->writeHistory();
        }
        catch (Exception $e) {
            $this->_errors[] = $e->getMessage();
        }
        return $this;
    }
    
    protected function writeReceiving($lastDate)
    {
        $receivinglog = Mage::getModel('receivinglog/receivinglog')->getCollection()
                ->addAttributeToFilter('date', array('gt' => $lastDate))
                ->addAttributeToSort('date', 'ASC');
        
        foreach($receivinglog as $receiving)
        {
            $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $receiving->getSku());
            $this->writeData(
                    $receiving->getDate(), 
                    $receiving->getDifference(), 
                    $product->getId(), 
                    self::TYPE_RECEIVING, 
                    self::LIGHT_TEXT
                );
            
        }          
    }
          
    protected function writeStocktaking($lastDate)
    {
        $inventorylog = Mage::getModel('inventorylog/inventorylog')->getCollection()
                ->addAttributeToFilter('date', array('gt' => $lastDate))
                ->addAttributeToSort('date', 'ASC');
        
        foreach($inventorylog as $inventory)
        {
            $this->writeData(
                    $inventory->getDate(), 
                    $inventory->getDifference(), 
                    $inventory->getProductId(), 
                    self::TYPE_INVENTORY, 
                    self::LIGHT_TEXT
                );
        }      
    }
           
    protected function writeSales($lastDate)
    {
        $sales = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('created_at', array('gt' =>  $lastDate))
            ->addAttributeToSort('created_at', 'ASC');
        
        foreach($sales as $order) 
        {
            $items = $order->getItemsCollection();
            foreach ($items as $item)
            {
                $this->writeData(
                        $order->getCreatedAt(), 
                        0-$item->getQtyOrdered(), 
                        $item->getProductId(), 
                        self::TYPE_SALE, 
                        self::LIGHT_TEXT
                    );
            }
        }
    }
            
    protected function writeCreditmemo($lastDate)
    {
        $creditmemo = Mage::getModel('sales/order_creditmemo')->getCollection()
            ->addFieldToFilter('created_at', array('gt' =>  $lastDate))
            ->addAttributeToSort('created_at', 'ASC');
        foreach($creditmemo as $creditmemoItem)
        {
            $createdAt = $creditmemoItem->getCreatedAt();
            $orderID = $creditmemoItem->getOrderId();
            $orders = Mage::getModel('sales/order')->getCollection()
                    ->addAttributeToFilter('entity_id', array('eq' => $orderID));
            foreach($orders as $order)
            {
                $items = $order->getItemsCollection();
                foreach($items as $item)
                {   
                    if($item->getParentItem() != null)
                    {
                        $refunded = $item->getParentItem()->getQtyRefunded();
                    }
                    else
                    {
                        $refunded = $item->getQtyRefunded();
                    }
                    if($refunded != 0)
                    {
                        $this->writeData(
                                $createdAt, 
                                $refunded, 
                                $item->getProductId(), 
                                self::TYPE_CREDITMEMO, 
                                self::LIGHT_TEXT
                            );
                    }
                }
            }
        }
    }
          
    protected function writeCancellations($lastDate)
    {
         $sales = Mage::getModel('sales/order')->getCollection()
            ->addFieldToFilter('updated_at', array('gt' =>  $lastDate))
            ->addAttributeToSort('updated_at', 'ASC');
         
        foreach($sales as $order) 
        {
            $items = $order->getItemsCollection();
            foreach ($items as $item)
            { 
                if($item->getParentItem() != null)
                {
                    $canceled = $item->getParentItem()->getQtyCanceled();
                }
                else
                {
                    $canceled = $item->getQtyCanceled();
                }
                if($canceled != 0)
                {
                    $this->writeData(
                            $order->getUpdatedAt(), 
                            $canceled, 
                            $item->getProductId(), 
                            self::TYPE_CANCELLATION, 
                            self::LIGHT_TEXT
                        );
                }
            }
        }
    }
            
    protected function writeCorrections($lastDate)
    {
        $products = Mage::getModel('catalog/product')->getCollection();
        foreach($products as $product)
        {
            $old_entries = Mage::getModel('inventorymanagement/transactions')->getCollection()
                    ->addFieldToFilter('product_id', array('eq' =>  $product->getId()))
                    ->addFieldToFilter('date', array('lteq' =>  $lastDate))
                    ->addAttributeToSort('date', 'DESC');
            foreach($old_entries as $entry_old)
            {
                $last_qty = $entry_old->getStock();
                break;
            }
            $new_entries = Mage::getModel('inventorymanagement/transactions')->getCollection()
                    ->addFieldToFilter('product_id', array('eq' =>  $product->getId()))
                    ->addFieldToFilter('date', array('gt' =>  $lastDate))
                    ->addAttributeToSort('date', 'ASC');
            foreach($new_entries as $entry)
            {
                $last_qty = $last_qty + $entry->getQty();
                $entry->setStock($last_qty)
                        ->save();
            }
            $stock_items = Mage::getModel('cataloginventory/stock_item');
            $stock_item = $stock_items->loadByProduct($product->getId());
            $stock = $stock_item->getQty();
            if($last_qty != $stock)
            {
                $this->writeData(
                        now(), 
                        $stock-$last_qty, 
                        $product->getId(), 
                        self::TYPE_CORRECTION, 
                        self::LIGHT_TEXT,
                        $stock
                    );
            }
        }
    }
    
    protected function initiateData()
    {
        $products = Mage::getModel('catalog/product')->getCollection();
        foreach($products as $product)
        {
            $stock_items = Mage::getModel('cataloginventory/stock_item');
            $stock_item = $stock_items->loadByProduct($product->getId());
            if (!$stock_item) 
            {
                $this->_getSession()->addError($this->__('The current number in stock could not be detected.'));
            } 
            else 
            {
                $count = $stock_item->getQty();
                $this->writeData(
                        now(), 
                        $count, 
                        $product->getId(), 
                        self::TYPE_CORRECTION, 
                        self::LIGHT_TEXT,
                        $count
                    );
            }
        }
    }
    
    private function writeData($date, $qty, $product_id, $type, $ref, $stock = null) 
    {
        try 
        {
            $model = Mage::getModel('inventorymanagement/transactions');
            $model->setDate($date)
                ->setProductID($product_id)
                ->setType($type)
                ->setReference($ref)
                ->setQty($qty)
                ->setStock($stock)
                ->save();

            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return;
        }
    }
    
    private function writeHistory() 
    {
        try 
        {
            $model = Mage::getModel('inventorymanagement/inventorymanagement');

            $execution = Mage::getSingleton('core/session')->getExecution();
            if($execution != self::EXECUTION_CRON)
            {                        
                Mage::getSingleton('core/session')->setExecution(self::EXECUTION_CRON);
                if(empty($execution))
                {
                    $execution = self::EXECUTION_CRON;
                }
            }

            $model->setDate(now())
                ->setExecution($execution)
                ->save();

            return;
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return;
        }
    }
}
?>
