<?php
 
namespace Unienvios\Cotacao\Setup;
 
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
 
class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
 
    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
 
        /* Product Custom Title */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'unienvios_height');
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'unienvios_height',
            [
                'type' => 'varchar',
                'label' => 'Height',
                'input' => 'text',
                'required' => false,
                'sort_order' => 10,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'custom_content_hide',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            ]
        );
 /* Product Custom Title */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'unienvios_length');
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'unienvios_length',                                                                                                     [
                'type' => 'varchar',
                'label' => 'Length',
                'input' => 'text',
                'required' => false,
                'sort_order' => 10,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'custom_content_hide',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            ]
        );
 /* Product Custom Title */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'unienvios_weight');
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'unienvios_weight',                                                                                                     [
                'type' => 'varchar',
                'label' => 'Weight',
                'input' => 'text',
                'required' => false,
                'sort_order' => 10,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'custom_content_hide',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            ]
        );	
	/* Product Custom Title */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'unienvios_width');                                 $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'unienvios_width',                                                                                                     [
                'type' => 'varchar',
                'label' => 'Width',                                                                                                    'input' => 'text',
                'required' => false,
                'sort_order' => 11,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'custom_content_hide',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
            ]
        );
	
		


        /* Product Custom Select Options */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'custom_mode');
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_mode',
            [
                'type' => 'varchar',
                'label' => 'Custom Select Option',
                'input' => 'select',
                'required' => false,
                'sort_order' => 20,
                'source' => \Unienvios\Cotacao\Model\Config\Source\CustomModeList::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'custom_content_hide',
            ]
        );
        /* Product Custom Multi Select Option */
        $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'custom_cms_pages');
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_cms_pages',
            [
                'type' => 'varchar',
                'label' => 'Custom CMS Pages',
                'input' => 'multiselect',
                'required' => false,
                'sort_order' => 30,
                'source' => \Unienvios\Cotacao\Model\Config\Source\CMSPageList::class,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'group' => 'custom_content_hide',
            ]
        );
    }
}
