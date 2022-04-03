<?php

namespace App\Services\Stripe\StripeApi\BrainApi;


interface StripeApiInterface
{
    function retrieveEvent($eventId);
}
