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
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>

<body>
    {{ auth()->user()->name ?? 'Nothing' }}
    <script>
        // Pusher.logToConsole = true;
        const pusher = new Pusher('18a73d95bd0eb4dedf89', {
            cluster: 'ap2'
        });
        pusher.connection.bind('error', function(err) {
            if (err.data.code === 4004) {
                log('Over limit!');
            }
        });

        const channel = pusher.subscribe('test-channel');

        pusher.allChannels().forEach(channel => console.log(channel.name));
        channel.bind('new-message.1', function(data) {
            console.log(data);
            alert('Event Triggered!');
        });


        pusher.connection.bind('connected', function() {
            fetch('/update-status', {
                method: 'POST',
                body: JSON.stringify({ online_status: 'online' }),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        });
        window.addEventListener('beforeunload', function() {
            navigator.sendBeacon('/update-status', JSON.stringify({ online: 'offline' }));
        });
    </script>
</body>

</html>