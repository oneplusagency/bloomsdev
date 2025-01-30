<?php

//! PayPal Express Checkout & Classic API
class PayPal
{
    /**
     * @var mixed
     */
    protected $f3;
    /**
     * @var array
     */
    private $creds = array();
    /**
     * @var mixed
     */
    public $endpoint;
    /**
     * @var mixed
     */
    public $redirect;
    /**
     * @var array
     */
    public $lineitems = array();
    /**
     * @var int
     */
    public $itemcounter = 0;
    /**
     * @var int
     */
    public $itemtotal = 0;
    /**
     * @var array
     */
    public $shippingaddress = array();
    /**
     * @var mixed
     */
    public $shippingamt;
    /**
     * @var mixed
     */
    public $taxamt;
    /**
     * @var mixed
     */
    public $returnurl;
    /**
     * @var mixed
     */
    public $cancelurl;
    /**
     * @var mixed
     */
    public $logger;

    /**
     *    Class constructor
     *    Defines API endpoint, credentials, Return URL & Cancel URL
     * @param  $options array
     */
    public function __construct( $options = null )
    {
        $f3 = \Base::instance();

        $this->calc_start_session();

        $f3->sync( 'SESSION' );

        if ( $options == null ) {
            if ( $f3->exists( 'PAYPAL' ) ) {
                $options = $f3->get( 'PAYPAL' );
            } else {
                $f3->error( 500, 'No configuration options set for F3-PYPL' );
            }
        }

        if ( $options['endpoint'] == "production" ) {
            $this->endpoint = 'https://api-3t.paypal.com/nvp';
            $this->redirect = 'https://www.paypal.com/webscr&cmd=_express-checkout&token=';
        } else {
            $this->endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
            $this->redirect = 'https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=';
        }

        $this->creds['USER']      = $options['user'];
        $this->creds['PWD']       = $options['pass'];
        $this->creds['SIGNATURE'] = $options['signature'];
        $this->creds['VERSION']   = $options['apiver'];
        $this->returnurl          = $options['return'];
        $this->cancelurl          = $options['cancel'];
        if ( $options['log'] ) {
            $this->logger = new Log( 'paypal.log' );
        }
    }

