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
        /** @var OrderInterface $order */
        $order = $observer->getEvent()->getOrder();
        $orderId = $order->getEntityId();

         if($order->getState() == 'canceled' || $order->getState() == 'processing') {
    //        echo "<pre>"; var_dump($order->getData());exit;
         }




        if($this->getReceipentStatus() == "1"){

        $shipping_id = str_replace("unienvios_", "", $order->getData('shipping_method'));
        $token = $order->getData("custom_notes");


        $medidas = [
         "estimate_height" => 0,
         "estimate_width" => 0,
         "estimate_length" => 0,
         "estimate_weight" => 0
        ];

        foreach ($order->getAllItems() as $item) {
            $product = $this->productFactory->create()->load($item->getProductId());
            $width = $product->getResource()->getAttribute('ts_dimensions_width')->getFrontend()->getValue($product);
            $height = $product->getResource()->getAttribute('ts_dimensions_height')->getFrontend()->getValue($product);
            $length = $product->getResource()->getAttribute('ts_dimensions_length')->getFrontend()->getValue($product);
            $weight = $product->getResource()->getAttribute('ts_dimensions_weight')->getFrontend()->getValue($product);
         if ($width) {
                $medidas['estimate_width'] += doubleval($width) * intVal($item->getQtyOrdered());
            }

         if ($height) {
                $medidas['estimate_height'] += doubleval($height) * intVal($item->getQtyOrdered());
            }
         if ($length) {
                 $medidas['estimate_length'] += doubleval($length) * intVal($item->getQtyOrdered());
            }

         if ($weight) {
                 $medidas['estimate_weight'] += doubleval($weight) * intVal($item->getQtyOrdered());
          }


         }


        $parametros = [
        "zipcode_destiny" => $order->getShippingAddress()->getData("postcode"),
        "document_recipient" => "07391006378",
        "name_recipient" =>$order->getShippingAddress()->getData("firstname"),
        "email_recipient" => $order->getShippingAddress()->getData("email"),
        "phone_recipient"=> $order->getShippingAddress()->getData("telephone"),
        "estimate_height" => $medidas['estimate_height'],
        "estimate_width" =>  $medidas['estimate_width'],
        "estimate_length" => $medidas['estimate_length'],
        "estimate_weight" => $medidas['estimate_weight'],
        "order_value" => doubleval($order->getSubtotal()),
        "address" =>$order->getShippingAddress()->getData("street"),
        "number" => "10",
        "city" => $order->getShippingAddress()->getData("city"),
        "neighbourhood" => "teste",
        "state" => $order->getShippingAddress()->getData("region"),
        "complement" => "",
        "shipping_id" => $shipping_id
        ];



 //echo "<pre>"; var_dump(doubleval($order->getSubtotal()));exit;


        $response = $this->apiCreateQuotation($parametros, $token);
        echo "<pre>"; var_dump($response);exit;

        }



    }

    public function apiCreateQuotation($parametros, $token) {
    /** $parametros = array(
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
