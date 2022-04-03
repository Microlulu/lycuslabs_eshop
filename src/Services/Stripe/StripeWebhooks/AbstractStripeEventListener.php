<?php

namespace App\Services\Stripe\StripeWebhooks;

use App\Services\Stripe\StripeApi\OverRidingApi;
use App\Services\stripe\StripeWebhooks\BrainWebhooks\StripeDispatch;
use App\Services\stripe\StripeWebhooks\BrainWebhooks\StripeEventListenerInterface;

abstract class AbstractStripeEventListener implements StripeEventListenerInterface
{

    /**
     * @param $data
     * @throws \Exception
     * à rendre dynamique
     */
    public function dispatch($data){
        $dispatch = new StripeDispatch();
        $stripeApi = new OverRidingApi();

        $dispatch->setServerLogic($this);
        $dispatch->setStripeService($stripeApi);

        $dispatch->dispatchWebhooks($data);
    }

    public function account_updated($event)
    {
        // TODO: Implement account_updated() method.
    }

    public function account_application_authorized($event)
    {
        // TODO: Implement account_application_authorized() method.
    }

    public function account_application_deauthorized($event)
    {
        // TODO: Implement account_application_deauthorized() method.
    }

    public function account_external_account_created($event)
    {
        // TODO: Implement account_external_account_created() method.
    }

    public function account_external_account_deleted($event)
    {
        // TODO: Implement account_external_account_deleted() method.
    }

    public function account_external_account_updated($event)
    {
        // TODO: Implement account_external_account_updated() method.
    }

    public function application_fee_created($event)
    {
        // TODO: Implement application_fee_created() method.
    }

    public function application_fee_refunded($event)
    {
        // TODO: Implement application_fee_refunded() method.
    }

    public function application_fee_refund_updated($event)
    {
        // TODO: Implement application_fee_refund_updated() method.
    }

    public function balance_available($event)
    {
        // TODO: Implement balance_available() method.
    }

    public function capability_updated($event)
    {
        // TODO: Implement capability_updated() method.
    }

    public function charge_captured($event)
    {
        // TODO: Implement charge_captured() method.
    }

    public function charge_expired($event)
    {
        // TODO: Implement charge_expired() method.
    }

    public function charge_failed($event)
    {
        // TODO: Implement charge_failed() method.
    }

    public function charge_pending($event)
    {
        // TODO: Implement charge_pending() method.
    }

    public function charge_succeeded($event)
    {
        // TODO: Implement charge_succeeded() method.
    }

    public function charge_updated($event)
    {
        // TODO: Implement charge_updated() method.
    }

    public function charge_dispute_closed($event)
    {
        // TODO: Implement charge_dispute_closed() method.
    }

    public function charge_dispute_created($event)
    {
        // TODO: Implement charge_dispute_created() method.
    }

    public function charge_dispute_funds_reinstated($event)
    {
        // TODO: Implement charge_dispute_funds_reinstated() method.
    }

    public function charge_dispute_funds_withdrawn($event)
    {
        // TODO: Implement charge_dispute_funds_withdrawn() method.
    }

    public function charge_dispute_updated($event)
    {
        // TODO: Implement charge_dispute_updated() method.
    }

    public function charge_refund_updated($event)
    {
        // TODO: Implement charge_refund_updated() method.
    }

    public function checkout_session_async_payment_failed($event)
    {
        // TODO: Implement checkout_session_async_payment_failed() method.
    }

    public function checkout_session_async_payment_succeeded($event)
    {
        // TODO: Implement checkout_session_async_payment_succeeded() method.
    }

    public function checkout_session_completed($event)
    {
        // TODO: Implement checkout_session_completed() method.
    }

    public function coupon_created($event)
    {
        // TODO: Implement coupon_created() method.
    }

    public function coupon_deleted($event)
    {
        // TODO: Implement coupon_deleted() method.
    }

    public function coupon_updated($event)
    {
        // TODO: Implement coupon_updated() method.
    }

    public function credit_note_created($event)
    {
        // TODO: Implement credit_note_created() method.
    }

    public function credit_note_updated($event)
    {
        // TODO: Implement credit_note_updated() method.
    }

    public function credit_note_voided($event)
    {
        // TODO: Implement credit_note_voided() method.
    }

    public function customer_created($event)
    {
        // TODO: Implement customer_created() method.
    }

    public function customer_deleted($event)
    {
        // TODO: Implement customer_deleted() method.
    }

