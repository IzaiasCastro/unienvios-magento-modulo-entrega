<?php
namespace Unienvios\Cotacao\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class SaveOrderObserver implements ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $quote = $observer->getEvent()->getQuote();
	
//echo "<pre>"; var_dump("okok");exit;	

	
	$om = \Magento\Framework\App\ObjectManager::getInstance();
 	$session = $om->get('Magento\Checkout\Model\Session');
  	$token = $session->getMyTokenQuotation() ;	

        $order->setData('unienvios_token', $token);
	
	$order->setData('unienvios_document_recipient', $quote->getUnienviosDocumentRecipient());
	
	$order->setData('unienvios_number', $quote->getUnienviosNumber()); 
	
	$order->setData('unienvios_neighbourhood', $quote->getUnienviosNeighbourhood()); 

	$order->setData('unienvios_complement', $quote->getUnienviosComplement()); 
        return $this;
    }
}
