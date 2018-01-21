<?php
/**
 * Copyright © 2015 Surething. All rights reserved.
 */

namespace Surething\Testimonials\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
	
        $installer = $setup;

        $installer->startSetup();

		/**
         * Create table 'testimonials_testimonials'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('testimonials_testimonials')
        )
		->addColumn(
            'id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'testimonials_testimonials'
        )
		->addColumn(
            'testimony_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_name'
        )
		->addColumn(
            'testimony_company',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_company'
        )
		->addColumn(
            'testimony_install',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_install'
        )
		->addColumn(
            'testimony_filename',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_filename'
        )
		->addColumn(
            'testimony_thumb',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_thumb'
        )
		->addColumn(
            'testimony_headline',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_headline'
        )
		->addColumn(
            'testimony_description',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_description'
        )
		->addColumn(
            'testimony_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_type'
        )
		->addColumn(
            'testimony_mural',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_mural'
        )
		->addColumn(
            'testimony_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '64k',
            [],
            'testimony_url'
        )
		->addColumn(
            'testimony_order',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'testimony_order'
        )
		/*{{CedAddTableColumn}}}*/
		
		
        ->setComment(
            'Surething Testimonials testimonials_testimonials'
        );
		
		$installer->getConnection()->createTable($table);
		/*{{CedAddTable}}*/

        $installer->endSetup();

    }
}
