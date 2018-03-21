<?php

namespace Alaa\OrderFeedExample\Setup;

use Alaa\OrderFeedExample\Model\Setup\SerializedFieldArray;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @var SerializedFieldArray
     */
    private $serializedFieldArray;

    /**
     * InstallSchema constructor.
     * @param SerializedFieldArray $serializedFieldArray
     */
    public function __construct(SerializedFieldArray $serializedFieldArray)
    {
        $this->serializedFieldArray = $serializedFieldArray;
    }

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $configs = [
            'order_export/settings/order/custom_attributes' => [
                [
                    'custom_attribute' => 'order_id',
                    'magento_attribute' => 'entity_id'
                ],
                [
                    'custom_attribute' => 'customer_reference',
                    'magento_attribute' => 'increment_id'
                ]
            ],
            'order_export/settings/customer/custom_attributes' => [
                [
                    'custom_attribute' => 'customer_id',
                    'magento_attribute' => 'customer_id'
                ]
            ],
            'order_export/settings/order_line/custom_attributes' => [
                [
                    'custom_attribute' => 'order_item_id',
                    'magento_attribute' => 'item_id'
                ],
                [
                    'custom_attribute' => 'code',
                    'magento_attribute' => 'sku'
                ],
                [
                    'custom_attribute' => 'item_gross',
                    'magento_attribute' => 'row_total_incl_tax'
                ],
                [
                    'custom_attribute' => 'quantity',
                    'magento_attribute' => 'qty_ordered'
                ]
            ],
            'order_export/settings/payment/custom_attributes' => [
                [
                    'custom_attribute' => 'payment_method',
                    'magento_attribute' => 'method'
                ],
                [
                    'custom_attribute' => 'amount',
                    'magento_attribute' => 'amount_paid'
                ]
            ]
        ];

        foreach ($configs as $path => $config) {
            $this->serializedFieldArray->addConfig($path, $config);
        }

        $setup->endSetup();
    }
}