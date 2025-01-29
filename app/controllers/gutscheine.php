<?php
require_once ONEPLUS_DIR_PATH . '/vendor/autoload.php';

use PayPal\Api\Amount;
use PayPal\Api\InvoiceAddress;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Presentation;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ShippingAddress;
use PayPal\Api\ShippingInfo;
use PayPal\Api\Transaction;
use PayPal\Api\WebProfile;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class gutscheine extends Controller
{
    /**
     * @var string
     */
    public $aprovalUrl = "";
    /**
     * @var mixed
     */
    protected $config;
    /**
     * @var mixed
     */
    protected $apiContext;

    //region Log levels
    /*
     * Logging level can be one of FINE, INFO, WARN or ERROR.
     * Logging is most verbose in the 'FINE' level and decreases as you proceed towards ERROR.
     */
    const LOG_LEVEL_FINE = 'FINE';
    const LOG_LEVEL_INFO = 'INFO';
    const LOG_LEVEL_WARN = 'WARN';
    const LOG_LEVEL_ERROR = 'ERROR';
    const LOG_LEVEL_DEBUG = 'DEBUG';
    const CURRENCY_CODE = "EUR";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return bool
     */
    public function validateRequiredSDK()
    {
        return (class_exists("\\PayPal\\Rest\\ApiContext") && class_exists("\\PayPal\\Auth\\OAuthTokenCredential")) ? true : false;
    }

    /**
     * @return \PayPal\Rest\ApiContext
     */
    public function getApiContext()
    {

        if ($this->apiContext) {
            return $this->apiContext;
        }

        if ($this->f3->get('paypal_plus.paypal_plus_endpoint') == "production") {
            $mode = "live";
            $clientId = $this->f3->get('paypal_plus.paypal_plus_client_id');
            $clientSecret = $this->f3->get('paypal_plus.paypal_plus_account_id');
        } else {
            $mode = "sandbox";
            $clientId = "AWlKuhcjbdFbpqSNVnUHrreLXSPIZFUzvJVt9e6dpPhZTiwdBVV81WVOf43ffR-WX-HQ5oCNA7sxpuQ6";
            $clientSecret = "EJtrLjKXjb96GlYAvIU3B74BPODNkAIYi90-T1w_vqWguMQX2rihM7-QdfD3JTTwAuhUJFrrHtyvH0O_";
        }

        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $clientId,
                // ClientID
                $clientSecret // ClientSecret
            )
        );
        // $this->apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', 'Bloomsstore_POS');
        // https://stackoverflow.com/questions/2031163/when-to-use-the-different-log-levels
        // https://coderoad.ru/45077865/PayPal-PHP-SDK-%D1%81%D0%B1%D0%BE%D0%B9-%D0%BF%D0%BB%D0%B0%D1%82%D0%B5%D0%B6%D0%B0-%D0%BD%D0%B0-500
        $this->apiContext->setConfig(
            // 'log.LogLevel' => 'DEBUG', 'cache.enabled' => true
            // 'log.LogLevel' => 'FINE'
            array(
                'mode' => $mode,
                'localcode' => 'de-DE',
                'log.LogEnabled' => true,
                'log.FileName' => ONEPLUS_DIR_PATH . '/' . date('d.m.Y') . '_card_paypal_plus.log',
                'log.LogLevel' => 'INFO'

            )
        );
        return $this->apiContext;
    }

    public function session()
    {
        // http://localhost/f3-url-shortener/termine/confirmTabtwo?test=1&option_salon=25&servicePackage=2

        $kliz_sel = ['error' => true, 'html' => ''];

        header('Content-Type: application/json');

        $test = (int) helperblooms::inGet('test', 0);
        // if (true) {
        if ($test || $this->f3->get('AJAX')) {

            if ($test || $this->f3->exists('GET.data')) {

                $this->f3->clear('SESSION.paypal');
                $this->f3->clear('SESSION.coupondata');
                $this->f3->clear('SESSION.giftcouponcode');

                // $data_get = $this->f3->get('GET');
                // file_put_contents ( ONEPLUS_DIR_PATH ."/session.txt" , var_export( $data_get , true), LOCK_EX );

                // array (
                //     'format' => 'JSON',
                //     'data' =>
                //     array (
                //       'vorname' => 'oppo',
                //       'strasse' => 'berlin , strasse 23',
                //       'plz' => '1234',
                //       'ort' => 'berlin',
                //       'email' => 'alexander@1plus-agency.com',
                //       'nachname' => 'Webiprog',
                //       'diffEmail' => 'oleg@blooms.com',
                //       'emailConfirm' => 'dalexander@1plus-agency.com',
                //       'mobilenumber' => '016092884554',
                //       'h_design' => '3',
                //       'h_greetings' => 'Grußtext Grußtext',
                //       'h_shipment' => 'per Post',
                //       'h_amount' => '30,-',
                //     ),
                //     'amount' => '30,-',
                //   )

                //exit;

                $data = helperblooms::inGET('data');


                // $data["amount"] = $data["amount"];

                // $data["amount"] = substr($data["amount"], 0, -2);
                // var_dump($data["amount"]);
                // die;
                $data["amount"] = str_replace(',','.',$data["amount"]);
                $data["amount"] = helperblooms::price_format_decimal($data["amount"]);
                // die($data["amount"]);

                $downloadcoupon = null;
                if ($data["shipment"] == 'per E-Mail') {
                    $downloadcoupon = 1;
                }
                $data['download'] = $downloadcoupon;
                $this->f3->set('SESSION.coupondata', $data);

                /* @FIX by oppo (webiprog.de), @Date: 2021-11-10 18:31:50
                 * @Desc: add mode
                 */
                if ($this->f3->get('paypal_plus.paypal_plus_endpoint') == "production") {
                    $mode = "live";
                } else {
                    $mode = "sandbox";
                }
                // https://github.com/marinss993/shopclear/blob/07c75726d7b7e0486dd9f48588e4e76416c10bc4/components/com_jshopping/payments/addon_api/pm_paypal_plus.php
                $this->aprovalUrl = $this->getPayPalPlus();
                // die($this->aprovalUrl);

                $paypalPlusJsData = '<script type="application/javascript">
                var ppp = PAYPAL.apps.PPP({
                "approvalUrl": "' . $this->aprovalUrl . '",
                "placeholder": "ppplus",
                "mode": "' . $mode . '",
                "country": "DE",
                "language": "de_DE",
                "useraction":"commit",
                "showLoadingIndicator":true,
                "buttonLocation":"inside", // outside
                "showPuiOnSandbox":true,
                "preselection":"paypal"
                });
                </script>';

                // $amount = helperblooms::inGET('amount');
                $kliz_sel = ['error' => false, 'html' => true, 'paypalPlusJsData' => $paypalPlusJsData];
            } else {
                // die("No data");
            }

        }
        print json_encode($kliz_sel, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function index()
    {

        $active_tab = 'pills-abgeschlossen';
        $active_tab = 'pills-gutscheinauswhal';
        // 'pills-gutscheinauswhal'

        $active_tab_sess = $this->f3->get('SESSION.ACTIVE_TAB');

        if ($active_tab_sess) {
            $this->f3->set('ACTIVE_TAB', $active_tab_sess);
            $this->f3->clear('SESSION.ACTIVE_TAB');
            unset($active_tab_sess);
        } else {
            $this->f3->set('ACTIVE_TAB', $active_tab);
        }
        //pills-abgeschlossen last tab demo
        // $this->f3->set('ACTIVE_TAB', 'pills-abgeschlossen');

        $this->f3->set('isHomePage', false);
        $this->f3->set('title', "Gutscheine");
        $this->f3->set('view', 'gutscheine.html');
        $this->f3->set('classfoot', 'gutscheine');

        $addscripts[] = 'js/layout/jquery.validate.min.js';
        $addscripts[] = 'js/layout/additional-methods.min.js';
        $addscripts[] = 'js/layout/messages_de.js';
        $addscripts[] = 'js/layout/jquery.bootstrap.wizard.min.js';
        $addscripts[] = 'js/layout/jquery.textareaCounter.plugin-min.js';
        $addscripts[] = 'js/layout/gutscheine.js';
        $this->f3->set('addscripts', $addscripts);

        $extremalscripts = '
    <!-- PayPal PLUS Script -->
    <script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js" type="text/javascript"></script>
   ';

        $this->f3->set('addextremalscripts', $extremalscripts);

        $options = array();
        $options['Herr'] = 'Herr';
        $options['Frau'] = 'Frau';
        $options['Diverse'] = 'Diverse';
        // $options['Familie']       = 'Familie';

        $this->f3->set('OPTION_ANREDE', $options);

        if ($this->f3->exists('SESSION.giftcouponcode')) {
            $this->f3->set('giftcouponcode', $this->f3->get('SESSION.giftcouponcode'));
            // $this->f3->set('ACTIVE_TAB', 'pills-abgeschlossen');
        }
    }

    /**
     * @var mixed
     */
    private $paypal;

    public function setUpPayPal()
    {

        // ; 'receiver_email' => 'antonshel-facilitator@gmail.com',
        // ; 'environment_mode' => 'sandbox',
        // ; 'username' => 'antonshel-facilitator_api1.gmail.com',
        // ; 'password' => 'LG4QW966KSZEDN22',
        // ; 'signature' => 'AFcWxV21C7fd0v3bYYYRCpSSRl31AvjKv59PR5PzFuyoTu9JpOggLUCs'

        // oleg-buyer@webiprog.com
        // kupi@webiprog.com

        // https: //www.paypal.com/businessprofile/mytools/apiaccess/firstparty/signature

        //         API пользователя
        // marketing2_api1.bloom-s.de

        // API пароль
        // R96HHLG69B6H5W5S

        // подпись
        // AFbtoae17G1snrl8ZQGTOr-6vaYpA-lKT3SoQFN1ys766R8QabYRm9IG

        // дата запроса28 февраля 2020 года в 13:15:48 CET

        // !!!!!!! https://developer.paypal.com/docs/archive/nvp-soap-api/
        // !!!! https://www.templatemonster.com/help/ru/whats-difference-paypal-standard-express-pro.html

        // paypal_user="marketing2_api1.bloom-s.de"
        // paypal_pass="R96HHLG69B6H5W5S"
        // paypal_signature="AqoHtf-9ou72KnSG8v3.87oYuH7IAQ7o5mbgFxrHF3B8BjjvL6tkFDBc"
        // paypal_endpoint="sandbox"
        // paypal_apiver="70.0"
        // paypal_return=""
        // paypal_cancel=""
        // paypal_log="1"

        // get from config.ini
        // https://developer.paypal.com/developer/accounts
        // sb-2dljz1119179@business.example.com
        // First Name: John
        // Last Name: Doe
        // Email ID: sb-2dljz1119179@business.example.com
        // System Generated Password: V&Ed1,:@

        $user = $this->f3->get('paypal.paypal_user');
        $pass = $this->f3->get('paypal.paypal_pass');
        $signature = $this->f3->get('paypal.paypal_signature');
        $endpoint = $this->f3->get('paypal.paypal_endpoint');
        $apiver = $this->f3->get('paypal.paypal_apiver');

        $return = $this->f3->get('paypal.paypal_return');
        if (!$return) {
            // $return = $this->home_url;
            $return = $this->home_url . '/gutscheine/complete';
        } else {
            $return = $this->home_url . '/' . ltrim($return, '/');
        }

        $cancel = $this->f3->get('paypal.paypal_cancel');
        if (!$cancel) {
            $cancel = $this->home_url;
        } else {
            $cancel = $this->home_url . '/' . ltrim($cancel, '/');
        }

        $log = $this->f3->get('paypal.paypal_log');

        $ppconfig = array(
            'user' => $user,
            'pass' => $pass,
            'signature' => $signature,
            'endpoint' => $endpoint,
            'apiver' => $apiver,
            'return' => $return,
            'cancel' => $cancel,
            'log' => $log
        );

        $this->paypal = new \PayPal($ppconfig);
    }

    public function sale()
    {

        $this->setUpPayPal();
        //Instantiate the PayPal Class
        $paypal = $this->paypal;

        $session_coupondata = $this->f3->get('SESSION.coupondata');

        if (empty($session_coupondata)) {

            $this->f3->set('SESSION.error', 'PayPal - Datenfehler');
            $this->f3->reroute('/gutscheine.html');
        } elseif (!is_array($session_coupondata)) {
            $this->f3->set('SESSION.error', 'PayPal - Datenfehler');
            $this->f3->reroute('/gutscheine.html');
        }

        // array (
        //     'vorname' => 'oppo',
        //     'strasse' => 'berlin , strasse 23',
        //     'plz' => '1234',
        //     'ort' => 'berlin',
        //     'email' => 'alexander@1plus-agency.com',
        //     'nachname' => 'Webiprog',
        //     'diffEmail' => 'oleg@blooms.com',
        //     'emailConfirm' => 'dalexander@1plus-agency.com',
        //     'mobilenumber' => '016092884554',
        //     'h_design' => '2',
        //     'h_greetings' => 'Grußtext Grußtext',
        //     'h_shipment' => 'per Post',
        //     'h_amount' => '125.00',
        //   )

        $amount = bloomArrayHelper::getValueJoom($session_coupondata, 'amount', null, 'STRING');
        $paymentaction = "Sale"; // Can be Sale or Authorization
        $currencycode = "EUR"; // 3 Character currency code
        // $amount = '100.00'; // Amount to charge

        // $result = $paypal->dcc($paymentaction, $currencycode, $amount, $cardtype, $cardnumber, $expdate, $cvv, $ipaddress);
        $options = array(
            'BRANDNAME' => 'Bloom Frisure',
            'LOCALECODE' => 'DE',
            'PAYMENTREQUEST_0_CUSTOM' => $amount,
            'REQCONFIRMSHIPPING' => '0',
            'NOSHIPPING' => '1',
            'ALLOWNOTE' => '1',
            'HDRIMG' => $this->home_url . '/assets/images/blooms-logo.png',
            'PAYFLOWCOLOR' => '#000'
        );

        $result = $paypal->create("Sale", self::CURRENCY_CODE, $amount, $options);

        // $result will contain an associative array of the API response.  Store the useful bits like status & transaction ID.

        if ($result['ACK'] != 'Success' && $result['ACK'] != 'SuccessWithWarning') {
            // Handle API error code
            die('Error with API call - ' . $result["L_ERRORCODE0"]);
            // $c = 'Error with API call - '.$result["L_ERRORCODE0"];
            // $this->f3->set( 'ERROR', array( 'text' => $c ) );
            // $this->f3->set( 'ERROR', array( 'code' => 404, 'text' => $c, 'status' => 'PayPalNot Found' ) );
            // $this->f3->error( 404 );
        } else {
            // exit(print_r($result));

            $this->f3->set('SESSION.paypal', ['token' => $result['TOKEN'], 'coupondata' => $session_coupondata]);

            // Redirect Buyer to PayPal
            $this->f3->reroute($result['redirect']);
            //['paypal']['token']

            // Array ( [TOKEN] => EC-1MV4621295915373C [TIMESTAMP] => 2020-03-09T14:14:39Z [CORRELATIONID] => 6cc0b7e683d86 [ACK] => Success [VERSION] => 204.0 [BUILD] => 54296257 [redirect] => https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=EC-1MV4621295915373C ) 1
        }
    }

    public function complete()
    {

        // grab token & PayerID from URL
        $token = $this->f3->get('GET.token');
        $payerid = $this->f3->get('GET.PayerID');

        // complete the transaction
        $this->setUpPayPal();
        //Instantiate the PayPal Class
        $paypal = $this->paypal;

        $result = $paypal->complete($token, $payerid);

        if ($result['ACK'] != 'Success' && $result['ACK'] != 'SuccessWithWarning') {
            // Handle API error code
            die('Error with API call - ' . $result["L_ERRORCODE0"]);
            // $c = 'Error with API call - '.$result["L_ERRORCODE0"];
            // $this->f3->set( 'ERROR', array( 'text' => $c ) );
            // $this->f3->set( 'ERROR', array( 'code' => 404, 'text' => $c, 'status' => 'PayPalNot Found' ) );
            // $this->f3->error( 404 );
        } else {

            // , ['token' => $result['TOKEN']]
            $token_session = $this->f3->get('SESSION.paypal');

            $tiz = ['payerid' => $payerid, 'result' => $result];
            if (!empty($token_session) && is_array($token_session)) {

                $tiz = array_merge($tiz, $token_session);
            }

            $this->f3->set('SESSION.paypal', $tiz);

            // Update back office - save transaction id, payment status etc
            // Display thank you/receipt to the buyer.
            $this->f3->set('itemcount', 0);
            $this->f3->set('txnid', $result['PAYMENTINFO_0_TRANSACTIONID']);
            // echo \Template::instance()->render('gutscheine.html');

            // array (
            //     'payerid' => 'A7Y4A3YTX6FKC',
            //     'result' =>
            //     array (
            //       'TOKEN' => 'EC-8CT21823YN5764921',
            //       'SUCCESSPAGEREDIRECTREQUESTED' => 'false',
            //       'TIMESTAMP' => '2020-03-10T15:30:58Z',
            //       'CORRELATIONID' => 'b743444000802',
            //       'ACK' => 'Success',
            //       'VERSION' => '204.0',
            //       'BUILD' => '54296257',
            //       'INSURANCEOPTIONSELECTED' => 'false',
            //       'SHIPPINGOPTIONISDEFAULT' => 'false',
            //       'PAYMENTINFO_0_TRANSACTIONID' => '6N828890VR427831M',
            //       'PAYMENTINFO_0_TRANSACTIONTYPE' => 'expresscheckout',
            //       'PAYMENTINFO_0_PAYMENTTYPE' => 'instant',
            //       'PAYMENTINFO_0_ORDERTIME' => '2020-03-10T15:30:56Z',
            //       'PAYMENTINFO_0_AMT' => '30.00',
            //       'PAYMENTINFO_0_FEEAMT' => '0.92',
            //       'PAYMENTINFO_0_TAXAMT' => '0.00',
            //       'PAYMENTINFO_0_CURRENCYCODE' => 'EUR',
            //       'PAYMENTINFO_0_PAYMENTSTATUS' => 'Completed',
            //       'PAYMENTINFO_0_PENDINGREASON' => 'None',
            //       'PAYMENTINFO_0_REASONCODE' => 'None',
            //       'PAYMENTINFO_0_PROTECTIONELIGIBILITY' => 'Ineligible',
            //       'PAYMENTINFO_0_PROTECTIONELIGIBILITYTYPE' => 'None',
            //       'PAYMENTINFO_0_SELLERPAYPALACCOUNTID' => 'sb-2dljz1119179@business.example.com',
            //       'PAYMENTINFO_0_SECUREMERCHANTACCOUNTID' => 'LVUTNUTBF2KTA',
            //       'PAYMENTINFO_0_ERRORCODE' => '0',
            //       'PAYMENTINFO_0_ACK' => 'Success',
            //     ),
            //   )

            // get coupon data from API
            $this->bugzilla();

            // set to last tab
            $this->f3->set('SESSION.ACTIVE_TAB', 'pills-abgeschlossen');

            $this->f3->reroute('/gutscheine.html');

            // $this->f3->set('ACTIVE_TAB', 'pills-gutscheinauswhal');
            // $this->index();
        }
    }

    public function bugzilla()
    {

        $session_coupondata = $this->f3->get('SESSION.coupondata');

        if (empty($session_coupondata)) {

            $this->f3->set('SESSION.error', 'Gutscheine - Datenfehler');
            $this->f3->reroute('/gutscheine.html');
        } elseif (!is_array($session_coupondata)) {
            $this->f3->set('SESSION.error', 'Gutscheine - Datenfehler');
            $this->f3->reroute('/gutscheine.html');
        }

        // array (
        //     'vorname' => 'oppo',
        //     'strasse' => 'berlin , strasse 23',
        //     'plz' => '1234',
        //     'ort' => 'berlin',
        //     'email' => 'alexander@1plus-agency.com',
        //     'nachname' => 'Webiprog',
        //     'diffEmail' => 'oleg@blooms.com',
        //     'emailConfirm' => 'dalexander@1plus-agency.com',
        //     'mobilenumber' => '016092884554',
        //     'h_design' => '2',
        //     'h_greetings' => 'Grußtext Grußtext',
        //     'h_shipment' => 'per Post',
        //     'h_amount' => '125.00',
        //   )

        // sess persDataConfirm: {"email":"oleg@blooms.com","vorname":"oppo","strasse":"berlin , strasse 23","plz":"1234","ort":"berlin","nachname":"OPOPOPOPO","salutation":"Herr","diffAdress":true,"diffEmail":"oleg@blooms.com","h_design":2,"h_greetings":"Grußtext Grußtext","h_shipment":"per Post","h_amount":"30,-"}

        $gutscheinemodel = new gutscheineModel();

        $giftCouponValue = bloomArrayHelper::getValueJoom($session_coupondata, 'amount', null, 'STRING');
        $giftCouponMessage = bloomArrayHelper::getValueJoom($session_coupondata, 'greetings', null, 'STRING');
        $diffAdress = bloomArrayHelper::getValueJoom($session_coupondata, 'diffAdress', null, 'STRING');
        $notificationEmail = bloomArrayHelper::getValueJoom($session_coupondata, 'email', null, 'STRING');
        $phoneInvoice = bloomArrayHelper::getValueJoom($session_coupondata, 'phone', null, 'STRING');
        $salutation = bloomArrayHelper::getValueJoom($session_coupondata, 'salutation', null, 'STRING');
        $firstName = bloomArrayHelper::getValueJoom($session_coupondata, 'vorname', null, 'STRING');
        $lastName = bloomArrayHelper::getValueJoom($session_coupondata, 'nachname', null, 'STRING');
        $street = bloomArrayHelper::getValueJoom($session_coupondata, 'adresse', null, 'STRING');
        $zipCode = bloomArrayHelper::getValueJoom($session_coupondata, 'plz', null, 'STRING');
        $city = bloomArrayHelper::getValueJoom($session_coupondata, 'ort', null, 'STRING');
        $design = bloomArrayHelper::getValueJoom($session_coupondata, 'design', null, 'STRING');

        $shipment = bloomArrayHelper::getValueJoom($session_coupondata, 'shipment', null, 'STRING');
        $emailDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffEmail', null, 'STRING');
        $salutationDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffsalutation', null, 'STRING');
        $firstNameDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffvorname', null, 'STRING');
        $lastNameDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffnachname', null, 'STRING');
        $streetDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffadresse', null, 'STRING');
        $cityDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffort', null, 'STRING');
        $zipCodeDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffplz', null, 'STRING');
        $phoneDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffphone', null, 'STRING');

        $giftCouponMessage = strip_tags($giftCouponMessage);
        $delivery = 'Invoice'; // Default Email
        $content = array(
            'giftCouponValue' => $giftCouponValue,
            'giftCouponMessage' => $giftCouponMessage,
            'notificationEmail' => $notificationEmail,
            'salutation' => $salutation,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'street' => $street,
            'zipCode' => $zipCode,
            'city' => $city,
            'paymentType' => 'PayPal',
            'templateName' => 'email_layout_' . $design
        );
        if ($shipment == 'per E-Mail' && $diffAdress == '1') {
            $content['emailDeliveryAddress'] = $emailDelivery;
        }
        if ($shipment == 'per Post') {
            $delivery = 'InvoicePostal';
            $content = array(
                'giftCouponValue' => $giftCouponValue,
                'giftCouponMessage' => $giftCouponMessage,
                'emailInvoice' => $notificationEmail,
                'salutationInvoice' => $salutation,
                'firstNameInvoice' => $firstName,
                'phoneInvoice' => $phoneInvoice,
                'lastNameInvoice' => $lastName,
                'streetInvoice' => $street,
                'zipCodeInvoice' => $zipCode,
                'cityInvoice' => $city,
                'emailDelivery' => $notificationEmail,
                'firstNameDelivery' => $firstName,
                'lastNameDelivery' => $lastName,
                'streetDelivery' => $street,
                'cityDelivery' => $city,
                'zipCodeDelivery' => $zipCode,
                'phoneDelivery' => $phoneDelivery,
                'paymentType' => 'PayPal',
                'templateName' => 'email_layout_' . $design,
                'shippingValue' => '0.00'

            );
            if ($diffAdress == '1') {
                $content['firstNameDelivery'] = $firstNameDelivery;
                $content['lastNameDelivery'] = $lastNameDelivery;
                $content['streetDelivery'] = $streetDelivery;
                $content['cityDelivery'] = $cityDelivery;
                $content['zipCodeDelivery'] = $zipCodeDelivery;
                $content['phoneDelivery'] = $phoneDelivery;
            }
        }

        $jsondata = $gutscheinemodel->getGutscheineCode($content, $delivery);

        // file_put_contents(ONEPLUS_DIR_PATH . "/GutscheineCode_content.txt", var_export($content, true), LOCK_EX);
        // file_put_contents(ONEPLUS_DIR_PATH . "/GutscheineCode_jsondata.txt", var_export($jsondata, true), LOCK_EX);

        // '{"GiftCouponCode":"J8LELT","GiftCouponPdfFileUri":null,"InvoicePdfFileUri":null,"SendMailSucceeded":false,"AdditionalInfo":"Invoice PDF file generation failed. ","ReturnCodeValue":16,"ReturnValueText":"CheckoutSavedButPdfGenerationFailed"}'

        if (!empty($jsondata) && is_array($jsondata)) {
            # code...

            $this->f3->set('SESSION.giftcouponcode', $jsondata);

            $coupon_save = $gutscheinemodel->gutscheine_mapper;

            $session_paypal = $this->f3->get('SESSION.paypal');
            $txnid = $session_paypal['result']['PAYMENTINFO_0_TRANSACTIONID'];
            // 'PAYMENTINFO_0_TRANSACTIONID' => '45Y65678BH330693H',
            // 'PAYMENTINFO_0_TRANSACTIONTYPE' => 'expresscheckout',

            $date = date('Y-m-d H:i:s', strtotime($session_paypal['result']['PAYMENTINFO_0_ORDERTIME']));

            $coupon_array = array_merge($content, ['txnid' => $txnid], ['date' => $date]);

            // array (
            //     'giftCouponValue' => '30.00',
            //     'giftCouponMessage' => 'oleg@blooms.com',
            //     'notificationEmail' => 'Grußtext Grußtext',
            //     'salutation' => 'Herr',
            //     'firstName' => 'oppo',
            //     'lastName' => 'OPOPOPOPO',
            //     'street' => 'berlin , strasse 23',
            //     'zipCode' => '1234',
            //     'city' => 'berlin',
            //     'paymentType' => 'PayPal',
            //     'templateName' => 'email_layout_2',
            //     'txnid' => '45Y65678BH330693H',
            //   )

            $coupon_save->copyFrom((array) $coupon_array);
            $coupon_save->save();

            return true;
        }

        // $biba = range(0, 180, 10);
        // $current = 0;
        // foreach ($biba as $key => $value) {
        //     $current++;
        //     $gutscheinemodel::outputProgress($current, count($biba));
        // }

        // array (
        //     'GiftCouponCode' => 'IKWYDX',
        //     'GiftCouponPdfFileUri' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf?/blooms_gutschein_015_uQdp25ipk6w67TdbbEZg.pdf',
        //     'InvoicePdfFileUri' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf?/blooms_rechnung_015_uQdp25ipk6w67TdbbEZg.pdf',
        //     'SendMailSucceeded' => true,
        //     'AdditionalInfo' => NULL,
        //     'ReturnCodeValue' => 0,
        //     'ReturnValueText' => 'AllFine',
        //   )

    }

    public function sys()
    {
        file_put_contents(ONEPLUS_DIR_PATH . "/bugzilla.txt", var_export($_REQUEST, true), LOCK_EX);
        exit;
    }

    public function koba()
    {

        // Card Type: Visa

        // Card Number: 4516218912883573

        // Expiration Date: 09/2023

        // CVV: 231

        // *******************************************

        // покупатель
        // Email ID: oleg-buyer@webiprog.com

        // Email ID:
        // kupi@webiprog.com
        // продавец начальник
        // oleg-facilitator@webiprog.com

        // Pass: oleg@webiprog.com

        // /////////          СИНГАТУРА    oleg-facilitator@webiprog.com            //////////
        // oleg-facilitator@webiprog.com

        // CredentialSignature
        // API Username
        // oleg-facilitator_api1.webiprog.com

        // API Password
        // ZRDYFFLGXBN3MHEB

        // Signature
        // AiPC9BjkCyDFQXbSkoZcgqH3hpacA7NWVgitm6Zx43bnPS9kRceT8Cnh

        $this->setUpPayPal();
        //Instantiate the PayPal Class
        $paypal = $this->paypal;

        $paymentaction = "Sale"; // Can be Sale or Authorization
        $currencycode = self::CURRENCY_CODE; // 3 Character currency code
        $amount = '100.00'; // Amount to charge
        $cardtype = 'Visa'; // Visa, MasterCard, Discover etc
        $cardnumber = '4209769767395628'; // Valid card number
        // $expdate = '122020'; // format MMYYYY
        $expdate = '052023'; // format MMYYYY 09/2023
        $cvv = '252'; // Valid security code
        $ipaddress = '127.0.0.1';

        // $result = $paypal->dcc($paymentaction, $currencycode, $amount, $cardtype, $cardnumber, $expdate, $cvv, $ipaddress);

        $options = array(
            'BRANDNAME' => 'Bloom Frisure',
            'LOCALECODE' => 'DE',
            'PAYMENTREQUEST_0_CUSTOM' => $amount,
            'REQCONFIRMSHIPPING' => '0',
            'NOSHIPPING' => '1',
            'ALLOWNOTE' => '1'
        );

        $result = $paypal->create("Sale", self::CURRENCY_CODE, $amount, $options);

        // $result will contain an associative array of the API response.  Store the useful bits like status & transaction ID.

        if ($result['ACK'] != 'Success' && $result['ACK'] != 'SuccessWithWarning') {
            // Handle API error code
            die('Error with API call - ' . $result["L_ERRORCODE0"]);
        } else {
            exit(print_r($result));
            // Array ( [TOKEN] => EC-1MV4621295915373C [TIMESTAMP] => 2020-03-09T14:14:39Z [CORRELATIONID] => 6cc0b7e683d86 [ACK] => Success [VERSION] => 204.0 [BUILD] => 54296257 [redirect] => https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=EC-1MV4621295915373C ) 1
        }
    }

    /**
     * encode_utf8
     */
    private function encode_utf8($in_str)
    {
        $cur_encoding = mb_detect_encoding($in_str);
        if ($cur_encoding == "UTF-8" && mb_check_encoding($in_str, "UTF-8")) {
            return $in_str;
        } else {
            return mb_convert_encoding($in_str, "UTF-8", "ISO-8859-15");
        }
    }

    /**
     * Creates and returns a Presentation object
     *
     * @return Presentation
     */
    private function get_presentation()
    {
        $presentation = new Presentation();

        $checkout_logo = $this->f3->get('paypal_plus.paypal_plus_checkout_logo');
        $brand_name = $this->f3->get('paypal_plus.paypal_plus_brand_name');
        if (!empty($checkout_logo)) {
            $url = str_ireplace('http://', 'https://', $this->home_url);
            $checkout_logo = $url . '/' . $checkout_logo;
            $presentation->setLogoImage($checkout_logo);
        }
        if (!empty($brand_name)) {
            $presentation->setBrandName($brand_name);
        }

        return $presentation;
    }

    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $businessName
     * @param \PayPal\Api\Phone $phone
     * @param \PayPal\Api\InvoiceAddress $invoiceAddress
     * @return \PayPal\Api\ShippingInfo
     */
    public function createShippingInfo($firstname, $lastname, $businessName, $phone, $invoiceAddress)
    {
        $shipping = new ShippingInfo();
        $shipping->setFirstName($firstname)
            ->setLastName($lastname)
            ->setBusinessName($businessName)
            ->setPhone($phone)
            ->setAddress($invoiceAddress);
        return $shipping;
    }

    /**
     * @param string $line1
     * @param string $line2
     * @param string $city
     * @param string $state
     * @param string $postalCode
     * @param string $countryCode
     * @return \PayPal\Api\InvoiceAddress
     */
    public function createInvoiceAddress($line1, $line2 = '', $city = '', $state = '', $postalCode = '', $countryCode = 'DE')
    {
        $address = new InvoiceAddress();
        $address->setLine1($line1)->setLine2($line2)
            ->setCity($city)
            ->setState($state)
            ->setPostalCode($postalCode)
            ->setCountryCode($countryCode);
        return $address;
    }

    /**
     * @return mixed
     */
    public function getPayPalPlus()
    {

        $paypal_plus_return = $this->home_url . '/' . $this->f3->get('paypal_plus.paypal_plus_return');
        $paypal_plus_cancel = $this->home_url . '/' . $this->f3->get('paypal_plus.paypal_plus_cancel');

        // $apiContext = $this->payPal;
        $apiContext = $this->getApiContext();

        $web_profile = new WebProfile();
        $brand_name = $this->f3->get('paypal_plus.paypal_plus_brand_name');
        $web_profile
            ->setName(substr($brand_name . uniqid(), 0, 50))
            ->setPresentation($this->get_presentation());
        $createProfile = $web_profile->create($apiContext);

        $session_coupondata = $this->f3->get('SESSION.coupondata');

        $notificationEmail = bloomArrayHelper::getValueJoom($session_coupondata, 'email', null, 'STRING');
        $phoneInvoice = bloomArrayHelper::getValueJoom($session_coupondata, 'phone', null, 'STRING');
        $salutation = bloomArrayHelper::getValueJoom($session_coupondata, 'salutation', null, 'STRING');
        $firstName = bloomArrayHelper::getValueJoom($session_coupondata, 'vorname', null, 'STRING');
        $lastName = bloomArrayHelper::getValueJoom($session_coupondata, 'nachname', null, 'STRING');
        $street = bloomArrayHelper::getValueJoom($session_coupondata, 'adresse', null, 'STRING');
        $zipCode = bloomArrayHelper::getValueJoom($session_coupondata, 'plz', null, 'STRING');
        $city = bloomArrayHelper::getValueJoom($session_coupondata, 'ort', null, 'STRING');

        $streetDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffadresse', null, 'STRING');
        $cityDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffort', null, 'STRING');
        $zipCodeDelivery = bloomArrayHelper::getValueJoom($session_coupondata, 'diffplz', null, 'STRING');

        // $shippingName = $firstName . ' ' . $lastName;
        // $shippingAddress = $this->createInvoiceAddress(
        //     (!empty($street)) ? $street : '',
        //     (!empty($streetDelivery)) ? $streetDelivery : '',
        //     ($city) ? $city : '',
        //     '',
        //     ($zipCode) ? $zipCode : '',
        //     'DE'
        // );

        // $shippingInfo = $this->createShippingInfo($firstName, $lastName, $shippingName, $phoneInvoice, $shippingAddress);

        $design = bloomArrayHelper::getValueJoom($session_coupondata, 'design', null, 'STRING');
        $design_arr = [1 => 'Geburtstag', 2 => 'Neutral', 3 => 'Weihnachten'];

        $amountPrice = bloomArrayHelper::getValueJoom($session_coupondata, 'amount', null, 'STRING');
        $paymentaction = "Sale"; // Can be Sale or Authorization
        if (isset($design_arr[$design])) {
            $paymentaction = $design_arr[$design];
        }

        $currencycode = self::CURRENCY_CODE; // 3 Character currency code

        $item_design = new \PayPal\Api\Item();
        $item_design->setName($paymentaction)
            ->setCurrency($currencycode)
            ->setQuantity(1)
            ->setPrice($amountPrice)
            ->setSku('email_layout_' . $design);

        // $item[$i] = new Item();
        // $item[$i]->setName($this->encode_utf8($products[$i]['name']))
        //          ->setCurrency($_SESSION['currency'])
        //          ->setQuantity($products[$i]['quantity'])
        //          ->setPrice($products[$i]['price'])
        //          ->setSku($products[$i]['model']);

        $itemList = new \PayPal\Api\ItemList();
        $itemList->setItems(array($item_design));

        // set address
        $shipping_address = new ShippingAddress();

        $shippingName = $firstName . ' ' . $lastName;
        $shipping_address->setRecipientName($shippingName)
            ->setLine1('DUMMY STREET')
            ->setCity('DUMMY CITY')
            ->setCountryCode('DE')
            ->setPostalCode('12345')
            ->setState('');

        $itemList->setShippingAddress($shipping_address);

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency('EUR');
        $amount->setTotal($amountPrice);

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);
        $transaction->setItemList($itemList);
        $transaction->setInvoiceNumber(uniqid());
        //$transaction->setDescription('Description');

        $redirectUrl = new \PayPal\Api\RedirectUrls();

        $redirectUrl->setReturnUrl($paypal_plus_return);
        $redirectUrl->setCancelUrl($paypal_plus_cancel);

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale');
        $payment->setPayer($payer);
        $payment->setRedirectUrls($redirectUrl);
        $payment->setTransactions(array($transaction));

        try {

            $payment->create($apiContext);
        } catch (PayPalConnectionException $exception) {
            // echo $exception->getCode(); // Prints the Error Code
            // echo $exception->getData(); // Prints the detailed error message
            die($exception->getData());
            $this->f3->set('SESSION.error', 'PayPal ' . $exception->getData());
            file_put_contents(ONEPLUS_DIR_PATH . "/payment_ERROR.txt", var_export($exception, true), LOCK_EX);
            $this->f3->reroute('/gutscheine.html');
            exit(1);
        } catch (Exception $e) {
            // echo $e->getMessage();
            die($e->getMessage());
            $this->f3->set('SESSION.error', 'PayPal ' . $e->getMessage());
            file_put_contents(ONEPLUS_DIR_PATH . "/payment_ERROR.txt", var_export($e, true), LOCK_EX);
            $this->f3->reroute('/gutscheine.html');
            exit(1);
        }

        $approvalUrl = $payment->getApprovalLink();

        return $approvalUrl;
    }

    /**
     * @param string $paymentId
     * @param string $payerId
     * @return string
     * @throws \Exception
     */
    public function completePayment($paymentId, $payerId)
    {
        $apiContext = $this->getApiContext();
        $payment = Payment::get($paymentId, $apiContext);
        $execution = new PaymentExecution();
        $execution->setPayerId($payerId);
        // if ('created' == $payment->getState()) {
        //     $execution = new PaymentExecution();
        //     $execution->setPayerId($payment->getPayer()->getPayerInfo()->getPayerId());
        //     $payment->execute($execution, $apiContext);
        // }

        $test = 0;

        /* How do I confirm a debit or credit card that I've linked to my PayPal account?
        https://www.paypal.com/gi/smarthelp/article/how-do-i-confirm-a-debit-or-credit-card-that-i've-linked-to-my-paypal-account-faq1565
        https://www.businessinsider.com/how-to-verify-paypal
        https://www.paypal-community.com/t5/About-Business-Archive/Payer-Unverified-by-PayPal/td-p/956911
        https://github.com/paypal/PayPal-PHP-SDK/issues/178
        */

        $payment_methode = $payment->payer->payment_method;
        // $buyer_name = $payment->payer->payer_info->first_name . " " . $payment->payer->payer_info->last_name;
        // $buyer_email = $payment->payer->payer_info->email;

        // foreach ($payment->transactions as $item) {

        //     $membershiptype = $item->description;
        //     $amount = $item->amount->total;
        //     $shipping_add = $item->item_list->shipping_address->line1 . "," . $item->item_list->shipping_address->city . "," . $item->item_list->shipping_address->state . " " . $item->item_list->shipping_address->postal_code . "," . $item->item_list->shipping_address->country_code;
        //     $taxamount = $item->amount->details->tax;
        // }

        $payer = $payment->getPayer();

        if ($test) {

            if (!empty($payer)) {

                file_put_contents(ONEPLUS_DIR_PATH . "/card_pplus_payer_payment_methode.txt", var_export([$payment_methode, $payer], true), FILE_APPEND | LOCK_EX);
            } else {
                file_put_contents(ONEPLUS_DIR_PATH . "/card_pplus_payment_methode.txt", var_export([$payment_methode], true), FILE_APPEND | LOCK_EX);
            }
        }

        // дочасу відлючено - непрацює з картами
        // if (!$payer || 'verified' !== strtolower($payer->getStatus())) {
        //     // throw new InvalidPaymentException('Payer not verified.');
        //     $this->f3->set('SESSION.error', 'PayPal error: Payer not verified!');
        //     $this->f3->reroute('/gutscheine.html');
        //     exit(1);
        // }

        $transactionId = '';
        try {
            $result = $payment->execute($execution, $apiContext);

            // if ('approved' != $payment->getState()) {
            //     throw new InvalidPaymentException('Invalid payment state.');
            // }

            if ($test) {
                // http://localhost/blooms/gutscheine/completeplus?paymentId=PAYID-MGGUEPI7M76059416159484G&token=EC-3CY06868SD948874D&PayerID=FM25X5L4GA5MA
                file_put_contents(ONEPLUS_DIR_PATH . "/card_pplus_result.txt", var_export($result, true), FILE_APPEND | LOCK_EX);
            }

            if ($result->state != 'approved') {
                $this->f3->set('SESSION.error', 'PayPal error: Invalid payment state!');
                $this->f3->reroute('/gutscheine.html');
                exit(1);
            }

            //exit;
            $transactions = $payment->getTransactions();
            if (!empty($transactions) && isset($transactions[0])) {

                if ($test) {
                    file_put_contents(ONEPLUS_DIR_PATH . "/card_pplus_transactions.txt", var_export($transactions, true), FILE_APPEND | LOCK_EX);
                }


                //    check Payment amount
                $transaction = $transactions[0];
                $session_coupondata = $this->f3->get('SESSION.coupondata');
                $amountPrice = bloomArrayHelper::getValueJoom($session_coupondata, 'amount', null, 'STRING');
                $currencycode = self::CURRENCY_CODE; // 3 Character currency code

                if (bccomp($transaction->amount->total, $amountPrice, 2) != 0 || $transaction->amount->currency != $currencycode) {
                    // throw new \Exception('Payment amount mismatch');
                    $this->f3->set('SESSION.error', 'Payment amount mismatch');
                    file_put_contents(ONEPLUS_DIR_PATH . "/card_pplus_amount_mismatch_ERROR.txt", var_export($transaction, true), LOCK_EX);
                    $this->f3->reroute('/gutscheine.html');
                    exit(1);
                }

                $relatedResources = $transactions[0]->getRelatedResources();
                if (!empty($relatedResources) && isset($relatedResources[0])) {
                    $sale = $relatedResources[0]->getSale();
                    $transactionId = $sale->getId();
                }
            }
        } catch (PayPalConnectionException $exception) {
            // echo $exception->getCode(); // Prints the Error Code
            // echo $exception->getData(); // Prints the detailed error message
            $this->f3->set('SESSION.error', 'PayPal ' . $exception->getData());
            file_put_contents(ONEPLUS_DIR_PATH . "/card_pplus_payment_complete_ERROR.txt", var_export($exception, true), LOCK_EX);
            $this->f3->reroute('/gutscheine.html');
            exit(1);
        } catch (Exception $e) {
            // echo $e->getMessage();
            $this->f3->set('SESSION.error', 'PayPal ' . $e->getMessage());
            file_put_contents(ONEPLUS_DIR_PATH . "/card_pplus_payment_complete_ERROR.txt", var_export($e, true), LOCK_EX);
            $this->f3->reroute('/gutscheine.html');
            exit(1);
        }
        return $transactionId;
    }

    public function completeplus()
    {
        // grab token & PayerID from URL
        // $token   = $this->f3->get('GET.token');
        $payerid = $this->f3->get('GET.PayerID');
        $token = $this->f3->get('GET.token');
        $paymentId = $this->f3->get('GET.paymentId');

        $transactionId = $this->completePayment($paymentId, $payerid);

        // array (
        //     'paymentId' => 'PAYID-MGGR43I2HN96505U1440330U',
        //     'token' => 'EC-6TJ54398MJ337434H',
        //     'PayerID' => 'FM25X5L4GA5MA',
        //   )

        // file_put_contents(ONEPLUS_DIR_PATH . "/payment_REQUEST.txt", var_export($_REQUEST, true), LOCK_EX);
        $result = [];
        $result['PAYMENTINFO_0_TRANSACTIONID'] = $transactionId;

        $token_session = $this->f3->get('SESSION.paypal');

        $tiz = ['payerid' => $payerid, 'result' => $result];
        if (!empty($token_session) && is_array($token_session)) {

            $tiz = array_merge($tiz, $token_session);
        }

        $this->f3->set('SESSION.paypal', $tiz);

        // Update back office - save transaction id, payment status etc
        // Display thank you/receipt to the buyer.
        $this->f3->set('itemcount', 0);
        $this->f3->set('txnid', $result['PAYMENTINFO_0_TRANSACTIONID']);

        // get coupon data from API
        $this->bugzilla();

        // set to last tab
        $this->f3->set('SESSION.ACTIVE_TAB', 'pills-abgeschlossen');
        $this->f3->reroute('/gutscheine.html');

        //}
    }

    /**
     * @param string $paymentId
     * @return string
     * @throws \Exception
     */
    public function completeAppPayment($paymentId)
    {
        $apiContext = $this->getApiContext();
        $payment = Payment::get($paymentId, $apiContext);

        $transactionId = '';
        try {
            $payment->get($paymentId, $apiContext);
            $transactions = $payment->getTransactions();
            if (!empty($transactions) && isset($transactions[0])) {
                $relatedResources = $transactions[0]->getRelatedResources();
                if (!empty($relatedResources) && isset($relatedResources[0])) {
                    $sale = $relatedResources[0]->getSale();
                    $transactionId = $sale->getId();
                }
            }
        } catch (\PayPal\Exception\PayPalConnectionException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
        return $transactionId;
    }

    /** @FIX by oppo (webiprog.de), @Date: 2021-11-11 18:03:37
     * @Desc: https://laracasts.com/discuss/channels/laravel/paypal-payment-validation-error-item-amount-must-add-up-to-specified-amount-subtotal
     */
    public function SalocompletePayment($paymentId, $payerId)
    {
        $payment = Payment::get($paymentId, $this->payPal);
        $execute = new PaymentExecution();
        $execute->setPayerId($payerId);

        try {
            $result = $payment->execute($execute, $this->payPal);
        } catch (PayPalConnectionException $exception) {
            $data = json_decode($exception->getData());
            $_SESSION['message'] = 'Error, ' . $data->message;
            // implement your own logic here to show errors from paypal
            exit;
        }

        if ($result->state === 'approved') {
            $transactions = $result->getTransactions();
            $transaction = $transactions[0];
            $invoiceId = $transaction->invoice_number;

            $relatedResources = $transactions[0]->getRelatedResources();
            $sale = $relatedResources[0]->getSale();
            $saleId = $sale->getId();

            $transactionData = ['salesId' => $saleId, 'invoiceId' => $invoiceId];

            return $transactionData;
        } else {
            echo "<h3>" . $result->state . "</h3>";
            var_dump($result);
            exit(1);
        }
    }
}