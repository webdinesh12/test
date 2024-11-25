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
    <div style="display: flex">
        <div style="width: 50%" id="pushersubscribe">

        </div>
        <div style="width: 50%" id="channel-msg">

        </div>
    </div>

    <button id="pusher1" style="background: black; color:white;">Subscribe Pusher 1</button>


    <button id="pusher2" style="background: black; color:white;">Subscribe Pusher 2</button>

    <script>
        let pusher = null;
        let channel = null; 

        function subscribePusher(pusherKey){
            if (pusher) {
                pusher.disconnect();
                pusher = null;
            }

            pusher = new Pusher(pusherKey, {
                cluster: 'ap2'
            });

            channel = pusher.subscribe('test-channel');
            channel.bind('new-message', function(data) {
                bindNewMessage(data);
            });

            let sentense = `<p>Subscribe to ${pusherKey == "18a73d95bd0eb4dedf89" ? "Pusher 1" : pusherKey == "c047094e35b8bf272ade" ? "Pusher 2" : "Pusher 3"}</p>`;
            document.getElementById('pushersubscribe').insertAdjacentHTML('beforeend', sentense);
        }

        document.getElementById('pusher1').addEventListener('click', function() {
            subscribePusher('18a73d95bd0eb4dedf89'); // this is pusher 1
        });
        document.getElementById('pusher2').addEventListener('click', function() {
            subscribePusher('c047094e35b8bf272ade'); // this is pusher 2
        });

        subscribePusher('29cd7876495941ad69be');

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
    </script>
</body>

</html>
