<?php

namespace App\Services\Stripe\StripeApi;

class OverRidingApi extends AbstractStripeApi
{
    /**
     * @param $email
     * @return \Stripe\Customer
     * @throws \Stripe\Exception\ApiErrorException
     *
     * create a user stripe
     */
    public function createUserStripe($email){
        return $this->stripeClient->customers->create([
            'email' => $email
        ]);
    }

    /**
     * @param $line_items
     * @return \Stripe\Checkout\Session
     * @throws \Stripe\Exception\ApiErrorException create a payment intent
     */
    public function paymentIntent($customerEmail, $line_items){
        return $this->stripeClient->checkout->sessions->create([
            'customer_email' => $customerEmail,
            'payment_method_types' => ['card'],
            'line_items' => [
                $line_items
            ],
            'mode' => 'payment',
            'success_url' => 'http://localhost:81/buyAction/order_confirmation/{CHECKOUT_SESSION_ID}',
            'cancel_url' => 'http://localhost:81/buyAction/order_failed/{CHECKOUT_SESSION_ID}'
        ]);
    }
}
