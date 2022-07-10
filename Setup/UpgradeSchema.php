<?php

namespace Lg\PaymentDiscount\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        // Add discount and baseDiscount
        foreach (['quote', 'quote_address', 'sales_order', 'sales_invoice', 'sales_creditmemo'] as $table) {
            $this->addColumn($setup, $table, 'discount_payment_amount', 'Discount Payment Amount');
            $this->addColumn($setup, $table, 'base_discount_payment_amount', 'Base Discount Payment Amount');
            if ($table === 'quote') {
                $this->addColumnString($setup, $table, 'discount_payment_type', 'Discount Payment Type');
                $this->addColumn($setup, $table, 'discount_payment_value', 'Discount Payment Value');
            }
        }

        // Add feeInvoiced, baseFeeInvoiced, feeRefunded, baseFeeRefunded
        $this->addColumn($setup, 'sales_order', 'discount_payment_amount_invoiced', 'Discount Amount Invoiced');
        $this->addColumn($setup, 'sales_order', 'base_discount_payment_amount_invoiced', 'Base Discount Amount Invoiced');
        $this->addColumn($setup, 'sales_order', 'discount_payment_amount_refunded', 'Discount Amount Refunded');
        $this->addColumn($setup, 'sales_order', 'base_discount_payment_amount_refunded', 'Base Discount Amount Refunded');

        $setup->endSetup();
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param string $table
     * @param string $name
     * @param string $description
     */
    public function addColumn(SchemaSetupInterface $setup, $table, $name, $description)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable($table),
            $name,
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
                'length' => '12,4',
                'default' => 0.0000,
                'nullable' => true,
                'comment' => $description
            ]
        );
    }

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param string $table
     * @param string $name
     * @param string $description
     */
    public function addColumnString(SchemaSetupInterface $setup, $table, $name, $description)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable($table),
            $name,
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => '255',
                'default' => null,
                'nullable' => true,
                'comment' => $description
            ]
        );
    }
}
