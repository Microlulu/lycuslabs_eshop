<?php


namespace App\Services\Stripe\StripeApi;

use App\Services\Stripe\StripeApi\BrainApi\StripeApiInterface;
use Stripe\StripeClient;

abstract class AbstractStripeApi implements StripeApiInterface
{
    private mixed $stripePublicKey;
    private mixed $stripeSecretKey;
    protected StripeClient $stripeClient;

    /**
     * get the stripe public key and secret key on the ENV and set the stripe client
     */
    public function __construct()
    {
        $this->stripePublicKey = $_ENV["PUBLIC_KEY_STRIPE"];
        $this->stripeSecretKey = $_ENV["SECRET_KEY_STRIPE"];
        $this->stripeClient = new StripeClient($this->stripeSecretKey);
    }


    /*
     * Section event
     */
    public function retrieveEvent($eventId)
    {
        return $this->stripeClient->events->retrieve(
            $eventId,
            []
        );
    }
}
