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

        .small.text-muted{
            color: white !important;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark">
    <div class="container">
        <a href="{{route('shop.home')}}">Shop</a>
        <div>
            <table class="table table-responsive table-dark">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th class="text-center">Payment Status</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Total Paid</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Order Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $item)
                        <tr>
                            <td>{{$item->product->name ?? '-'}}</td>
                            <td class="text-center">{{ucfirst($item->payment_status) ?? '-'}}</td>
                            <td class="text-center">${{$item->price ?? '-'}}</td>
                            <td class="text-center">${{$item->total_paid ?? '-'}}</td>
                            <td class="text-center">
                                @if($item->created_at)
                                    {{date("d-m-Y", ($item->created_at->timestamp) ?? false) ?? '-'}}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="text-center">{{ucfirst($item->order_status) ?? '-'}}</td>
                            <td class="text-center">
                                @if ($item->order_status == 'ordered')
                                    <button class="btn btn-danger cancel_refund" data-order_id="{{$item->id}}">
                                        Cancel & Refund
                                    </button>
                                @else
                                    -
                                @endif
                                
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">
                                No record found.
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
        <div class="pagination" style="height: 50px; width: 100%; color: white;">
            {{$orders->links('pagination::bootstrap-5')}}
        </div>
    </div>

    <script>
        window.addEventListener('load', function(){
            $('.cancel_refund').on('click', function(){
                let order_id = $(this).data('order_id');
                if (confirm("Are you sure want to cancel and refund?") == true && order_id) {
                    $.ajax({
                        url: '{{route("cancel.order")}}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN' : "{{csrf_token()}}"
                        },
                        data: {
                            order_id: order_id
                        },
                        success: function(res){
                            console.log(res);
                            alert(res.msg);
                            window.location.reload();
                        }

                    });
                }
            });
            
        });
        
    </script>

</body>

</html>
