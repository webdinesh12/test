<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        * {
            margin: 0px;
            padding: 0px;
        }

        body {
            height: 100vh;
            max-height: 100vh;
            width: 100vw;
            max-width: 100vw;
            background: rgb(44, 44, 44);
            color: white;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
</head>

<body>
    <div id="quote">

    </div>
    <button id="get_quote">GET QUOTE</button>
    <script>
        window.addEventListener('load', function(){
            var i = 0;
            $('#get_quote').html('GET QUOTE'+i);
            document.getElementById('get_quote').addEventListener('click', function(){
                $('#get_quote').html('...');
                $.ajax({
                    method: 'GET',
                    url: 'https://dummyjson.com/quotes?skip='+i,
                    contentType: 'application/json',
                    success: function(result) {
                        $.ajax({
                            url: "{{route('add.quote')}}",
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN' : "{{csrf_token()}}"
                            },
                            data: {
                                quotes: result.quotes	
                            },
                            success: function(){
                                i +=30;
                                $('#get_quote').html('GET QUOTE'+i);
                            }
                        })
                    },
                    error: function ajaxError(jqXHR) {
                        console.error('Error: ', jqXHR.responseText);
                    }
                });
            });
        });
    </script>
</body>

</html>
