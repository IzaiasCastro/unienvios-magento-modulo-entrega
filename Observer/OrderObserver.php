<?php

namespace Dev\Testing\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\HTTP\Client\Curl;

class OrderObserver implements ObserverInterface
{
    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;
    protected $messageManager;
    protected $_curl;
     protected $_quoteRepository;
protected $_productRepository;
protected $scopeConfig;

const XML_PATH_EMAIL_RECIPIENT = 'carriers/unienvios/email';
const XML_PATH_SENHA_RECIPIENT = 'carriers/unienvios/senha';
const XML_PATH_STATUS_RECIPIENT = 'carriers/unienvios/active';
    /**
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Quote\Model\QuoteFactory $quoteFactory,
        \Magento\Sales\Model\Order $order,
        ManagerInterface $messageManager,
        \Magento\Framework\HTTP\Client\Curl $curl,
	\Magento\Quote\Model\QuoteRepository $quoteRepository,
	\Magento\Catalog\Model\ProductFactory $productFactory,
	\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->_objectManager = $objectManager;
        $this->messageManager = $messageManager;
	$this->_curl = $curl;
        $this->_quoteRepository = $quoteRepository;
	$this->productFactory = $productFactory;
	$this->scopeConfig = $scopeConfig;
    }

     public function execute(Observer $observer)
    {
	
    }

    public function apiCreateQuotation($parametros, $token) {
    /**	$parametros = array(
      'zipcode_destiny' => "64224002",
      'document_recipient' => "073-910.063-78",
      'name_recipient' => "John Doe",
      'email_recipient' => "johndoe@email.com",
      'phone_recipient' => "(11)-98161755",
      'estimate_height' => 12,
      'estimate_width' => 15,
      'estimate_length' => 15,
      'estimate_weight' => 15,
      'order_value' => 150,
      'address' => "Rua José Diog",
      'number' => "361",
      'city' => "Parnaiba",
      'neighbourhood' => "bairro cal",
      'state' => "PI",
      'complement' => "casa",
      'shipping_id' => "36106104-9fd7-4e60-b1fa-275188d227c5"
    );
**/

///aqui
$values = $this->getReceipentSenha();
return $values;

 $parans = json_encode($parametros);
        $this->_curl->addHeader("Content-Type", "application/json");
        $this->_curl->addHeader("email", $this->getReceipentEmail());
        $this->_curl->addHeader("password", $this->getReceipentSenha());
        $this->_curl->addHeader("token", $token);
        $this->_curl->post("https://apihml.unienvios.com.br/external-integration/quotation/create", $parans);
        $response =$this->_curl->getBody();
	
	return $response;

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
