<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Repositary\Stripe\StripeRepo;
use App\Rules\OrderStatus;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\InvalidRequestException;
use Stripe\StripeClient;

class OrderController extends Controller
{
    public $stripeRepo;
    public function __construct(StripeRepo $stripeRepo){
        $this->stripeRepo = $stripeRepo;
    }
    public function myOrders(){
        if(auth()->check()){
            $orders = Order::where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->paginate(5);
            return view('shop.my-orders', compact('orders'));
        }
        return response()->json(['success' => 0, 'msg' => 'Login First to Buy Products. Login Here = '.route('fake.login')]);
    }

    public function cancelOrder(Request $request){
        $validator = validator($request->all(), ['order_id'=> ['required', new OrderStatus()]]);
        if($validator->fails()){
            $error = [];
            foreach($validator->errors()->messages() as $item){
                $error[] = $item[0];
            }
            return response()->json(['success' => 0, 'msg' => $error]);
        }
        $order = Order::find($request->order_id);
        $orderPaymentInitialStatus = $order->payment_status;
        $order->payment_status = 'refunding';
        $order->save();
        DB::beginTransaction();
        try{
            $refundAmount = $order->price;
            $refund = $this->stripeRepo->refundPayment($order->txn_id, $refundAmount);
            if($refund['success'] > 0){
                $order->payment_status = 'refunded';
                $order->order_status = 'canceled';
                $order->refund_date = date('Y-m-d');
                $order->refund_amount = $refundAmount;
                $order->refund_id = $refund['data']['id'];
                $order->save();
                DB::commit();
                return response()->json(['success' => 1, 'msg' => 'The order successfully canceled and refund initiated.', 'data' => $refund]);
            }
            $order->payment_status = $orderPaymentInitialStatus;
            $order->save();
            return response()->json(['success' => 0, 'msg' => $refund['msg']]);
        }catch(Exception $err){
            DB::rollback();
            $order->payment_status = $orderPaymentInitialStatus;
            $order->save();
            return response()->json(['success' => 0, 'msg' => 'Something went wrong. '.  $err->getMessage()]);
        }
    }

    public function recurringPayment($payment_id = false){
        if(!$payment_id){
            $payment_id = 'pi_3QNCW6CxDSee9LjE0p5UWmzr';
        }
        dd($this->stripeRepo->recurringPayment($payment_id, 20));
    }
}