    public function customer_updated($event)
    {
        // TODO: Implement customer_updated() method.
    }

    public function customer_discount_created($event)
    {
        // TODO: Implement customer_discount_created() method.
    }

    public function customer_discount_deleted($event)
    {
        // TODO: Implement customer_discount_deleted() method.
    }

    public function customer_discount_updated($event)
    {
        // TODO: Implement customer_discount_updated() method.
    }

    public function customer_source_created($event)
    {
        // TODO: Implement customer_source_created() method.
    }

    public function customer_source_deleted($event)
    {
        // TODO: Implement customer_source_deleted() method.
    }

    public function customer_source_expiring($event)
    {
        // TODO: Implement customer_source_expiring() method.
    }

    public function customer_source_updated($event)
    {
        // TODO: Implement customer_source_updated() method.
    }

    public function customer_subscription_created($event)
    {
        // TODO: Implement customer_subscription_created() method.
    }

    public function customer_subscription_deleted($event)
    {
        // TODO: Implement customer_subscription_deleted() method.
    }

    public function customer_subscription_pending_update_applied($event)
    {
        // TODO: Implement customer_subscription_pending_update_applied() method.
    }

    public function customer_subscription_pending_update_expired($event)
    {
        // TODO: Implement customer_subscription_pending_update_expired() method.
    }

    public function customer_subscription_trial_will_end($event)
    {
        // TODO: Implement customer_subscription_trial_will_end() method.
    }

    public function customer_subscription_updated($event)
    {
        // TODO: Implement customer_subscription_updated() method.
    }

    public function customer_tax_id_created($event)
    {
        // TODO: Implement customer_tax_id_created() method.
    }

    public function customer_tax_id_deleted($event)
    {
        // TODO: Implement customer_tax_id_deleted() method.
    }

    public function customer_tax_id_updated($event)
    {
        // TODO: Implement customer_tax_id_updated() method.
    }

    public function file_created($event)
    {
        // TODO: Implement file_created() method.
    }

    public function invoice_created($event)
    {
        // TODO: Implement invoice_created() method.
    }

    public function invoice_deleted($event)
    {
        // TODO: Implement invoice_deleted() method.
    }

    public function invoice_finalization_failed($event)
    {
        // TODO: Implement invoice_finalization_failed() method.
    }

    public function invoice_finalized($event)
    {
        // TODO: Implement invoice_finalized() method.
    }

    public function invoice_marked_uncollectible($event)
    {
        // TODO: Implement invoice_marked_uncollectible() method.
    }

    public function invoice_paid($event)
    {
        // TODO: Implement invoice_paid() method.
    }

    public function invoice_payment_action_required($event)
    {
        // TODO: Implement invoice_payment_action_required() method.
    }

    public function invoice_payment_failed($event)
    {
        // TODO: Implement invoice_payment_failed() method.
    }

    public function invoice_payment_succeeded($event)
    {
        // TODO: Implement invoice_payment_succeeded() method.
    }

    public function invoice_sent($event)
    {
        // TODO: Implement invoice_sent() method.
    }

    public function invoice_upcoming($event)
    {
        // TODO: Implement invoice_upcoming() method.
    }

    public function invoice_updated($event)
    {
        // TODO: Implement invoice_updated() method.
    }

    public function invoice_voided($event)
    {
        // TODO: Implement invoice_voided() method.
    }

    public function invoiceitem_created($event)
    {
        // TODO: Implement invoiceitem_created() method.
    }

    public function invoiceitem_deleted($event)
    {
        // TODO: Implement invoiceitem_deleted() method.
    }

    public function invoiceitem_updated($event)
    {
        // TODO: Implement invoiceitem_updated() method.
    }

    public function issuing_authorization_created($event)
    {
        // TODO: Implement issuing_authorization_created() method.
    }

    public function issuing_authorization_request($event)
    {
        // TODO: Implement issuing_authorization_request() method.
    }

    public function issuing_authorization_updated($event)
    {
        // TODO: Implement issuing_authorization_updated() method.
    }

    public function issuing_card_created($event)
    {
        // TODO: Implement issuing_card_created() method.
    }

    public function issuing_card_updated($event)
    {
        // TODO: Implement issuing_card_updated() method.
    }

    public function issuing_cardholder_created($event)
    {
        // TODO: Implement issuing_cardholder_created() method.
    }

    public function issuing_cardholder_updated($event)
    {
        // TODO: Implement issuing_cardholder_updated() method.
    }