        /**
     * if the session has been started yet, start it
     *
     * @return void
     */
    protected function calc_start_session()
    {
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
        } else {
            if (!session_id()) {
                session_start();
            }
        }
    }

    /**
     * Creates & parses NVP call
     * @param  $method string
     * @param  $nvp array
     * @return array
     */
    public function apireq( $method, $nvp )
    {
        $arg           = array_merge( $this->creds, $nvp );
        $arg['METHOD'] = $method;

        $options = array(
            'method'           => 'POST',
            'content'          => http_build_query( $arg ),
            'protocol_version' => 1.1
        );

        $result = \Web::instance()->request( $this->endpoint, $options );
        parse_str( $result['body'], $output );

        if ( isset( $this->logger ) ) {
            $arg['PWD']       = "*****";
            $arg['SIGNATURE'] = "*****";
            $this->logreq( "Request: ".urldecode( http_build_query( $arg ) ) );
            $this->logreq( "Response: ".urldecode( $result['body'] ) );
        }

        return ( $output );

    }

    /**
     * Build array of line items & calculating item total.
     * @param $itemname string
     * @param $itemqty integer
     * @param $itemprice string
     */
    public function setLineItem( $itemname, $itemqty = 1, $itemprice )
    {
        $i                                            = $this->itemcounter++;
        $this->lineitems["L_PAYMENTREQUEST_0_NAME$i"] = $itemname;
        $this->lineitems["L_PAYMENTREQUEST_0_QTY$i"]  = $itemqty;
        $this->lineitems["L_PAYMENTREQUEST_0_AMT$i"]  = $itemprice;
        $this->itemtotal += ( $itemqty * $itemprice );
    }

    /**
     * Set shipping address used for Express Checkout Mark.
     * @param $name string
     * @param $street1 string
     * @param $street2 string
     * @param $city string
     * @param $state string
     * @param $zip string
     * @param $countrycode string
     */
    public function setShippingAddress( $name, $street1, $street2, $city, $state, $zip, $countrycode )
    {
        $this->shippingaddress['PAYMENTREQUEST_0_SHIPTONAME']        = $name;
        $this->shippingaddress['PAYMENTREQUEST_0_SHIPTOSTREET']      = $street1;
        $this->shippingaddress['PAYMENTREQUEST_0_SHIPTOSTREET2']     = $street2;
        $this->shippingaddress['PAYMENTREQUEST_0_SHIPTOCITY']        = $city;
        $this->shippingaddress['PAYMENTREQUEST_0_SHIPTOSTATE']       = $state;
        $this->shippingaddress['PAYMENTREQUEST_0_SHIPTOZIP']         = $zip;
        $this->shippingaddress['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $countrycode;
    }

    /**
     * Set shipping amount
     * @param $shippingamt string
     */
    public function setShippingAmt( $shippingamt )
    {
        $this->shippingamt = $shippingamt;
    }

    /**
     * Set tax amount
     * @param $taxamt string
     */
    public function setTaxAmt( $taxamt )
    {
        $this->taxamt = $taxamt;
    }

    /**
     * Setup Express Checkout Payment (SetExpressCheckout API Request)
     * The request is stored in a session using ExpressCheckout Token as they key
     * this is retrieved later to complete the transaction.
     * @param  $paymentaction string
     * @param  $currency string
     * @param  $amount string
     * @param  $additional array
     * @return array
     */
    public function create( $paymentaction, $currency, $amt, $additional = null )
    {


        // $amt  = sprintf( '%0.2f', $amt );
        $nvp                                   = array();
        $nvp['RETURNURL']                      = $this->returnurl;
        $nvp['CANCELURL']                      = $this->cancelurl;
        $nvp['PAYMENTREQUEST_0_PAYMENTACTION'] = $paymentaction;
        $nvp['PAYMENTREQUEST_0_CURRENCYCODE']  = $currency;
        $nvp['PAYMENTREQUEST_0_AMT']           = $amt;

        if ( isset( $this->shippingaddress ) ) {
            $nvp = array_merge( $nvp, $this->shippingaddress );
        }

        if ( isset( $this->shippingamt ) ) {
            $nvp['PAYMENTREQUEST_0_SHIPPINGAMT'] = $this->shippingamt;
        }

        if ( isset( $this->lineitems )  && count(($this->lineitems))) {
            $this->lineitems["PAYMENTREQUEST_0_ITEMAMT"] = sprintf( '%0.2f', $this->itemtotal );
            $nvp                                         = array_merge( $nvp, $this->lineitems );
        }

        if ( isset( $this->taxamt ) ) {
            $nvp['PAYMENTREQUEST_0_TAXAMT'] = $this->taxamt;
        }

        if ( isset( $additional ) ) {
            $nvp = array_merge( $nvp, $additional );
        }

        $setec = $this->apireq( 'SetExpressCheckout', $nvp );
        // store for reuse
        unset( $nvp['RETURNURL'], $nvp['CANCELURL'] );
        $_SESSION[$setec['TOKEN']] = serialize( $nvp );
        $setec['redirect']         = $this->redirect.$setec['TOKEN'];

        return $setec;
    }

    /**
     * Complete Express Checkout Payment (DoExpressCheckoutPayment API Request)
     * Retrieve the original request from the session append EC Token & buyers PayerID.
     * @param  $token string
     * @param  $payerid string
     * @return array
     */
    public function complete( $token, $payerid )
    {
        $nvp            = unserialize( $_SESSION[$token] );
        $nvp['PAYERID'] = $payerid;
        $nvp['TOKEN']   = $token;

        $doec = $this->apireq( 'DoExpressCheckoutPayment', $nvp );
        return $doec;
    }

    /**
     * Capture Authorization (DoCapture API Request)
     * Partially or fully captures an authorization
     * @param  $authorizationid string
     * @param  $amt string
     * @param  $currencycode string
     * @param  $completetype string
     * @return array
     */
    public function capture( $authorizationid, $amt, $currencycode, $completetype )
    {
        $nvp['AUTHORIZATIONID'] = $authorizationid;
        $nvp['AMT']             = $amt;
        $nvp['CURRENCYCODE']    = $currencycode;
        $nvp['COMPLETETYPE']    = $completetype;

        $docapture = $this->apireq( 'DoCapture', $nvp );
        return $docapture;
    }

    /**
     * Authorize Transaction (DoAuthorization API Request)
     * Authorize a payment
     * @param  $transactionid string
     * @param  $amt string
     * @return array
     */
    public function authorize( $transactionid, $amt )
    {
        $nvp['TRANSACTIONID'] = $transactionid;
        $nvp['AMT']           = $amt;

        $doauth = $this->apireq( 'DoAuthorization', $nvp );
        return $doauth;
    }

    /**
     * ReAuthorize Transaction (DoReauthorization API Request)
     * The DoReauthorization API operation reauthorizes an existing authorization transaction.
     * @param  $authorizationid string
     * @param  $amt string
     * @param  $currencycode string
     * @return array
     */
    public function reauth( $authorizationid, $amt, $currencycode )
    {
        $nvp['AUTHORIZATIONID'] = $authorizationid;
        $nvp['AMT']             = $amt;
        $nvp['CURRENCYCODE']    = $currencycode;

        $reauth = $this->apireq( 'DoReauthorization', $nvp );
        return $reauth;
    }

    /**
     * Void Authorization (DoVoid API Request)
     * Void an order or an authorization.
     * @param  $authorizationid string
     * @return array
     */
    public function void( $authorizationid )
    {
        $nvp['AUTHORIZATIONID'] = $authorizationid;

        $dovoid = $this->apireq( 'DoVoid', $nvp );
        return $dovoid;
    }

    /**
     * Refund Transaction (RefundTransaction API Request)
     * Partially refunds or fully refunds the transaction
     * @param  $transactionid string
     * @param  $refundtype string
     * @param  $amt string
     * @return array
     */
    public function refund( $transactionid, $refundtype = 'Full', $currencycode = null, $amt = null )
    {
        $nvp['TRANSACTIONID'] = $transactionid;
        $nvp['REFUNDTYPE']    = $refundtype;

        if ( $refundtype == 'Partial' ) {
            $nvp['AMT']          = $amt;
            $nvp['CURRENCYCODE'] = $currencycode;
        }

        $refundtxn = $this->apireq( 'RefundTransaction', $nvp );
        return $refundtxn;
    }

    /**
     * // Transation & buyer details (GetExpressCheckoutDetails API Request)
     * @param  $token string
     * @return array
     */
    public function getDetails( $token )
    {
        $nvp['TOKEN'] = $token;
        $getec        = $this->apireq( 'GetExpressCheckoutDetails', $nvp );
        return $getec;
    }

    /**
     * Update shipping address if buyer changes address on review page etc
     * @param $token string
     * @param $name string
     * @param $street1 string
     * @param $street2 string
     * @param $city string
     * @param $state string
     * @param $zip string
     * @param $countrycode string
     */
    public function updateShippingAddress( $token, $name, $street1, $street2, $city, $state, $zip, $countrycode )
    {
        $orderdetails                                       = unserialize( $_SESSION[$token] );
        $orderdetails['PAYMENTREQUEST_0_SHIPTONAME']        = $name;
        $orderdetails['PAYMENTREQUEST_0_SHIPTOSTREET']      = $street1;
        $orderdetails['PAYMENTREQUEST_0_SHIPTOSTREET2']     = $street2;
        $orderdetails['PAYMENTREQUEST_0_SHIPTOCITY']        = $city;
        $orderdetails['PAYMENTREQUEST_0_SHIPTOSTATE']       = $state;
        $orderdetails['PAYMENTREQUEST_0_SHIPTOZIP']         = $zip;
        $orderdetails['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $countrycode;
        $_SESSION[$token]                                   = serialize( $orderdetails );
    }

    /**
     * Used for payment mark method where buyers address is known and shipping
     * has been calculated. Resets shipping amount (if previously set)
     * and updates order total and adds new shipping amount.
     * @param  $token string
     * @param  $amt string
     */
    public function updateShippingAmt( $token, $amt )
    {
        $orderdetails = unserialize( $_SESSION[$token] );
        if ( array_key_exists( 'PAYMENTREQUEST_0_SHIPPINGAMT', $orderdetails ) ) {
            $orderdetails['PAYMENTREQUEST_0_AMT'] = sprintf( '%0.2f', $orderdetails['PAYMENTREQUEST_0_AMT'] - $orderdetails['PAYMENTREQUEST_0_SHIPPINGAMT'] );
        }
        $orderdetails['PAYMENTREQUEST_0_SHIPPINGAMT'] = $amt;
        $orderdetails['PAYMENTREQUEST_0_AMT']         = sprintf( '%0.2f', $orderdetails['PAYMENTREQUEST_0_AMT'] + $amt );
        $_SESSION[$token]                             = serialize( $orderdetails );
    }

    /**
     * Used for payment mark method where buyers address is known and tax
     * has been calculated. Resets tax amount (if previously set)
     * and updates order total and adds new shipping amount.
     * @param  $token string
     * @param  $amt string
     */
    public function updateTaxAmt( $token, $amt )
    {
        $orderdetails = unserialize( $_SESSION[$token] );
        if ( array_key_exists( 'PAYMENTREQUEST_0_TAXAMT', $orderdetails ) ) {
            $orderdetails['PAYMENTREQUEST_0_AMT'] = sprintf( '%0.2f', $orderdetails['PAYMENTREQUEST_0_AMT'] - $orderdetails['PAYMENTREQUEST_0_TAXAMT'] );
        }
        $orderdetails['PAYMENTREQUEST_0_TAXAMT'] = $amt;
        $orderdetails['PAYMENTREQUEST_0_AMT']    = sprintf( '%0.2f', $orderdetails['PAYMENTREQUEST_0_AMT'] + $amt );
        $_SESSION[$token]                        = serialize( $orderdetails );
    }

    /**
     * Copy basket() to PayPal Checkout
     * Transfer your basket details to the PayPal Checkout
     * Returns a total value of items
     * @param  $basket object
     * @param  $name string
     * @param  $amount string
     */
    public function copyBasket( $basket, $name = 'name', $quantity = 'qty', $amount = 'amount' )
    {
        $totalamount = 0;
        foreach ( $basket as $lineitem ) {

            if ( empty( $lineitem->{$quantity} ) ) {
                $lineitem->{$quantity} = 1;
            }

            $this->setLineItem( $lineitem->{$name}, $lineitem->{$quantity}, $lineitem->{$amount} );
            $totalamount += $lineitem->{$amount} * $lineitem->{$quantity};
        }

        return $totalamount;
    }

    /**
     * Direct Credit Card (DoDirectPayment API Request)
     * Processes a credit card payment (PayPal Pro Required).
     * @param  $paymentaction string
     * @param  $currency string
     * @param  $amt string
     * @param  $cardtype string
     * @param  $cardnumber int
     * @param  $expdate string
     * @param  $cvv int
     * @param  $ipaddress string
     * @return array
     */
    public function dcc( $paymentaction, $currency = null, $amt, $cardtype, $cardnumber, $expdate, $cvv, $ipaddress, $additional = null )
    {
        $nvp = array();

        $nvp['PAYMENTACTION'] = $paymentaction;
        if ( isset( $currency ) ) {
            $nvp['CURRENCYCODE'] = $currency;
        }
        $nvp['AMT']            = $amt;
        $nvp['CREDITCARDTYPE'] = $cardtype;
        $nvp['ACCT']           = $cardnumber;
        $nvp['EXPDATE']        = $expdate;
        $nvp['CVV']            = $cvv;
        $nvp['IPADDRESS']      = $ipaddress;

        $dcc = $this->apireq( 'DoDirectPayment', $nvp );
        return $dcc;
    }

    /**
     * Logs NVP Request
     * @param  $request string
     */
    public function logreq( $request )
    {
        $this->logger->write( $request );
    }
}
