<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Upload Image</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
    <form type="POST" enctype="multipart/form-data" id="uploadImgForm" name="uploadImgForm">
        @csrf
        <input type="file" name="image" id="imageInput" accept="jpg/*,png/*">
        <div>
            <img src="{{auth()->user()->profile_picture()}}" alt="{{auth()->user()->name ?? ''}}" style="height: 150px; width: 150px;">
            <p>ID: {{auth()->user()->id ?? ''}}</p>
            <p>Name: {{auth()->user()->name ?? ''}}</p>
            <p>Email: {{auth()->user()->email ?? ''}}</p>
        </div>
    </form>

    <button id="open_model">Open Model</button>


    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Modal Heading</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body p-0 m-5">
                    <img src="" alt="test" style="display: none" src="" id="image"
                        style="width: 100%;">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    <button id="cropButton" class="btn btn-info" type="button">Crop</button>
                </div>
            </div>
        </div>
    </div>



    <script>
        window.addEventListener('load', function() {
            let cropper;

            document.getElementById('imageInput').addEventListener('change', function(e) {
                document.getElementById('image').src = '';
                const files = e.target.files;
                const done = (url) => {
                    document.getElementById('image').src = url;
                    $('#myModal').modal('show');
                    cropperDefine();
                };

                if (files && files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        done(event.target.result);
                    };
                    reader.readAsDataURL(files[0]);
                }

            });

            function cropperDefine() {
                document.getElementById('image').style.display = 'block';


                if (cropper) {
                    cropper.destroy();
                }

                cropper = new Cropper(document.getElementById('image'), {
                    aspectRatio: 1,
                    viewMode: .5,
                });
            }

            document.getElementById('open_model').addEventListener('click', function() {
                $('#myModal').modal('show');
            });

            document.getElementById('cropButton').addEventListener('click', function() {
                const canvas = cropper.getCroppedCanvas();
                canvas.toBlob((blob) => {
                    const formData = new FormData(document.getElementById('uploadImgForm'));
                    formData.append('croppedImage', blob);

                    fetch('/upload-image', {
                            method: 'POST',
                            body: formData,
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            if(data.success){
                                window.location.reload();
                            }
                        });
                });
            });
        });
    </script>
</body>

</html>
