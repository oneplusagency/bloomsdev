<?php

//! PayPal Reference Transactions via Classic API
class PayPalRT extends PayPal
{
    /**
     *    Class constructor
     *    Calls parent class constructor where PP config is set at instantiation.
     */
    public function __construct( $options = null )
    {
        parent::__construct( $options );
    }

    /**
     * Setup Reference Transaction Billing Agreement.
     * @param  $description string
     * @param  $additional array
     * @return array
     */
    public function setupRT( $description, $additional = null )
    {
        $nvp                                   = array();
        $nvp['RETURNURL']                      = $this->returnurl;
        $nvp['CANCELURL']                      = $this->cancelurl;
        $nvp['L_BILLINGTYPE0']                 = 'MerchantInitiatedBillingSingleAgreement';
        $nvp['L_BILLINGAGREEMENTDESCRIPTION0'] = $description;

        if ( isset( $additional ) ) {
            $nvp = array_merge( $nvp, $additional );
        }

        $setec = $this->apireq( 'SetExpressCheckout', $nvp );
        // store for reuse
        unset( $nvp['RETURNURL'], $nvp['CANCELURL'], $nvp['L_BILLINGTYPE0'], $nvp['L_BILLINGAGREEMENTDESCRIPTION0'] );
        $nvp['DESC'] = $description;

        $_SESSION[$setec['TOKEN']] = serialize( $nvp );

        $setec['redirect'] = $this->redirect.$setec['TOKEN'];
        return $setec;
    }

    /**
     * Create Billing Agreement.
     * @param  $token string
     * @param  $additional array
     * @return array
     */
    public function createBA( $token )
    {
        $nvp          = unserialize( $_SESSION[$token] );
        $nvp['TOKEN'] = $token;

        if ( isset( $this->profiledetails ) ) {
            $nvp = array_merge( $nvp, $this->profiledetails );
        }

        $createrpp = $this->apireq( 'CreateBillingAgreement', $nvp );
        return $createrpp;
    }

    /**
     * Create a Reference Transaction.
     * @param  $referenceid string
     * @param  $paymentaction string
     * @param  $currency string
     * @param  $amt string
     * @param  $additional array
     * @return array
     */
    public function doRT( $referenceid, $paymentaction, $currency, $amt, $additional = null )
    {
        $nvp                  = array();
        $nvp['REFERENCEID']   = $referenceid;
        $nvp['PAYMENTACTION'] = $paymentaction;
        $nvp['CURRENCYCODE']  = $currency;
        $nvp['AMT']           = $amt;

        if ( isset( $additional ) ) {
            $nvp = array_merge( $nvp, $additional );
        }

        $dort = $this->apireq( 'DoReferenceTransaction', $nvp );
        return $dort;
    }

}
