<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Repositary\Stripe\StripeRepo;
use App\Rules\ProductExists;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Charge;

class ShopController extends Controller
{
    private $stripeRepo;
    public function __construct(StripeRepo $stripeRepo)
    {   
        $this->stripeRepo = $stripeRepo;
    }
    public function index()
    {
        $products = Product::get();
        return view('shop.index', compact('products'));
    }
    public function buyProduct($product_id)
    {
        if(auth()->check()){
            $product = Product::findOrFail($product_id);
            $transaction_fees = ($product->price * 0.29) + 0.30;
            $to_pay =  $product->price + $transaction_fees;
            $order_data = [
                'product_id' => $product_id,
                'fees' => number_format($product->price, 2, '.', ''),
                'transaction_fees' => number_format($transaction_fees, 2, '.', ''),
                'to_pay' => number_format($to_pay, 2, '.', '')
            ];
            session()->put('order_data', $order_data);
            return view('shop.buy', compact('product', 'transaction_fees', 'to_pay'));
        }
        return response()->json(['success' => 0, 'msg' => 'Login First to Buy Products. Login Here = '.route('fake.login')]);
    }

    public function doBuyProduct(Request $request){
        if(session()->has('order_data')){
            $order_data = session()->get('order_data');
            $rules = [
                'product_id' => ['required', new ProductExists()]
            ];
            $validation = validator()->make($order_data, $rules);
            if($validation->fails()){
                $returnValidationMsg = [];
                foreach ($validation->errors()->toArray() as $key => $value) {
                    $returnValidationMsg[$key] = $value[0];
                }
                return response()->json(['success' => 0, 'errors' => count($returnValidationMsg), 'data' => $returnValidationMsg, 'mgs' => 'Validation Errors.']);
            }

            $paymentIntent = $this->stripeRepo->createClientSecret($order_data['to_pay'], $request->payment_method_id, 'usd', ['name' => auth()->user()->name, 'email' => auth()->user()->email]);
            if($paymentIntent['success']){
                $clientSecret = $paymentIntent['client_secret'] ?? '';
                if($clientSecret == null){
                    return response()->json(['success' => 0, 'msg' => 'Something went wrong.']);
                }
                $order = new Order();
                $order->user_id = auth()->user()->id;
                $order->product_id = $order_data['product_id'];
                $order->price = $order_data['fees'];
                $order->total_paid = $order_data['to_pay'];
                $order->transaction_fees = $order_data['transaction_fees'];
                $order->txn_id = $paymentIntent['payment_id'];
                $order->created_at = date('Y-m-d H:i:s');
                $order->save();
                return response()->json(['success' => 1, 'data' => ['client_secret' => $clientSecret, 'order_id' => $order->id]]);
            }
            return response()->json(['success' => 0, 'msg' => 'Payment Unsuccessfull.']);
        }
        return response()->json(['success' => 0, 'msg' => 'No Session Found.']);
    }

    public function thankYou($paymentId){
        $order = Order::where('txn_id', $paymentId)->firstOrFail();
        if($order->payment_status != 'succeed'){
            return 'Payment not confirmed';
            // dd($this->stripeRepo->confirmPayment($paymentId));
            // $stripe->charges->all
            // (['payment_intent
            // ' => '{{PAYMENT_INTENT_ID}}'
            // ]);
        }
       return view('shop.thank-you');
    }

    public function paymentConfirmed(Request $request){
        if(session()->has('order_data')){
            $order = Order::findOrFail($request->order_id);
            $order->payment_status = 'succeed';
            $order->order_status = 'ordered';
            $order->save();
            session()->forget('order_data');
            return response()->json(['success' => 1, 'data' => ['payment_id' => $order->txn_id]]);
        }
        return response()->json(['success' => 0, 'msg' => 'No Session Found.']);
    }

    public function retriveFunction($id='pi_3QHeMyCxDSee9LjE1qcRfAHf'){
        echo $id;
        dd($this->stripeRepo->retrivePaymentInfo($id)->toArray());
    }

    public function paymentConfirm(){
        dd('test');
    }
}
