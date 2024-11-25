<?php

namespace App\Repositary\Stripe;

interface  StripeRepo{
	// Write instensable functions
	public function test();
	public function retrivePaymentInfo($txn_id);
	public function createClientSecret($amount, $payment_method_id, $currency = 'usd');
	public function confirmPayment($paymentId);
	public function refundPayment($paymentId);
	public function recurringPayment($paymentIntentId, $amount);
}