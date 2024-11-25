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
</head>
<body class="bg-dark">
    <div class="container">
        <div class="row">
            @forelse ($products as $item)
                <div class="col-4">
                    {{$item->name}} - ${{$item->price}}
                    <div>
                        <a href="{{route('buy.product', [$item->id])}}" class="btn btn-dark">Buy</a>
                    </div>
                </div>
            @empty
                <div class="d-flex justify-content-center align-items-center" style="height: 50vh;">
                    No Products Found.
                </div>
            @endforelse
        </div>
    </div>
    
</body>
</html>