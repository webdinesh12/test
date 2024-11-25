<?php

namespace App\Repositary\Stripe;

use Exception;
use Stripe\Stripe;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Charge;
use Stripe\Customer;
use Stripe\Exception\CardException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Refund;

class StripeRepoImpl implements StripeRepo
{
	private $stripe;
	public function __construct()
	{
		$this->stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
	}
	// Write implements functions
	public function test()
	{
		Log::info('Test');
	}

	public function retrivePaymentInfo($txn_id)
	{
		Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
		return PaymentIntent::retrieve($txn_id);;
	}

	public function createCustomer($customer_details){
		$customerObject = new Customer();
		$customer = $customerObject->create([
			'name' => $customer_details['name'] ?? '',
			'email' => $customer_details['email'] ?? ''
		]);
		return $customer->id;
	}

	public function createClientSecret($amount, $payment_method_id, $currency = 'usd', $customer_details = [])
	{
		$customerId = false;
		if (!empty($customer_details)) {
			Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
			$customer = $this->stripe->customers->all(['email'=> $customer_details['email'], 'limit' => 1]);
			if(empty($customer->data)){
				$customerId = $this->createCustomer($customer_details);
			}else{
				if($customer->data[0]->name == $customer_details['name']){
					$customerId = $customer->data[0]->id;
				}else{
					$customerId = $this->createCustomer($customer_details);
				}
			}			
		}

		$paymentData = [
			'amount' => ($amount * 100),
			'currency' => 'usd',
			'payment_method_types' => ['card'],
			'setup_future_usage' => 'off_session',
			'payment_method' => $payment_method_id
		];
		if ($customerId) {
			$paymentData['customer'] = $customerId;
		}
		$payment = $this->stripe->paymentIntents->create($paymentData);
		if ($payment) {
			return ['success' => 1, 'payment_id' => $payment->id, 'client_secret' => $payment->client_secret];
		}
		return ['success' => 0];
	}

	public function confirmPayment($paymentId)
	{
		$secret_key = env('STRIPE_SECRET_KEY');
		Stripe::setApiKey($secret_key);
		$stripe = new Charge($secret_key);

		return $stripe->create(
			[
				'payment_intent' => $paymentId
			]
		);
	}

	public function refundPayment($paymentId, $amount = false)
	{
		try {
			Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
			$refundClient = new Refund();
			$refundData = ['payment_intent' => $paymentId];
			if ($amount) {
				$refundData['amount'] = $amount * 100;
			}
			$refund = $refundClient->create($refundData);
			return ['success' => 1, 'msg' => 'Refund initiated.', 'data' => $refund];
		} catch (CardException $err) {
			return ['success' => 0, 'msg' => $err->getError()->message];
		} catch (InvalidRequestException $err) {
			$returnMsg = "";
			$error = json_decode($err->getHttpBody());
			if (($error->error->code ?? "") != "" && $error->error->code == 'charge_already_refunded') {
				$returnMsg = 'The payment already refunded';
			} else {
				$returnMsg = $error->error->message;
			}
			return ['success' => 0, 'msg' => $returnMsg];
		} catch (Exception $err) {
			return ['success' => 0, 'msg' => $err->getMessage()];
		}
	}

	public function recurringPayment($paymentIntentId, $amount){
        try{
            $paymentIntentDetails = $this->stripe->paymentIntents->retrieve($paymentIntentId);
            $pay =  $this->stripe->paymentIntents->create([
                'amount' => $amount * 100,
                'currency' => 'usd',
                'customer' => $paymentIntentDetails->customer,
                'payment_method' => $paymentIntentDetails->payment_method,
                'off_session' => true,
                'confirm' => true,
            ]);
			if($pay->status == 'succeeded'){
				return ['success' => 1, 'msg' => 'Payment successfull.'];
			}
			return ['success' => 0, 'msg' => 'Something went wrong.'];
        }catch(InvalidRequestException $err){
			return ['success' => 0, 'msg' => $err->getMessage()];
        }
	}
}
