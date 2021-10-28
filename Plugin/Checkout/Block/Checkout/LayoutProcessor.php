<?php
namespace Dev\Testing\Plugin\Checkout\Block\Checkout;
  
class LayoutProcessor
{

protected $_curl;

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array  $jsLayout
    ) {

        $jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']['unienvios_document_recipient'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'unienvios-document_recipient',
            ],
            'dataScope' => 'shippingAddress.custom_attributes.unienvios_document_recipient',
            'label' => 'CPF',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
		'required-entry' => true,
		"maxlength"=>11	
	     ],
            'sortOrder' =>50,
            'id' => 'unienvios-document_recipient'
        ];
	
	
	$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']
	['unienvios_number'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'unienvios-number',
            ],
            'dataScope' => 'shippingAddress.custom_attributes.unienvios_number',
            'label' => 'Número',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'required-entry' => true,
             ],
            'sortOrder' => 80,
            'id' => 'unienvios-number'
        ];
	

	$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']
        ['unienvios_neighbourhood'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'unienvios-neighbourhood',
            ],
            'dataScope' => 'shippingAddress.custom_attributes.unienvios_neighbourhood',
            'label' => 'Bairro',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'required-entry' => true,
             ],
            'sortOrder' => 81,
            'id' => 'unienvios-neighbourhood'
        ];

	
	$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']['children']
        ['shippingAddress']['children']['shipping-address-fieldset']['children']
        ['unienvios_complement'] = [
            'component' => 'Magento_Ui/js/form/element/abstract',
            'config' => [
                'customScope' => 'shippingAddress.custom_attributes',
                'template' => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
                'options' => [],
                'id' => 'unienvios-complement',
            ],
            'dataScope' => 'shippingAddress.custom_attributes.unienvios_complement',
            'label' => 'Complemento',
            'provider' => 'checkoutProvider',
            'visible' => true,
            'validation' => [
                'required-entry' => true,
             ],
            'sortOrder' => 82,
            'id' => 'unienvios-complement'
        ];


        return $jsLayout;
    }
}
