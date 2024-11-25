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

        .full-screen-modal {
            width: 100vw !important;
            height: 100vh !important;
            margin: 0;
        }

        .modal-content {
            height: 100%;
            border: none;
            border-radius: 0;
        }

        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #image {
            height: 100%;
            width: 100%;
            object-fit: contain;
        }

        .modal-body{
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <form method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="files[]" multiple>
        <button>Submit</button>
    </form>
</body>
</html>