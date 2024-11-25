<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Shop</title>
    <style>
        * {
            margin: 0px;
            padding: 0px;
            color: white;
        }

        body {
            height: 100vh;
            max-height: 100vh;
            width: 100vw;
            max-width: 100vw;
            background: rgb(44, 44, 44);
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body class="bg-dark">
    <div class="container">
        <form action="{{ route('do.buy.product') }}" method="POST" style="width: 518px; max-width: 100%;"
            id="buy-product-form">
            @csrf
            <p>Product Price: USD {{ number_format($product->price, 2) }}</p>
            <p>Transaction Fees: USD {{ number_format($transaction_fees, 2) }}</p>
            <p>To Pay: USD {{ number_format($to_pay, 2) }}</p>
            <div id="stripe_element" class="p-2" style="background: white;">
                {{-- <div id="card-number" style="height: 25px; font-size: 20px; background:white;" class="mb-2"></div>
                <div id="card-expiry" style="height: 25px; font-size: 20px; background:white;" class="mb-2"></div>
                <div id="card-cvv" style="height: 25px; font-size: 20px; background:white;" class="mb-2"></div> --}}
            </div>
            <button class="btn btn-dark" id="submit-btn">Submit</button>
        </form>
    </div>
    <script>
        window.addEventListener('load', function() {
            /**
             * STRIPE CARD MOUNT
             */

            console.log("Stripe Public Key: {{ env('STRIPE_PUBLIC_KEY') }}");
            var stripe = Stripe("{{ env('STRIPE_PUBLIC_KEY') }}");
            console.log(stripe);
            var elements = stripe.elements();
            var cardElement = elements.create('card');
            // var cardElement = elements.getElement('card');
            console.log(cardElement);
            // var cardNumber = elements.create('cardNumber');
            // var cardExpiry = elements.create('cardExpiry');
            // var cardCvc = elements.create('cardCvc');

            cardElement.mount('#stripe_element');
            // cardExpiry.mount('#card-expiry');
            // cardCvc.mount('#card-cvv');

            // cardElement.on('change', function(event) {
            //     var displayError = document.getElementById('card-errors');
            //     if (event.error) {
            //         console.log(event);
            //     }

            //     console.log(event);
            // });

            $('#buy-product-form').on('submit', async function(e) {
                e.preventDefault();
                disableSubmitBtn();
                const form = $(this);
                /**
                 * VALIDATE THE CARD
                 */
                var displayError = document.getElementById('card-errors');
                const cardElementContainer = document.querySelector('#stripe_element');

                let cardElementEmpty = cardElementContainer.classList.contains('StripeElement--empty');
                let cardElementInvalid = cardElementContainer.classList.contains('StripeElement--invalid');
                if (cardElementEmpty || cardElementInvalid) {
                    alert("card is not valid.");
                    cardElement.focus();  
                    enableSubmitBtn();
                    return;
                }
                /**
                 * CREATE PAYMENT METHOD
                 */

                const promise1 = new Promise((resolve, reject) => {
                    resolve(stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                        billing_details: {
                            name: "{{auth()->user()->name ?? 'Dinesh Web'}}",
                        },
                    }));
                });
                promise1.then((value) => {
                    $.ajax({
                        url: e.target.action,
                        type: e.target.method,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: {
                            'payment_method_id' : value.paymentMethod.id,
                            'name' : "{{auth()->user()->name ?? 'Dinesh Web'}}"
                        },
                        success: function(res) {
                            if (res.success > 0) {
                                (async () => {
                                    const {paymentIntent, error} = await stripe.confirmCardPayment(res.data.client_secret);
                                    if (error) {
                                        alert('confirm payment error');
                                        enableSubmitBtn();
                                    } else if (paymentIntent && paymentIntent.status === 'succeeded') {
                                        $.ajax({
                                            url: "{{route('payment.confirmed')}}",
                                            type: "post",
                                            headers: {
                                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                            },
                                            data: {
                                                'order_id' : res.data.order_id,
                                            },
                                            success: function(res){
                                                if(res.success){
                                                    window.location.href = "{{ route('thank.you', ['payment_id' => '__PAYMENTCLIENTSECRET__']) }}".replace('__PAYMENTCLIENTSECRET__', res.data.payment_id);
                                                }else{
                                                    alert(res.msg);
                                                    enableSubmitBtn();
                                                }   
                                            },
                                            error: function(){
                                                enableSubmitBtn();
                                            }
                                        });                                        
                                    }
                                })();
                            } else {
                                alert(res.msg);
                                enableSubmitBtn();
                            }
                        },
                        error: function(error) {
                            enableSubmitBtn();
                            console.log(error);
                        }

                    });

                }).catch((error) => {
                    alert('Payment method nto created');
                    enableSubmitBtn();
                });
            });
            /**
             * STRIPE CARD MOUNT
             */

            function disableSubmitBtn(){
                $('#submit-btn').css({
                    'pointer-events':'none',
                    'opacity' : '.5'
                });
                $('#submit-btn').html('Please Wait...');
            }
            function enableSubmitBtn(){
                $('#submit-btn').css({
                    'pointer-events':'auto',
                    'opacity' : '1'
                });
                $('#submit-btn').html('Submit');
            }

        });
    </script>
</body>

</html>
