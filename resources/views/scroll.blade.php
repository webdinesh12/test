<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
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

        .m-5 {
            margin: 20px;
            background: rgb(24, 24, 24);
        }

        .single-body {
            background: rgb(27, 27, 27);
            height: 100vh;
            max-height: 100vh;
            overflow: auto;
            width: 100%;
            max-width: 500px;
            margin: auto;
            color: rgb(143, 143, 143);
        }

        .date{
            color: white;
        }
    </style>
</head>

<body>
    <div class="single-body">
        <div id="innerWrapper">
            @include('inc/scroll-partial', compact('data'))
        </div>
    </div>
    <script>
        window.addEventListener('load', function() {
            // SCROLL TO BOTTOM WHEN PAGE LOAD
            document.querySelector('.single-body').scrollTop = document.querySelector('.single-body').scrollHeight;
            let singleBody = document.querySelector('.single-body');
            let nextPageUrl = @json($hasMorePage ? $nextPageUrl : false);
            let ajaxReuestOnProcess = false;
            $('.single-body').on('scroll', function() {
                if ($('.single-body').scrollTop() < 100 && !ajaxReuestOnProcess && nextPageUrl) {
                    ajaxReuestOnProcess = true;
                    console.log('call');
                    let initialHeight = $('.single-body')[0].scrollHeight;
                    $('#innerWrapper').prepend('<div class="m-5 loading">Loading</div>');
                    $.ajax({
                        url: nextPageUrl,
                        type: 'GET',
                        success: function(data) {
                            $('.loading').remove();
                            if (data.nextPageUrl) {
                                nextPageUrl = data.nextPageUrl;
                                $('#innerWrapper').prepend(data.html);
                                ajaxReuestOnProcess = false;
                                let newHeight = $('.single-body')[0].scrollHeight;   
                                setTimeout(() => {                                   
                                    if ($('.single-body').scrollTop() === 0) {       
                                        let scrollOffset = newHeight - initialHeight;
                                        $('.single-body').animate({
                                            scrollTop: scrollOffset
                                        }, 10);
                                    }
                                }, 100);
                            } else {
                                $('#innerWrapper').prepend('No More Quotes');
                            }
                        }
                    });

                }
            });


        });
    </script>
</body>

</html>
