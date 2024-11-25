<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Send Mail</title>
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

    <form method="POST">
        @csrf
        <input type="text" name="first_name" placeholder="First Name"><br>
        <input type="text" name="last_name" placeholder="Last Name"><br>
        <input type="text" name="username" placeholder="Username"><br>
        <input type="text" name="email" placeholder="Email"><br>
        <input type="text" name="location" placeholder="Location"><br>
        <textarea name="question" placeholder="Question"></textarea>
        <button>Send</button>
    </form>
<body>

</body>

</html>
