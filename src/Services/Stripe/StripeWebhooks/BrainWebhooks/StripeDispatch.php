<?php

namespace App\Services\Stripe\StripeWebhooks\BrainWebhooks;

use App\Services\Stripe\StripeWebhooks\OverRidingWebhooks;

class StripeDispatch
{
    private $serverLogic;
    private $stripeApi;

    public function setStripeService($stripeApi){
        $this->stripeApi = $stripeApi;
    }

    public function setServerLogic($server){
        $this->serverLogic = $server;
    }

    public function dispatchWebhooks($webhooksData){
        $eventId = $webhooksData['id'];
        $event = $this->stripeApi->retrieveEvent($eventId);
        switch ($event->type){
            case 'account.updated':
                //Occurs whenever an account status or property has changed.
                break;
            case 'account.application.authorized':
                //Occurs whenever a user authorizes an application. Sent to the related application only.
                break;
            case 'account.application.deauthorized':
                //Occurs whenever a user deauthorizes an application. Sent to the related application only.
                break;
            case 'account.external_account.created':
                //Occurs whenever an external account is created.
                break;
            case 'account.external_account.deleted':
                //Occurs whenever an external account is deleted.
                break;
            case ' account.external_account.updated':
                //Occurs whenever an external account is updated.
                break;
            case 'application_fee.created':
                //Occurs whenever an application fee is created on a charge.
                break;
            case 'application_fee.refunded':
                //Occurs whenever an application fee is refunded,
                //whether from refunding a charge or from refunding the application fee directly.
                //This includes partial refunds.
                break;
            case 'application_fee.refund.updated':
                //Occurs whenever an application fee refund is updated.
                break;
            case 'balance.available':
                //Occurs whenever your Stripe balance has been updated
                // (e.g., when a charge is available to be paid out).
                // By default, Stripe automatically transfers funds in your balance to your bank account on a daily basis.
                break;
            case 'capability.updated':
                //Occurs whenever a capability has new requirements or a new status.
                break;
            case 'charge.captured':
                //Occurs whenever a previously uncaptured charge is captured.
                break;
            case 'charge.expired':
                //Occurs whenever an uncaptured charge expires.
                break;
            case 'charge.failed':
                //Occurs whenever a failed charge attempt occurs.
                break;
            case 'charge.pending':
                //Occurs whenever a charge is refunded, including partial refunds.
                break;
            case 'charge.succeeded':
                //Occurs whenever a new charge is created and is successful.
                $this->serverLogic->charge_succeeded($event);
                break;
            case 'charge.updated':
                //Occurs whenever a charge description or metadata is updated.
                break;
            case 'charge.dispute.closed':
                //Occurs when a dispute is closed and the dispute status changes to lost, warning_closed, or won.
                break;
            case 'charge.dispute.created':
                //Occurs whenever a customer disputes a charge with their bank.
                break;
            case 'charge.dispute.funds_reinstated':
                //Occurs when funds are reinstated to your account after a dispute is closed. This includes partially refunded payments.
                break;
            case 'charge.dispute.funds_withdrawn':
                //Occurs when funds are removed from your account due to a dispute.
                break;
            case 'charge.dispute.updated':
                //Occurs when the dispute is updated (usually with evidence).
                break;
            case 'charge.refund.updated':
                //Occurs whenever a refund is updated, on selected payment methods.
                break;
            case 'checkout.session.async_payment_failed':
                //Occurs when a payment intent using a delayed payment method fails.
                break;
            case 'checkout.session.async_payment_succeeded':
                //Occurs when a payment intent using a delayed payment method finally succeeds.
                break;
            case 'checkout.session.completed':
                //Occurs when a Checkout Session has been successfully completed.
                break;
            case 'coupon.created':
                //Occurs whenever a coupon is created.
                break;
            case 'coupon.deleted':
                //Occurs whenever a coupon is deleted.
                break;
            case 'coupon.updated':
                //Occurs whenever a coupon is updated.
                break;
            case 'credit_note.created':
                //Occurs whenever a credit note is created.
                break;
            case 'credit_note.updated':
                //Occurs whenever a credit note is updated.
                break;
            case 'credit_note.voided':
                //Occurs whenever a credit note is voided.
                break;
            case 'customer.created':
                //Occurs whenever a new customer is created.
                break;
            case 'customer.deleted':
                //Occurs whenever a customer is deleted.
                break;
            case 'customer.updated':
                //Occurs whenever any property of a customer changes.
                break;
            case 'customer.discount.created':
                //Occurs whenever a coupon is attached to a customer.
                break;
            case 'customer.discount.deleted':
                //Occurs whenever a coupon is removed from a customer.
                break;
            case 'customer.discount.updated':
                //Occurs whenever a customer is switched from one coupon to another.
                break;
            case 'customer.source.created':
                //Occurs whenever a new source is created for a customer.
                break;
            case 'customer.source.deleted':
                //Occurs whenever a source is removed from a customer.
                break;
            case 'customer.source.expiring':
                //Occurs whenever a card or source will expire at the end of the month.
                break;
            case 'customer.source.updated':
                //Occurs whenever a source's details are changed.
                break;
            case 'customer.subscription.created':
                //Occurs whenever a customer is signed up for a new plan.
                break;
            case 'customer.subscription.deleted':
                //Occurs whenever a customer's subscription ends.
                break;
            case 'customer.subscription.pending_update_applied':
                //Occurs whenever a customer's subscription's pending update is applied, and the subscription is updated.
                break;
            case 'customer.subscription.pending_update_expired':
                //Occurs whenever a customer's subscription's pending update expires before the related invoice is paid.
                break;
            case 'customer.subscription.trial_will_end':
                //Occurs three days before a subscription's trial period is scheduled to end, or when a trial is ended immediately (using trial_end=now).
                break;
            case 'customer.subscription.updated':
                //Occurs whenever a subscription changes (e.g., switching from one plan to another, or changing the status from trial to active).
                break;
            case 'customer.tax_id.created':
                //Occurs whenever a tax ID is created for a customer.
                break;
            case 'customer.tax_id.deleted':
                //Occurs whenever a tax ID is deleted from a customer.
                break;
            case'customer.tax_id.updated':
                //Occurs whenever a customer's tax ID is updated.
                break;
            case'file.created':
                //Occurs whenever a new Stripe-generated file is available for your account.
                break;
            case'invoice.created':
                //Occurs whenever a new invoice is created. To learn how webhooks can be used with this event,
                // and how they can affect it, see Using Webhooks with Subscriptions.
                break;
            case'invoice.deleted':
                //Occurs whenever a draft invoice is deleted.
                break;
            case'invoice.finalization_failed':
                //Occurs whenever a draft invoice cannot be finalized. See the invoice’s last finalization error for details.
                break;
            case'invoice.finalized':
                //Occurs whenever a draft invoice is finalized and updated to be an open invoice.
                break;
            case'invoice.marked_uncollectible':
                //Occurs whenever an invoice is marked uncollectible.
                break;
            case'invoice.paid':
                //Occurs whenever an invoice payment attempt succeeds or an invoice is marked as paid out-of-band.
                break;
            case'invoice.payment_action_required':
                //Occurs whenever an invoice payment attempt requires further user action to complete.
                break;
            case'invoice.payment_failed':
                //Occurs whenever an invoice payment attempt fails, due either to a declined payment or to the lack of a stored payment method.
                break;
            case'invoice.payment_succeeded':
                //Occurs whenever an invoice payment attempt succeeds.
                break;
            case'invoice.sent':
                //Occurs whenever an invoice email is sent out.
                break;
            case'invoice.upcoming':
                //Occurs X number of days before a subscription is scheduled to create an invoice that is automatically
                // charged—where X is determined by your subscriptions settings. Note: The received Invoice object will not have an invoice ID.
                break;
            case'invoice.updated':
                //Occurs whenever an invoice changes (e.g., the invoice amount).
                break;
            case'invoice.voided':
                //Occurs whenever an invoice is voided.
                break;
            case'invoiceitem.created':
                //Occurs whenever an invoice item is created.
                break;
            case'invoiceitem.deleted':
                //Occurs whenever an invoice item is deleted.
                break;
            case'invoiceitem.updated':
                //Occurs whenever an invoice item is updated.
                break;
            case'issuing_authorization.created':
                //Occurs whenever an authorization is created.
                break;
            case'issuing_authorization.request':
                //Represents a synchronous request for authorization, see Using your integration to handle authorization requests.
                //You must create a webhook endpoint which explicitly subscribes to this event type to access it.
                //Webhook endpoints which subscribe to all events will not include this event type.
                break;
            case'issuing_authorization.updated':
                //Occurs whenever an authorization is updated.
                break;
            case'issuing_card.created':
                //Occurs whenever a card is created.
                break;
            case'issuing_card.updated':
                //Occurs whenever a card is updated.
                break;
            case'issuing_cardholder.created':
                //Occurs whenever a cardholder is created.
                break;
            case'issuing_cardholder.updated':
                //Occurs whenever a cardholder is updated.
                break;
            case'issuing_dispute.closed':
                //Occurs whenever a dispute is won, lost or expired.
                break;
            case'issuing_dispute.created':
                //Occurs whenever a dispute is created.
                break;
            case'issuing_dispute.funds_reinstated':
                //Occurs whenever funds are reinstated to your account for an Issuing dispute.
                break;
            case'issuing_dispute.submitted':
                //Occurs whenever a dispute is submitted.
                break;
            case'issuing_dispute.updated':
                //Occurs whenever a dispute is updated.
                break;
            case'issuing_transaction.created':
                //Occurs whenever an issuing transaction is created.
                break;
            case'issuing_transaction.updated':
                //Occurs whenever an issuing transaction is updated.
                break;
            case'mandate.updated':
                //Occurs whenever a Mandate is updated.
                break;
            case'order.created':
                //Occurs whenever an order is created.
                break;
            case'order.payment_failed':
                //Occurs whenever an order payment attempt fails.
                break;
            case'order.payment_succeeded':
                //Occurs whenever an order payment attempt succeeds.
                break;
            case'order.updated':
                //Occurs whenever an order is updated.
                break;
            case'order_return.created':
                //Occurs whenever an order return is created.
                break;
            case'payment_intent.amount_capturable_updated':
                //Occurs when a PaymentIntent has funds to be captured.
                //Check the amount_capturable property on the PaymentIntent to determine the amount that can be captured.
                //You may capture the PaymentIntent with an amount_to_capture value up to the specified amount.
                //Learn more about capturing PaymentIntents.
                break;
            case'payment_intent.canceled':
                //Occurs when a PaymentIntent is canceled.
                break;
            case'payment_intent.created':
                //Occurs when a new PaymentIntent is created.
                break;
            case'payment_intent.payment_failed':
                //Occurs when a PaymentIntent has failed the attempt to create a payment method or a payment.
                break;
            case'payment_intent.processing':
                //Occurs when a PaymentIntent has started processing.
                break;
            case'payment_intent.requires_action':
                //Occurs when a PaymentIntent transitions to requires_action state
                break;
            case'payment_intent.succeeded':
                $this->serverLogic->payment_intent_succeeded($event);
                break;
            case'payment_method.attached':
                //Occurs whenever a new payment method is attached to a customer.
                break;
            case'payment_method.automatically_updated':
                //Occurs whenever a payment method's details are automatically updated by the network.
                break;
            case'payment_method.detached':
                //Occurs whenever a payment method is detached from a customer.
                break;
            case'payment_method.updated':
                //Occurs whenever a payment method is updated via the PaymentMethod update API.
                break;
            case'payout.canceled':
                //Occurs whenever a payout is canceled.
                break;
            case'payout.created':
                //Occurs whenever a payout is created.
                break;
            case'payout.failed':
                //Occurs whenever a payout attempt fails.
                break;
            case'payout.paid':
                //Occurs whenever a payout is expected to be available in the destination account.
                //If the payout fails, a payout.failed notification is also sent, at a later time.
                break;
            case'payout.updated':
                //Occurs whenever a payout is updated.
                break;
            case'person.created':
                //Occurs whenever a person associated with an account is created.
                break;
            case'person.deleted':
                //Occurs whenever a person associated with an account is deleted.
                break;
            case'person.updated':
                //Occurs whenever a person associated with an account is updated.
                break;
            case'plan.created':
                //Occurs whenever a plan is created.
                break;
            case'plan.deleted':
                //Occurs whenever a plan is deleted.
                break;
            case'plan.updated':
                //Occurs whenever a plan is updated.
                break;
            case'price.created':
                //Occurs whenever a price is created.
                break;
            case'price.deleted':
                //Occurs whenever a price is deleted.
                break;
            case'price.updated':
                //Occurs whenever a price is updated.
                break;
            case'product.created':
                //Occurs whenever a product is created.
                break;
            case'product.deleted':
                //Occurs whenever a product is deleted.
                break;
            case'product.updated':
                //Occurs whenever a product is updated.
                break;
            case'promotion_code.created':
                //Occurs whenever a promotion code is created.
                break;
            case'promotion_code.updated':
                //Occurs whenever a promotion code is updated.
                break;
            case'radar.early_fraud_warning.created':
                //Occurs whenever an early fraud warning is created.
                break;
            case'radar.early_fraud_warning.updated':
                //Occurs whenever an early fraud warning is updated.
                break;
            case'recipient.created':
                //Occurs whenever a recipient is created.
                break;
            case 'recipient.deleted':
                //Occurs whenever a recipient is deleted.
                break;
            case 'recipient.updated':
                //Occurs whenever a recipient is updated.
                break;
            case 'reporting.report_run.failed':
                //Occurs whenever a requested ReportRun failed to complete.
                break;
            case 'reporting.report_run.succeeded':
                //Occurs whenever a requested ReportRun completed succesfully.
                break;
            case 'reporting.report_type.updated':
                //Occurs whenever a ReportType is updated (typically to indicate that a new day's data has come available).
                //You must create a webhook endpoint which explicitly subscribes to this event type to access it.
                // Webhook endpoints which subscribe to all events will not include this event type.
                break;
            case 'review.closed':
                //Occurs whenever a review is closed. The review's reason field indicates why: approved, disputed, refunded,
                //or refunded_as_fraud.
                break;
            case 'review.opened':
                //Occurs whenever a review is opened.
                break;
            case 'setup_intent.canceled':
                //Occurs when a SetupIntent is canceled.
                break;
            case 'setup_intent.created':
                //Occurs when a new SetupIntent is created.
                break;
            case 'setup_intent.requires_action':
                //Occurs when a SetupIntent is in requires_action state.
                break;
            case 'setup_intent.setup_failed':
                //Occurs when a SetupIntent has failed the attempt to setup a payment method.
                break;
            case 'setup_intent.succeeded':
                //Occurs when an SetupIntent has successfully setup a payment method.
                break;
            case 'sigma.scheduled_query_run.created':
                //Occurs whenever a Sigma scheduled query run finishes.
                break;
            case 'sku.created':
                //Occurs whenever a SKU is created.
                break;
            case 'sku.deleted':
                //Occurs whenever a SKU is deleted.
                break;
            case 'sku.updated':
                //Occurs whenever a SKU is updated.
                break;
            case 'source.canceled':
                //Occurs whenever a source is canceled.
                break;
            case 'source.chargeable':
                //Occurs whenever a source transitions to chargeable.
                break;
            case 'source.failed':
                //Occurs whenever a source fails.
                break;
            case 'source.mandate_notification':
                //Occurs whenever a source mandate notification method is set to manual.
                break;
            case 'source.refund_attributes_required':
                //Occurs whenever the refund attributes are required on a receiver source to process a refund or a mispayment.
                break;
            case 'source.transaction.created':
                //Occurs whenever a source transaction is created.
                break;
            case 'source.transaction.updated':
                //Occurs whenever a source transaction is updated.
                break;
            case 'subscription_schedule.aborted':
                //Occurs whenever a subscription schedule is canceled due to the underlying subscription being canceled because of delinquency.
                break;
            case 'subscription_schedule.canceled':
                //Occurs whenever a subscription schedule is canceled.
                break;
            case 'subscription_schedule.completed':
                //Occurs whenever a new subscription schedule is completed.
                break;
            case 'subscription_schedule.created':
                //Occurs whenever a new subscription schedule is created.
                break;
            case 'subscription_schedule.expiring':
                //Occurs 7 days before a subscription schedule will expire.
                break;
            case 'subscription_schedule.released':
                //Occurs whenever a new subscription schedule is released.
                break;
            case 'subscription_schedule.updated':
                //Occurs whenever a subscription schedule is updated.
                break;
            case 'tax_rate.created':
                //Occurs whenever a new tax rate is created.
                break;
            case 'tax_rate.updated':
                //Occurs whenever a tax rate is updated.
                break;
            case 'topup.canceled':
                //Occurs whenever a top-up is canceled.
                break;
            case 'topup.created':
                //Occurs whenever a top-up is created.
                break;
            case 'topup.failed':
                //Occurs whenever a top-up fails.
                break;
            case 'topup.reversed':
                //Occurs whenever a top-up is reversed.
                break;
            case 'topup.succeeded':
                //Occurs whenever a top-up succeeds.
                break;
            case 'transfer.created':
                //Occurs whenever a transfer is created.
                break;
            case 'transfer.failed':
                //Occurs whenever a transfer failed.
                break;
            case 'transfer.paid':
                //Occurs after a transfer is paid. For Instant Payouts, the event will typically be sent within 30 minutes.
                break;
            case 'transfer.reversed':
                //Occurs whenever a transfer is reversed, including partial reversals.
                break;
            case 'transfer.updated':
                //Occurs whenever a transfer's description or metadata is updated.
                break;
            default:
                throw new \Exception('Unexpected webhook type form Stripe! '.$event->type);
        }
    }
}
