<?php namespace Bedard\Shop\Components;

use Bedard\Shop\Classes\Paypal;
use Bedard\Shop\Models\PaySettings;
use Bedard\Shop\Models\Transaction;
use Cms\Classes\ComponentBase;
use Exception;
use Redirect;
use Session;

class PaypalCheckout extends ComponentBase
{
    use \Bedard\Shop\Traits\CartTrait;
    use \Bedard\Shop\Traits\AjaxResponderTrait;

    /**
     * PayPal Credentials
     * @var string  $paypal_clientId
     * @var string  $paypal_secret
     */
    private $paypal_clientId;
    private $paypal_secret;

    /**
     * Component Details
     * @return  array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Paypal Checkout',
            'description' => 'Checkout functionality for PayPal'
        ];
    }

    /**
     * Component Properties
     * @return  array
     */
    public function defineProperties()
    {
        return [
            'api_mode' => [
                'title'             => 'API Mode',
                'description'       => 'PayPal API mode',
                'type'              => 'dropdown',
                'options' => [
                    'live'      => 'Live',
                    'sandbox'   => 'Sandbox'
                ],
                'default'               => 'live',
                'showExternalParam' => FALSE
            ],
            'callback_success' => [
                'title'             => 'Success URL',
                'description'       => 'Callback URL for successful checkouts.',
                'validationPattern' => '^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$',
                'validationMessage' => 'Invalid callback URL.',
                'showExternalParam' => FALSE
            ],
            'callback_canceled' => [
                'title'             => 'Canceled URL',
                'description'       => 'Callback URL for canceled checkouts.',
                'validationPattern' => '^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$',
                'validationMessage' => 'Invalid callback URL.',
                'showExternalParam' => FALSE
            ],
            'callback_failed' => [
                'title'             => 'Failed URL',
                'description'       => 'Callback URL for failed PayPal requests.',
                'validationPattern' => '^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$',
                'validationMessage' => 'Invalid callback URL.',
                'showExternalParam' => FALSE
            ]
        ];
    }

    /**
     * Require paypal credentials and a non-empty shopping cart
     */
    public function onInit()
    {
        $this->paypal_clientId = PaySettings::get('paypal_client_id');
        $this->paypal_secret = PaySettings::get('paypal_secret');
        if (empty($this->paypal_clientId) || empty($this->paypal_secret))
            throw new Exception('A client ID and secret is required to use the PayPal checkout component.');
    }

    /**
     * Begin the checkout process, and redirect the user to PayPal
     * @return  Redirect
     */
    public function onPaypalCheckout()
    {
        // Load the cart with it's relationships
        $this->loadCart(TRUE);
        if (!$this->cart)
            return $this->response('Cart not found', FALSE);

        // Validate the cart item quantities
        if ($this->cart->fixQuantities())
            return $this->response('Fixed quantities', FALSE);

        // Set up the PayPal API and cart
        $paypal = new Paypal;
        $paypal->createNewCart($this->cart);

        // Set the callbacks
        $paypal->setCallbackUrls([
            'success'   => $this->property('callback_success'),
            'canceled'  => $this->property('callback_canceled'),
            'failed'    => $this->property('callback_failed'),
        ]);

        // Load the checkout ID and url
        $checkout = $paypal->checkout();

        // If everything worked out, store a hash in the database
        if ($checkout['id']) {
            $hash = md5($checkout['id']);
            Session::put('bedard_shop_paypal_hash', $hash);
            $receipt = Transaction::create([
                'service'   => 'paypal',
                'payment_id'=> $checkout['id'],
                'hash'      => $hash
            ]);
        }

        return Redirect::to($checkout['url']);
    }

}