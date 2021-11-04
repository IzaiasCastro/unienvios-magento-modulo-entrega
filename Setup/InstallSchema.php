<?php
namespace Unienvios\Cotacao\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(
		SchemaSetupInterface $setup, 
		ModuleContextInterface $context
	){
        $setup->startSetup();

        $quote = $setup->getTable('quote');
        $salesOrder = $setup->getTable('sales_order');
		
		//token
		$setup->getConnection()->addColumn(
			$quote,
			'unienvios_token',
			[
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' =>'Token'
			]
		);
		
		$setup->getConnection()->addColumn(
			$salesOrder,
			'unienvios_token',
			[
				'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				'nullable' => true,
				'comment' =>'Token'
			]
		);
		
		//cpf
		 $setup->getConnection()->addColumn(
                        $quote,
                        'unienvios_document_recipient',
                        [
                                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                'nullable' => true,
                                'comment' =>'Cpf'
                        ]
                );

                $setup->getConnection()->addColumn(
                        $salesOrder,
                        'unienvios_document_recipient',
                        [
                                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                'nullable' => true,
                                'comment' =>'Cpf'
                        ]
                );
		
		//number
		 $setup->getConnection()->addColumn(
                        $quote,
                        'unienvios_number',
                        [
                                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                'nullable' => true,
                                'comment' =>'Numero'
                        ]
                );

                $setup->getConnection()->addColumn(
                        $salesOrder,
                        'unienvios_number',
                        [
                                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                'nullable' => true,
                                'comment' =>'Numero'
                        ]
                );
		
		//bairro
		 $setup->getConnection()->addColumn(
                        $quote,
                        'unienvios_neighbourhood',
                        [
                                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                'nullable' => true,
                                'comment' =>'Bairro'
                        ]
                );

                $setup->getConnection()->addColumn(
                        $salesOrder,
                        'unienvios_neighbourhood',
                        [
                                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                'nullable' => true,
                                'comment' =>'Bairro'
                        ]
                );
		
		//complemento
		 $setup->getConnection()->addColumn(
                        $quote,
                        'unienvios_complement',
                        [
                                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                'nullable' => true,
                                'comment' =>'Complemento'
                        ]
                );

                $setup->getConnection()->addColumn(
                        $salesOrder,
                        'unienvios_complement',
                        [
                                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                                'nullable' => true,
                                'comment' =>'Complemento'
                        ]
               );		
		
		    
		
        $setup->endSetup();
    }
}
