<?php
error_reporting(-1);
require_once (__DIR__ . '/vendor/autoload.php');
$apiContext = new \PayPal\Rest\ApiContext(
new \PayPal\Auth\OAuthTokenCredential(
'AS_Cnie6CsZs6ZwZxReV3mOWYKowKSt5qHfk-yfwYQ_6chHNky9s-MYM02LS7624lptEGVveEDMwzars' ,
'EFues4MQo5OeMa35CRIrYJjc76YpM9Fk62RXP2nmYMpbuGMT-02wuw67CImacYGGDzV3zFZtAmfQnLM6'      // ClientSecret
)
);

$apiContext->setConfig(
array(
'mode' => 'sandbox'
)
);

$item1 = new \PayPal\Api\Item();
$item1->setName('Test item')
->setCurrency('USD')
->setQuantity(1)
->setPrice(1);

$itemList = new \PayPal\Api\ItemList();
$itemList->setItems(array($item1));

$payer = new \PayPal\Api\Payer();
$payer->setPaymentMethod('paypal');

$amount = new \PayPal\Api\Amount();
$amount->setCurrency('USD');
$amount->setTotal('1');

$transaction = new \PayPal\Api\Transaction();
$transaction->setAmount($amount);
$transaction->setItemList($itemList);
//$transaction->setInvoiceNumber(uniqid());
//$transaction->setDescription('Description');

$redirectUrl = new \PayPal\Api\RedirectUrls();
$redirectUrl->setReturnUrl('http://localhost');
$redirectUrl->setCancelUrl('http://localhost');

$payment = new \PayPal\Api\Payment();
$payment->setIntent('sale');
$payment->setPayer($payer);
$payment->setRedirectUrls($redirectUrl);
$payment->setTransactions(array($transaction));

$payment = $payment->create($apiContext);

$approvalUrl = $payment->getApprovalLink();


?>
<script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js" type="text/javascript">
</script>

<div id="ppplus">
</div>

<script type="application/javascript">
    var ppp = PAYPAL.apps.PPP({
    "approvalUrl": "<?php echo $approvalUrl;?>",
    "placeholder": "ppplus",
    "mode": "sandbox",
    "country": "DE"
    });
</script>