<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="<?= base_url() ?>/public/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container mt-5 text-center">
        <h1>Templates</h1>
        <div class="row">
            <?php foreach ($category as $val) : ?>
                <div class="col-3 mt-5" id="<?= $val['name'] ?>" onclick="navigate(this)">
                    <img class="template" src="<?= base_url() . '/public/assets/' . $val['background'] ?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script>
        function navigate(e) {
            window.location.href = '<?= base_url() ?>/template/' + e.id;
        }
    </script>
</body>

</html>