<?php

namespace Surething\Tooltip\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{

    /**
     * {@inheritdoc}
     */
    public function install(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        // Add an extra column to the catalog_eav_attribute-table:
		$setup->getConnection()->addColumn(
			$setup->getTable('catalog_eav_attribute'),
			'tooltip',
			array(
			'type'      => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable'  => true,
				'comment'   => 'Tooltip'
			)
		);
    }
}

