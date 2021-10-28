<?php

namespace Dev\Testing\Model\Carrier;

use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Checkout\Model\Session as CheckoutSession;

/**
 * Custom shipping model
 */
class UnienviosModel extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'unienvios';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    private $rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    private $rateMethodFactory;

/**
* @var \Magento\Framework\HTTP\Client\Curl
*/
protected $_curl;
protected $_checkoutSession;
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
	* @param Magento\Framework\HTTP\Client\Curl $curl
     */

const XML_PATH_EMAIL_RECIPIENT = 'carriers/unienvios/email';
const XML_PATH_SENHA_RECIPIENT = 'carriers/unienvios/senha';
const XML_PATH_STATUS_RECIPIENT = 'carriers/unienvios/active';

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = [],
	\Magento\Framework\HTTP\Client\Curl $curl,
	CheckoutSession $checkoutSession
    	) {
	$this->_curl = $curl;
	$this->_checkoutSession = $checkoutSession;
	$this->scopeConfig = $scopeConfig;

        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);

        $this->rateResultFactory = $rateResultFactory;
        $this->rateMethodFactory = $rateMethodFactory;
	
    }

    /**
     * Custom Shipping Rates Collector
     *
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->rateMethodFactory->create();

        $method->setCarrier($this->_code);
        $method->setCarrierTitle("Unienvios");

	$medidas = [
 	 "zipcode_destiny" => $request->getData()['dest_postcode'],
 	 "estimate_height" => 0,
 	 "estimate_width" => 0,
 	 "estimate_length" => 0,
  	"estimate_weight" => 0,
	"order_value" => $request->getData()['base_subtotal_incl_tax']
	];

foreach($request->getAllItems() as $item){
    //do you logic per item
   $product = $item->getProduct();
 
   $objectManagerTeste = \Magento\Framework\App\ObjectManager::getInstance();
   $product = $objectManagerTeste->create('Magento\Catalog\Model\Product')->load($product->getId());
   $my_width = $product->getData('ts_dimensions_width');
   $my_height = $product->getData('ts_dimensions_height');
   $my_length = $product->getData('ts_dimensions_length');
   $my_weight = $product->getData('ts_dimensions_weight');    
   $medidas['estimate_width'] += doubleval($my_width) * intVal($item->get('qty')['qty']);
   $medidas['estimate_height'] += doubleval($my_height) * intVal($item->get('qty')['qty']);
   $medidas['estimate_length'] += doubleval($my_length) * intVal($item->get('qty')['qty']);
   $medidas['estimate_weight'] += doubleval($my_weight) * intVal($item->get('qty')['qty']);

}

//	$this->getCheckoutSession()->setMyMedidas($medidas);

	 $quotations=$this->getQuotation($medidas);
	if(isset($quotations->statusCode)){
	return false;	
	
	$dado = ["code"=>"teset1", "title"=>json_encode($quotations->statusCode), "price"=>11.8, "cost"=>0];
         $result->append($this->_getExpressShippingRate($dado));

	$this->getAllowedMethods();	
	return $result;

	}else{

	if($quotations->token != null){	

	 $quotationsList = $quotations->quotations;


		foreach($quotationsList as $key=>$quot){
		$dado = [
		"code" => $quot->id,
		"title" =>$quot->name,
		"price" => $quot->final_price,
		"cost" => 0
		];
		$result->append($this->_getExpressShippingRate($dado));
		}

        	return $result;
	}

	}
	
	return false;

    }

	protected function _getExpressShippingRate($dado=null) {
   
    /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
	$method = $this->rateMethodFactory->create();
   	 $method->setCarrier($this->_code);
   	 $method->setCarrierTitle("Unienvios");
   	 $method->setMethod($dado["code"]);
   	 $method->setMethodTitle($dado["title"]);
   	 $method->setPrice($dado["price"]);
   	 $method->setCost($dado["cost"]);
    return $method;
}

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
   
	return ["teset1"=>"Teste 1"];
    }


    public function getQuotation($medidas = null) {

	$parans = json_encode($medidas);
	$this->_curl->addHeader("Content-Type", "application/json");
	$this->_curl->addHeader("email", $this->getReceipentEmail());
	$this->_curl->addHeader("password", $this->getReceipentSenha());
	$teste = $this->_curl->post("https://apihml.unienvios.com.br/external-integration/quotation/get-quotations", $parans); 
        $result =$this->_curl->getBody();
	$result = json_decode($result);
        
	if(isset($result->token)){
	//	salvar token em variavel de session
	$this->getCheckoutSession()->setMyTokenQuotation($result->token);
	}

	return $result;
	
    }

    public function getCheckoutSession() {
        return $this->_checkoutSession;
    }

    
    public function getReceipentEmail() {
     $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

     return $this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope);


     }


 public function getReceipentSenha() {
     $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

     return $this->scopeConfig->getValue(self::XML_PATH_SENHA_RECIPIENT, $storeScope);


     }

 public function getReceipentStatus() {
     $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

     return $this->scopeConfig->getValue(self::XML_PATH_STATUS_RECIPIENT, $storeScope);


     }


}
