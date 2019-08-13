<?php

/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Model_Transactions extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('inventorymanagement/transactions');
    }
}
?>
