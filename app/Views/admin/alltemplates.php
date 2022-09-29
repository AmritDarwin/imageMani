<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Templates</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="<?= base_url() ?>/public/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container mt-5 text-center">
        <h1>Click <a href="<?= base_url() ?>/admin/addTemplate">here </a>to add new template</h1>
        <h1>Admin All Templates</h1>
        <div class="row">
            <?php if (count($templates) > 0) : foreach ($templates as $val) : ?>
                    <div class="col-3 mt-5">
                        <img class="template" name="<?= $val['id'] ?>" onclick="navigate(this)" src="<?= base_url() . '/public/assets/' . $val['background'] ?>" alt="">
                        <button class="btn btn-danger mt-2" id="<?= $val['id'] ?>-id" onclick="deleteTemplate(this)">delete</button>
                    </div>
            <?php endforeach;
            endif; ?>
        </div>
    </div>
    <script>
        function navigate(e) {
            window.location.href = '<?= base_url() ?>/admin/template/' + e.name;
        }

        function deleteTemplate(e) {
            const formData = new FormData();
            formData.append('id', e.id);
            axios.post('<?= base_url("/admin/deleteTemplate") ?>', formData).then(res => {
                if (res) {
                    alert('deleted');
                    window.location.reload();
                } else alert('something went wrong')
            }).catch(err => {
                alert(err.message)
            })
        }
    </script>
</body>

</html>