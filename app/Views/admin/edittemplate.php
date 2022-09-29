<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.css" integrity="sha512-+VDbDxc9zesADd49pfvz7CgsOl2xREI/7gnzcdyA9XjuTxLXrdpuz21VVIqc5HPfZji2CypSbxx1lgD7BgBK5g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.js" integrity="sha512-ZK6m9vADamSl5fxBPtXw6ho6A4TuX89HUbcfvxa2v2NYNT/7l8yFGJ3JlXyMN4hlNbz0il4k6DvqbIW5CCwqkw==" crossorigin="anonymous" referrerpolicy="no-referrer" deffer></script>
    <script src="<?= base_url('public/js/fabric.min.js') ?>" deffer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <h1>Admin</h1>
    <input type="text" onchange="onNameChange(event)">
    <select onchange="onNameColorChange(event)">
        <option value="black">black</option>
        <option value="white">white</option>
    </select>
    <button onclick="addName()">Add Name</button>
    <br>
    <br>
    <input style="margin-bottom: 10px;" id='bg' type="file" onchange="onBgChange(event)">
    <input style="margin-bottom: 10px;" id='file' type="file" onchange="onChange(event)">
    <button onclick="saveImageCo()">Save Image Position</button>
    <button onclick="saveNameCo()">Save Name Position</button>
    <button onclick="deleteElement()">Delete Item</button>
    <button onclick="saveTemplate()">Save Template</button>
    <canvas id="canvas"></canvas>
    <script>
        const canvas = new fabric.Canvas('canvas', {
            width: 800,
            height: 800,
            preserveObjectStacking: true,
        });
        // fabric.Image.fromURL("<?= base_url('public/assets/independence.png') ?>", (img) => {
        //     canvas.backgroundImage = img;
        //     canvas.renderAll();
        // });

        fabric.Image.fromURL("<?= base_url('public/assets/' . $details['background']) ?>", function(img) {
            canvas.backgroundImage = img;
            canvas.renderAll();
        });

        let userImageCoordinates;
        let userNameCoordinates;
        let userNameColor = 'black';
        let coordinates;
        let userName;

        function onNameColorChange(event) {
            userNameColor = event.target.value;
        }

        function onNameChange(event) {
            userName = event.target.value;
        }

        function onBgChange(event) {
            var url = URL.createObjectURL(event.target.files[0]);
            addImage(url, canvas);
        }

        function onChange(event) {
            var url = URL.createObjectURL(event.target.files[0]);
            setBackground(url, canvas);
        }

        function setBackground(url, canvas) {
            fabric.Image.fromURL(url, function(img) {
                canvas.add(img);
                canvas.sendToBack(img);
                canvas.renderAll();
            });
        }

        function addImage(url, canvas) {
            fabric.Image.fromURL(url, function(img) {
                // img.set({
                //     width: 800,
                //     height: 800
                // });
                // canvas.add(img);
                canvas.backgroundImage = img;
                canvas.renderAll();
            });
        }

        function addName() {
            var text = new fabric.Text(userName, {
                fill: userNameColor
            });
            canvas.add(text);
            canvas.renderAll();
        }

        canvas.on('object:moving', (e) => {
            coordinates = e.target.aCoords;
        })

        function saveImageCo() {
            userImageCoordinates = coordinates;
            console.log('image - ', userImageCoordinates)
        }

        function saveNameCo() {
            userNameCoordinates = coordinates;
            console.log('text - ', userNameCoordinates)
        }

        function deleteElement() {
            canvas.remove(canvas.getActiveObject());
        }

        function saveTemplate() {
            const formData = new FormData();
            formData.append('id', "<?= $details['id'] ?>")
            formData.append('name', 'independence')
            formData.append('background', document.getElementById('bg').files[0])
            formData.append('image_coordinates', JSON.stringify(userImageCoordinates))
            formData.append('name_coordinates', JSON.stringify(userNameCoordinates))
            formData.append('email_coordinates', '')
            formData.append('mobile_coordinates', '')
            formData.append('text_color', userNameColor)
            axios.post('<?= base_url() ?>/editTemplate', formData).then(res => {
                if (res.data) {
                    alert('added');
                }
            }).catch(err => {
                console.log(err)
            })
        }
        canvas.renderAll();
    </script>
</body>

</html>