<?php

/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Model_Mysql4_Transactions extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {   
        $this->_init('inventorymanagement/transactions', 'transaction_id');
    }
}

?>
