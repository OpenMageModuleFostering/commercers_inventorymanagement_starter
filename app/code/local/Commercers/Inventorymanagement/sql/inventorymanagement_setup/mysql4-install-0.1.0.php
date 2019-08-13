<?php
/*
 * commercers.com
 * Commercers Inventory management
 */
$installer = $this;
$installer->startSetup();

$installer->run(        
"DROP TABLE IF EXISTS {$this->getTable('commercers_inventorymanagement_transactions')};    
CREATE TABLE {$this->getTable('commercers_inventorymanagement_transactions')}(
    `transaction_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `date` datetime,
    `product_id` INT(10) UNSIGNED NOT NULL,
    `type` VARCHAR(64) NOT NULL,
    `reference` VARCHAR(64) NOT NULL,
    `qty` DECIMAL(12,4),
    `stock` DECIMAL(12,4),
    PRIMARY KEY (`transaction_id`)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS {$this->getTable('commercers_inventorymanagement')};    
CREATE TABLE {$this->getTable('commercers_inventorymanagement')}(
    `history_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `date` datetime,
    `execution` VARCHAR(64) NOT NULL,
    PRIMARY KEY (`history_id`)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;
");


$installer->endSetup();