    public function issuing_dispute_closed($event)
    {
        // TODO: Implement issuing_dispute_closed() method.
    }

    public function issuing_dispute_created($event)
    {
        // TODO: Implement issuing_dispute_created() method.
    }

    public function issuing_dispute_funds_reinstated($event)
    {
        // TODO: Implement issuing_dispute_funds_reinstated() method.
    }

    public function issuing_dispute_submitted($event)
    {
        // TODO: Implement issuing_dispute_submitted() method.
    }

    public function issuing_dispute_updated($event)
    {
        // TODO: Implement issuing_dispute_updated() method.
    }

    public function issuing_transaction_created($event)
    {
        // TODO: Implement issuing_transaction_created() method.
    }

    public function issuing_transaction_updated($event)
    {
        // TODO: Implement issuing_transaction_updated() method.
    }

    public function mandate_updated($event)
    {
        // TODO: Implement mandate_updated() method.
    }

    public function order_created($event)
    {
        // TODO: Implement order_created() method.
    }

    public function order_payment_failed($event)
    {
        // TODO: Implement order_payment_failed() method.
    }

    public function order_payment_succeeded($event)
    {
        // TODO: Implement order_payment_succeeded() method.
    }

    public function order_updated($event)
    {
        // TODO: Implement order_updated() method.
    }

    public function order_return_created($event)
    {
        // TODO: Implement order_return_created() method.
    }

    public function payment_intent_amount_capturable_updated($event)
    {
        // TODO: Implement payment_intent_amount_capturable_updated() method.
    }

    public function payment_intent_canceled($event)
    {
        // TODO: Implement payment_intent_canceled() method.
    }

    public function payment_intent_created($event)
    {
        // TODO: Implement payment_intent_created() method.
    }

    public function payment_intent_payment_failed($event)
    {
        // TODO: Implement payment_intent_payment_failed() method.
    }

    public function payment_intent_processing($event)
    {
        // TODO: Implement payment_intent_processing() method.
    }

    public function payment_intent_requires_action($event)
    {
        // TODO: Implement payment_intent_requires_action() method.
    }

    public function payment_intent_succeeded($event)
    {
        // TODO: Implement payment_intent_succeeded() method.
    }

    public function payment_method_attached($event)
    {
        // TODO: Implement payment_method_attached() method.
    }

    public function payment_method_detached($event)
    {
        // TODO: Implement payment_method_detached() method.
    }

    public function payment_method_updated($event)
    {
        // TODO: Implement payment_method_updated() method.
    }

    public function payout_canceled($event)
    {
        // TODO: Implement payout_canceled() method.
    }

    public function payout_created($event)
    {
        // TODO: Implement payout_created() method.
    }

    public function payout_failed($event)
    {
        // TODO: Implement payout_failed() method.
    }

    public function payout_paid($event)
    {
        // TODO: Implement payout_paid() method.
    }

    public function payout_updated($event)
    {
        // TODO: Implement payout_updated() method.
    }

    public function person_created($event)
    {
        // TODO: Implement person_created() method.
    }

    public function person_deleted($event)
    {
        // TODO: Implement person_deleted() method.
    }

    public function person_updated($event)
    {
        // TODO: Implement person_updated() method.
    }

    public function plan_created($event)
    {
        // TODO: Implement plan_created() method.
    }

    public function plan_deleted($event)
    {
        // TODO: Implement plan_deleted() method.
    }

    public function plan_updated($event)
    {
        // TODO: Implement plan_updated() method.
    }

    public function price_created($event)
    {
        // TODO: Implement price_created() method.
    }

    public function price_deleted($event)
    {
        // TODO: Implement price_deleted() method.
    }

    public function price_updated($event)
    {
        // TODO: Implement price_updated() method.
    }

    public function product_created($event)
    {
        // TODO: Implement product_created() method.
    }

    public function product_deleted($event)
    {
        // TODO: Implement product_deleted() method.
    }

    public function product_updated($event)
    {
        // TODO: Implement product_updated() method.
    }

    public function promotion_code_created($event)
    {
        // TODO: Implement promotion_code_created() method.
    }

    public function promotion_code_updated($event)
    {
        // TODO: Implement promotion_code_updated() method.
    }

    public function radar_early_fraud_warning_created($event)
    {
        // TODO: Implement radar_early_fraud_warning_created() method.
    }

    public function radar_early_fraud_warning_updated($event)
    {
        // TODO: Implement radar_early_fraud_warning_updated() method.
    }

