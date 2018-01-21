<?php
/**
 * Adds Salesperson to orders and quotes. Automatically adds the logged in user.
 * Copyright (C) 2017  2017 Sure Thing Web
 * 
 * This file is part of Surething/Salesperson.
 * 
 * Surething/Salesperson is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Surething\Salesperson\Setup;

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
		$setup->getConnection()->addColumn(
			$setup->getTable('sales_order_grid'),
			'surething_sales_person',
			[
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 150,
				'nullable' => true,
				'comment' => 'Sales Person'
			]
		);
		
		$setup->getConnection()->addColumn(
			$setup->getTable('sales_order'),
			'surething_sales_person',
			[
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 150,
				'nullable' => true,
				'comment' => 'Sales Person'
			]
		);
		
		$setup->getConnection()->addColumn(
			$setup->getTable('quote'),
			'surething_sales_person',
			[
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'length' => 150,
				'nullable' => true,
				'comment' => 'Sales Person'
			]
		);
    }
}
