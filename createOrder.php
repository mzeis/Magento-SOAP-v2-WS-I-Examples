<?php
error_reporting(E_ALL | E_STRICT);
ini_set("display_errors", 1);

require_once 'lib/MagentoSoapClient.php';

try {
    $client = new MagentoSoapClient('http://test02.magentoshops.vm/api/v2_soap?wsdl=1');
    
    $client->login("Admin", "Admin123");
    echo "Logged in.<br>";
    
    $cartId = $client->createCart();
    echo "Created a cart. ID: {$cartId}<br>";
    
    $products = array(
        array(
            'sku' => 'test',
            'qty' => 1
        )
    );
    $productsAdded = $client->addProductsToCart($cartId, $products);
    if ($productsAdded === true) {
        echo "Added the products to the cart.<br>";
    } else {
        throw new Exception("Products could not be added to cart.");
    }
    
    $customer = array(
        'firstname' => 'John',
        'lastname' => 'Doe',
        'email' => 'posorder@company.com',
        'mode' => 'guest'
    );
    $customerSet = $client->setCustomerForCart($cartId, $customer);
    if ($customerSet === true) {
        echo "Set the customer for the cart.<br>";
    } else {
        throw new Exception("Customer could not be set for the cart.");
    }
    
    $billingAdress = array(
        'mode' => 'billing',
        'firstname' => 'John',
        'lastname' => 'Doe',
        'street' => ' Street 123',
        'city' => 'Vienna',
        'region' => 'WI',
        'postcode' => '1170',
        'country_id' => 'AT',
        'telephone' => '+431234567890',
        'is_default_billing' => 1
    );
    $shippingAddress = array(
        'mode' => 'shipping',
        'firstname' => 'John',
        'lastname' => 'Doe',
        'street' => ' Street 123',
        'city' => 'Vienna',
        'region' => 'WI',
        'postcode' => '1170',
        'country_id' => 'AT',
        'telephone' => '+431234567890',
        'is_default_shipping' => 1
    ); 
    $addresses = array(
        $billingAdress,
        $shippingAddress
    );
    $addressesAdded = $client->addAddressesToCart($cartId, $addresses);
    if ($addressesAdded === true) {
        echo "Added the billing and shipping address to the cart.<br>";
    } else {
        throw new Exception("Couldn't add the billing and shipping address to the cart.<br>");
    }
    
    $shippingMethodSet = $client->setShippingMethodForCart($cartId, 'flatrate_flatrate');
    if ($shippingMethodSet === true) {
        echo "Set the shipping method.<br>";
    } else {
        throw new Exception("Couldn't set the shipping method.<br>");
    }
    
    $paymentMethodSet = $client->setPaymentMethodForCart($cartId, 'checkmo');
    if ($paymentMethodSet === true) {
        echo "Set the payment method.<br>";
    } else {
        throw new Exception("Couldn't set the payment method.<br>");
    }
    
    $orderId = $client->createOrderFromCart($cartId);
    echo "Created the order. ID: {$orderId}<br>";
    
} catch (Exception $e) {
    $client->printException($e);
}