    public function recipient_created($event)
    {
        // TODO: Implement recipient_created() method.
    }

    public function recipient_deleted($event)
    {
        // TODO: Implement recipient_deleted() method.
    }

    public function recipient_updated($event)
    {
        // TODO: Implement recipient_updated() method.
    }

    public function reporting_report_run_failed($event)
    {
        // TODO: Implement reporting_report_run_failed() method.
    }

    public function reporting_report_run_succeeded($event)
    {
        // TODO: Implement reporting_report_run_succeeded() method.
    }

    public function reporting_report_type_updated($event)
    {
        // TODO: Implement reporting_report_type_updated() method.
    }

    public function review_closed($event)
    {
        // TODO: Implement review_closed() method.
    }

    public function review_opened($event)
    {
        // TODO: Implement review_opened() method.
    }

    public function setup_intent_canceled($event)
    {
        // TODO: Implement setup_intent_canceled() method.
    }

    public function setup_intent_created($event)
    {
        // TODO: Implement setup_intent_created() method.
    }

    public function setup_intent_requires_action($event)
    {
        // TODO: Implement setup_intent_requires_action() method.
    }

    public function setup_intent_setup_failed($event)
    {
        // TODO: Implement setup_intent_setup_failed() method.
    }

    public function setup_intent_succeeded($event)
    {
        // TODO: Implement setup_intent_succeeded() method.
    }

    public function sigma_scheduled_query_run_created($event)
    {
        // TODO: Implement sigma_scheduled_query_run_created() method.
    }

    public function sku_created($event)
    {
        // TODO: Implement sku_created() method.
    }

    public function sku_deleted($event)
    {
        // TODO: Implement sku_deleted() method.
    }

    public function sku_updated($event)
    {
        // TODO: Implement sku_updated() method.
    }

    public function source_canceled($event)
    {
        // TODO: Implement source_canceled() method.
    }

    public function source_chargeable($event)
    {
        // TODO: Implement source_chargeable() method.
    }

    public function source_failed($event)
    {
        // TODO: Implement source_failed() method.
    }

    public function source_mandate_notification($event)
    {
        // TODO: Implement source_mandate_notification() method.
    }

    public function source_refund_attributes_required($event)
    {
        // TODO: Implement source_refund_attributes_required() method.
    }

    public function source_transaction_created($event)
    {
        // TODO: Implement source_transaction_created() method.
    }

    public function source_transaction_updated($event)
    {
        // TODO: Implement source_transaction_updated() method.
    }

    public function subscription_schedule_aborted($event)
    {
        // TODO: Implement subscription_schedule_aborted() method.
    }

    public function subscription_schedule_canceled($event)
    {
        // TODO: Implement subscription_schedule_canceled() method.
    }

    public function subscription_schedule_completed($event)
    {
        // TODO: Implement subscription_schedule_completed() method.
    }

    public function subscription_schedule_created($event)
    {
        // TODO: Implement subscription_schedule_created() method.
    }

    public function subscription_schedule_expiring($event)
    {
        // TODO: Implement subscription_schedule_expiring() method.
    }

    public function subscription_schedule_released($event)
    {
        // TODO: Implement subscription_schedule_released() method.
    }

    public function subscription_schedule_updated($event)
    {
        // TODO: Implement subscription_schedule_updated() method.
    }

    public function tax_rate_created($event)
    {
        // TODO: Implement tax_rate_created() method.
    }

    public function tax_rate_updated($event)
    {
        // TODO: Implement tax_rate_updated() method.
    }

    public function topup_canceled($event)
    {
        // TODO: Implement topup_canceled() method.
    }

    public function topup_created($event)
    {
        // TODO: Implement topup_created() method.
    }

    public function topup_failed($event)
    {
        // TODO: Implement topup_failed() method.
    }

    public function topup_reversed($event)
    {
        // TODO: Implement topup_reversed() method.
    }

    public function topup_succeeded($event)
    {
        // TODO: Implement topup_succeeded() method.
    }

    public function transfer_created($event)
    {
        // TODO: Implement transfer_created() method.
    }

    public function transfer_failed($event)
    {
        // TODO: Implement transfer_failed() method.
    }

    public function transfer_paid($event)
    {
        // TODO: Implement transfer_paid() method.
    }

    public function transfer_reversed($event)
    {
        // TODO: Implement transfer_reversed() method.
    }

    public function transfer_updated($event)
    {
        // TODO: Implement transfer_updated() method.
    }
}
