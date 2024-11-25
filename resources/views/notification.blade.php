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
</head>

<body>
    <button id="enable">Enable notifications</button>
    <button id="sendNotification">Send Notification</button>
    <script>
        let notificationBtn = document.getElementById('enable');
        let sendNotificationBtn = document.getElementById('sendNotification');
        notificationBtn.addEventListener('click', askNotificationPermission);
        sendNotificationBtn.addEventListener('click', sendNotification);

        function askNotificationPermission() {
            // Check if the browser supports notifications
            if (!("Notification" in window)) {
                console.log("This browser does not support notifications.");
                return;
            }
            Notification.requestPermission().then((permission) => {
                console.log('permission - ', permission);
                // set the button to shown or hidden, depending on what the user answers
                // notificationBtn.style.display = permission === "granted" ? "none" : "block";
            });
        }

        async function sendNotification() {
            let permission = Notification.permission;

            if (permission !== 'granted') {
                permission = await Notification.requestPermission();
            }

            if (permission === 'granted') {
                const img = "https://developer.mozilla.org/favicon-48x48.bc390275e955dacb2e65.png";
                const text = `HEY! Your task is now overdue.`;
                const notification = new Notification("To do list", {
                    body: text,
                    icon: img
                });

                notification.onclick = function() {
                    window.open('http://127.0.0.1:8000?from=notification', '_blank');
                    notification.close();
                };
            }
        }
    </script>
</body>

</html>
