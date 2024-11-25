<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
</head>

<body>
    <button class="history" data-name="dinesh">Dinesh</button>
    <button class="history" data-name="baidya">baidya</button>
    <button class="history" data-name="dinesh1">dinesh1</button>
    <button class="history" data-name="baidya1">baidya1</button>
    <div id="hey">
        {{ $name ?? '' }}
    </div>
    <script>
        window.addEventListener('load', function() {
            let backPressed = 0;

            $('.history').on('click', function() {
                const name = $(this).data('name');
                $('#hey').html(name);
                backPressed = 0;
                history.pushState({
                    name: name
                }, name, '/history-management/' + name);
            });

            window.addEventListener('popstate', function(event) {
                if(backPressed > 0){
                    window.history.back();
                }
                if (event.state) {
                    backPressed++;
                    $('#hey').html(event.state.name);
                } else {
                    $('#hey').html('');
                }
            });
        });
    </script>
</body>

</html>
