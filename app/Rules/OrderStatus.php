<?php

namespace App\Rules;

use App\Models\Order;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OrderStatus implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $order = Order::find($value);
        if(!$order){
            $fail('The '.$attribute.' is invalid.');
            return;
        }
        if(!$order->order_status == 'pending'){
            $fail('The order status is pending.');
            return;
        }
        if($order->order_status == 'delivered'){
            $fail('The order is delivered can not be refund.');
            return;
        }
        if($order->payment_status == 'refunding'){
            $fail('Refunding process already started for this order.');
            return;
        }
        if($order->order_status == 'canceled' && $order->payment_status == 'refunded' && $order->refund_date != null){
            $fail('The order has already canceled.');
            return;
        }
    }
}
