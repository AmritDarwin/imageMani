<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Manipulation</title>
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
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
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email" class="form-control">
                </div>
                <div class="form-group">
                    <label>Mobile</label>
                    <input type="number" id="mobile" name="mobile" class="form-control">
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" id="image" name="image" class="form-control">
                </div>
                <div class="form-group mt-3">
                    <input type="submit" type="button" value="Submit" class="btn btn-dark">
                </div>
            </form>
        </div>
        <div class="col-7 mt-5 img-div">
            <img id="img" src="" class="d-none" alt="custom img">
        </div>
    </div>
    <script>
        $(document).ready(function() {

            $(form).on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData();
                formData.append('name', $('#name').val());
                formData.append('email', $('#email').val());
                formData.append('mobile', $('#mobile').val());
                formData.append('image', document.getElementById('image').files[0]);
                axios.post('http://localhost/imageMani/image', formData).then(res => {
                    if (res.data.img) {
                        $('#img').attr('src', '<?= base_url() ?>/public/assets/' +
                            res.data.img).removeClass('d-none');
                    }
                })
            });
        });
    </script>
</body>

</html>