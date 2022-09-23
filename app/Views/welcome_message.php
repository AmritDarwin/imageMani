<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Manipulation</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.css" integrity="sha512-+VDbDxc9zesADd49pfvz7CgsOl2xREI/7gnzcdyA9XjuTxLXrdpuz21VVIqc5HPfZji2CypSbxx1lgD7BgBK5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.js" integrity="sha512-ZK6m9vADamSl5fxBPtXw6ho6A4TuX89HUbcfvxa2v2NYNT/7l8yFGJ3JlXyMN4hlNbz0il4k6DvqbIW5CCwqkw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>
</head>

<body>
    <div class="row">
        <div class="col-5 mt-5 px-3">
            <form id="form">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="name" name="name" class="form-control">
                </div>
                <!-- <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Mobile</label>
                    <input type="number" id="mobile" name="mobile" class="form-control">
                </div> -->
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" id="image" name="image" class="form-control js-photo-upload" accept="image/*">
                </div>
            </form>
        </div>
        <div class="col-7 mt-5 img-div">
            <img id="img" src="" class="d-none" alt="custom img">
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crop Photo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                    <img class="js-avatar-preview" src="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <input type="submit" type="button" value="Submit" class="btn btn-primary js-save-cropped-avatar">
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            let cropper;
            let cropperModalId = '#cropperModal';

            $('.js-photo-upload').on('change', function() {
                var files = this.files;
                if (files.length > 0) {
                    var photo = files[0];

                    var reader = new FileReader();
                    reader.onload = function(event) {
                        var image = $('.js-avatar-preview')[0];
                        image.src = event.target.result;
                        cropper = new Cropper(image, {
                            viewMode: 1,
                            aspectRatio: 1,
                            center: true,
                            movable: true,
                            minCropBoxWidth: 418,
                            minCropBoxHeight: 418,
                            data: {
                                height: 418,
                                width: 418
                            },
                            ready: function() {
                                console.log(image);
                            }
                        });

                        $(cropperModalId).modal("show");
                    };
                    reader.readAsDataURL(photo);
                }
            });

            $('.js-save-cropped-avatar').on('click', function(event) {
                event.preventDefault();
                const canvas = cropper.getCroppedCanvas();
                console.log(canvas);
                const base64encodedImage = canvas.toDataURL();
                const formData = new FormData();
                console.log(base64encodedImage);
                formData.append('name', $('#name').val());
                // formData.append('email', $('#email').val());
                // formData.append('mobile', $('#mobile').val());
                formData.append('image', base64encodedImage);
                axios.post('<?= base_url() ?>/image', formData).then(res => {
                    if (res.data.img) {
                        $('#img').attr('src', '<?= base_url() ?>/public/assets/' +
                            res.data.img).removeClass('d-none');
                        $(cropperModalId).modal("hide");
                    }
                })
            });


        });
    </script>
</body>

</html>