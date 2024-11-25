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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>

<body>
    
    <a href="{{route('shop.home')}}" style="color: rgb(97, 97, 250); margin-bottom: 10px; display: block;">Shop</a><br>
    <div id="channel-msg">

    </div>

    <button id="unbind-newmsg" style="background: black; color:white;">Unbind New Message</button>


    <button id="bind-newmsg" style="background: black; color:white;">Bind New Message</button>

    <br>
    {{ auth()->check() ? auth()->user()->name ?? 'no name' : 'Not Loggedin' }}
    <br>

    <br>
    {{ auth('admin')->check() ? 'admin - ' . auth('admin')->user()->name ?? 'no admin name' : 'No admin Loggedin' }}
    <br>

    <script>
        const pusher = new Pusher('18a73d95bd0eb4dedf89', {
            cluster: 'ap2'
        });
        pusher.connection.bind('error', function(err) {
            if (err.data.code === 4004) {
                log('Over limit!');
            }
        });

        pusher.connection.bind("connected", function(e) {
            console.log('connected', e.socket_id);
        });

        pusher.connection.bind("unavailable", function() {
            console.log('Unavailable');
        });

        const channel = pusher.subscribe('test-channel');
        channel.bind('new-message', function(data) {
            bindNewMessage(data);
        });

        document.getElementById('unbind-newmsg').addEventListener('click', function() {
            channel.unbind('new-message');
            console.log('New Message Unbind');
        });
        document.getElementById('bind-newmsg').addEventListener('click', function() {
            channel.unbind('new-message');
            channel.bind('new-message', function(data) {
                bindNewMessage(data);
            });
        });

        function bindNewMessage(data) {
            let msg = `<p>${data.msg}</p>`;
            // sendNotification('New Event Triggered', data.msg);
            document.getElementById('channel-msg').insertAdjacentHTML('beforeend', msg);
        }

        async function sendNotification(subject, body, img="https://developer.mozilla.org/favicon-48x48.bc390275e955dacb2e65.png", url = 'http://127.0.0.1:8000?from=notification') {
            let permission = Notification.permission;
            if (permission !== 'granted') {
                permission = await Notification.requestPermission();
            }
            if (permission === 'granted') {
                const notification = new Notification(subject, {
                    body: body,
                    icon: img
                });

                notification.onclick = function() {
                    window.open(url);
                    notification.close();
                };
            }
        }

        let isTabClosed = false;

        // Detect tab close
        window.addEventListener('beforeunload', function(event) {
            isTabClosed = true; // Set a flag when beforeunload is triggered
            sendDisconnectRequest();
        });

        // Optional: Use visibilitychange to differentiate between tab switching and closing
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden' && isTabClosed) {
                // The tab is closed
                sendDisconnectRequest();
            }
        });

        // Function to send the disconnect request
        function sendDisconnectRequest() {
            // $.ajax({
            //     url: "{{ route('change.status') }}",
            //     method: 'GET',
            //     async: false // Use cautiously
            // });
        }

        // Reset the flag if the tab is not closing
        window.addEventListener('pagehide', function() {
            isTabClosed = false;
        });
    </script>
    <script>
        window.addEventListener('load', function(){
            // setTimeout(() => {
            //     $.ajax({
            //     url: "{{ route('change.status2') }}",
            //     method: 'GET',
            //     async: false
            // });
            // }, 2000);
        });
    </script>
</body>

</html>
