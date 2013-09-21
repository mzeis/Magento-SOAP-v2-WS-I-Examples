<?php
/**
 * Example Client for using Magento SOAP API v2 with WS-I mode enabled.
 */
class MagentoSoapClient
{
    /**
     * @var SoapClient
     */
    protected $_client = null;
    
    /**
     * @var string
     */
    protected $_sessionId = null;
    
    /**
     * @var array
     */
    protected $_soapOptions = array(
        'soap_version' => SOAP_1_2,
        'trace' => true,
        'exceptions' => true,
        'cache_wsdl' => WSDL_CACHE_NONE,
        'features' => SOAP_SINGLE_ELEMENT_ARRAYS
    );
    
    /**
     * @param string $url
     * @return MagentoSoapClient
     */
    public function __construct($url)
    {
        $this->_client = new SoapClient($url, $this->_soapOptions);
    }
    
    /**
     * @link http://www.magentocommerce.com/api/soap/checkout/cartCustomer/cart_customer.addresses.html
     * @param int $cartId
     * @param array $addresses 
     * @return bool Returns true if the addresses have been added successfully
     */
    public function addAddressesToCart($cartId, array $addresses)
    {
        $data = array(
            'sessionId' => $this->_sessionId,
            'quoteId' => $cartId,
            'customerAddressData' => $addresses
        );
        $response = $this->_client->shoppingCartCustomerAddresses($data);
        return $response->result;
    }
    
    /**
     * @link http://www.magentocommerce.com/api/soap/checkout/cartProduct/cart_product.add.html
     * @param int $cartId
     * @param array $products
     * @return bool Returns true if the products have been added successfully
     */
    public function addProductsToCart($cartId, array $products)
    {
        $data = array(
            'sessionId' => $this->_sessionId,
            'quoteId' => $cartId,
            'productsData' => $products
        );
        $response = $this->_client->shoppingCartProductAdd($data);
        return $response->result;
    }
    
    /**
     * @link http://www.magentocommerce.com/api/soap/checkout/cart/cart.create.html
     * @return int Cart/quote id
     */
    public function createCart()
    {
        $data = array(
            'sessionId' => $this->_sessionId
        );
        $response = $this->_client->shoppingCartCreate($data);
        return $response->result;
    }
    
    /**
     * @link http://www.magentocommerce.com/api/soap/checkout/cart/cart.order.html
     * @param int $cartId
     * @return string Order increment id
     */
    public function createOrderFromCart($cartId)
    {
        $data = array(
            'sessionId' => $this->_sessionId,
            'quoteId' => $cartId
        );
        $response = $this->_client->shoppingCartOrder($data);
        return $response->result;
    }
    
    /**
     * @link http://www.magentocommerce.com/api/soap/introduction.html
     * @param string $name
     * @param string $password
     * @return void
     */
    public function login($name, $password)
    {
        $data = array(
            'username' => $name,
            'apiKey' => $password
        );
        $response = $this->_client->login($data);
        $this->_sessionId = $response->result;
    }
    
    /**
     * @param Exception $e
     * @return void
     */
    public function printException(Exception $e)
    {
        $output = "Exception

File: {$e->getFile()}
Line: {$e->getLine()}
Message: {$e->getMessage()}
Code: " .($e instanceof SoapFault ? $e->faultcode : $e->getCode());

        /*
        if ($e instanceof SoapFault) {
            $output .= "
            
SoapFault information:
String: {$e->faultstring}
Actor: {$e->faultactor}
Detail: {$e->detail}
Name: {$e->faultname}
Header: {$e->headerfault}";
        }
         */
        
        $output .= "\n\nTrace:\n{$e->getTraceAsString()}";
        
        echo nl2br(htmlentities($output));
    }
    
    /**
     * @link http://www.magentocommerce.com/api/soap/checkout/cartCustomer/cart_customer.set.html
     * @param int $cartId
     * @param array $customer
     * @return bool Returns true if the customer has been set successfully
     */
    public function setCustomerForCart($cartId, array $customer)
    {
        $data = array(
            'sessionId' => $this->_sessionId,
            'quoteId' => $cartId,
            'customerData' => $customer
        );
        $response = $this->_client->shoppingCartCustomerSet($data);
        return $response->result;
    }
    
    /**
     * @link http://www.magentocommerce.com/api/soap/checkout/cartPayment/cart_payment.method.html
     * @param int $cartId
     * @param string $paymentMethod
     * @return bool Returns true if the payment method has been set successfully
     */
    public function setPaymentMethodForCart($cartId, $paymentMethod)
    {
        $data = array(
            'sessionId' => $this->_sessionId,
            'quoteId' => $cartId,
            'paymentData' => array(
                'method' => $paymentMethod
            )
        );
        $response = $this->_client->shoppingCartPaymentMethod($data);
        return $response->result;
    }
    
    /**
     * @link http://www.magentocommerce.com/api/soap/checkout/cartShipping/cart_shipping.method.html
     * @param int $cartId
     * @param string $shippingMethod
     * @return bool Returns true if the shipping method has been set successfully
     */
    public function setShippingMethodForCart($cartId, $shippingMethod)
    {
        $data = array(
            'sessionId' => $this->_sessionId,
            'quoteId' => $cartId,
            'shippingMethod' => $shippingMethod
        );
        $response = $this->_client->shoppingCartShippingMethod($data);
        return $response->result;
    }
}
