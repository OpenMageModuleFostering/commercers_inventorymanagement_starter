<?php

/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Model_Inventorymanagement extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('inventorymanagement/inventorymanagement');
    }

}
?>
