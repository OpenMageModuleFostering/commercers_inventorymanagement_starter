<?php
/*
 * commercers.com
 * Commercers Inventory management
 */
class Commercers_Inventorymanagement_Model_Adminhtml_System_Config_Backend_Commercers_Inventorymanagement extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH  = 'crontab/jobs/commercers_inventorymanagement/schedule/cron_expr';
    const CRON_MODEL_PATH   = 'crontab/jobs/commercers_inventorymanagement/run/model';

    protected function _afterSave()
    {
        $minute     = $this->getData('groups/inventorymanagement/fields/minute/value');
        $hour       = $this->getData('groups/inventorymanagement/fields/hour/value');
        $day       = $this->getData('groups/inventorymanagement/fields/day/value');
        $month       = $this->getData('groups/inventorymanagement/fields/month/value');
        $dayofweek       = $this->getData('groups/inventorymanagement/fields/dayofweek/value');
        $errorEmail = $this->getData('groups/inventorymanagement/fields/error_email/value');

        $cronExprArray = array( $minute, $hour, $day, $month, $dayofweek );
        $cronExprString = join(' ', $cronExprArray);

        try {
            Mage::getModel('core/config_data')
                ->load(self::CRON_STRING_PATH, 'path')
                ->setValue($cronExprString)
                ->setPath(self::CRON_STRING_PATH)
                ->save();

            Mage::getModel('core/config_data')
                ->load(self::CRON_MODEL_PATH, 'path')
                ->setValue((string) Mage::getConfig()->getNode(self::CRON_MODEL_PATH))
                ->setPath(self::CRON_MODEL_PATH)
                ->save();
        }
        catch (Exception $e) {
            Mage::throwException(Mage::helper('adminhtml')->__('Unable to save the cron expression.'));
        }
    }
}
