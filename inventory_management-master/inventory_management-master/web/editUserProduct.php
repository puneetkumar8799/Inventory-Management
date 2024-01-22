<?php
include_once "header.php";

if (empty($_SESSION)) {
    echo '<meta http-equiv="refresh" content="0;url=listProducts.php">';
    exit();
}
function getAllProducts()
{
    global $conn;

    // Assuming you have sanitized the input to avoid SQL injection
    $productID = $_GET['productID'];

    $sql = "SELECT * FROM tbl_products WHERE id = '$productID'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = array();
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    } else {
        return array();
    }
}

$product = getAllProducts();


?>

<style>
   body {
        background-color: #f5f5f5;
        overflow-x: hidden;
    }

    .custom-file-upload-btn {
        background-color: #007bff;
        /* Blue background color */
        color: #fff;
        /* Text color */
        border: none;
        /* Remove border */
        padding: 8px 12px;
        /* Adjust padding */
        border-radius: 4px;
        /* Optional: Add border-radius for rounded corners */
    }

    .custom-file-upload {
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;
        background-color: #3498db;
        color: #fff;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .custom-file-upload:hover {
        background-color: #2980b9;
    }

    input[type="file"] {
        display: none;
    }

    .color-option {
        display: flex;
        align-items: center;
    }

    .color-square {
        width: 20px;
        height: 20px;
        margin-left: 10px;
        border: 1px solid #ccc;
    }
</style>
<div class="card p-1 mr-5 ml-5 mt-2 mb-2">
    <div class="card-header">
        <div class="row">
            <div class="col-md-8">

                <h3 class="card-title">Update Product</h3>
            </div>
            <div class="col-md-2">
                <a class="btn btn-primary btn-block mt-3 text-white" href="listProducts.php" type="button"> <i class="fa fa-th-list custom-text-color mr-2"> </i> Market</a>

            </div>
            <div class="col-md-2">
                <a class="btn btn-primary btn-block mt-3 text-white" href="listUserProducts.php" type="button"> <i class="fa fa-th-list custom-text-color mr-2"> </i> List My Products</a>

            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="form-group mb-5">
            <label for="name">Name</label>
            <input class="form-control" type="text" id="name" placeholder="Enter car part name" value="<?= $product[0]['name']  ?>" />
        </div>
        <div class="form-group mb-5">
            <label for="variant">Make/Model/Variant</label>
            <input class="form-control" type="text" id="variant" value="<?= $product[0]['variant']  ?>" placeholder="Enter car part make model variant" />
        </div>
        <div class="form-group mb-5">
            <label for="color">Select Color of part</label>
            <select class="form-control" id="color">
                <?php
                $colors = array("red", "white", "blue", "green", "yellow", "orange", "purple", "pink", "brown", "cyan", "magenta", "teal", "indigo", "lime", "maroon", "navy", "olive", "silver", "violet", "gold", "coral");
                echo '<option value="">Select the color</option>';

                foreach ($colors as $color) {
                    echo '<option value="' . $color . '"';
                    if ($color == $product[0]['color']) {
                        echo ' selected';
                    }
                    echo '>';
                    echo ucfirst($color); 
                    echo '</option>';
                }
                ?>
            </select>
        </div>

        <!-- <div class="form-group mb-5">
            <label for="mileage"> Mileage</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">KM</span>
                </div>
                <input class="form-control" type="text" id="mileage" value="<?= $product[0]['mileage']  ?>" placeholder="Enter car part mileage" />
            </div>
        </div> -->
        <div class="form-group mb-5">
            <label for="price"> Price</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">USD</span>
                </div>
                <input class="form-control" type="text" id="price" value="<?= $product[0]['price']  ?>" placeholder="Enter car part price" />
            </div>
        </div>
        <div class="form-group mb-5">
            <label for="transmission">Add the car </label>
            <input class="form-control" type="text" id="price" value="<?= $product[0]['transmission']  ?>" placeholder="Enter car  name" />

        </div>
        <div class="form-group mb-5">
            <label for="description">Description</label>
            <textarea class="form-control" rows="3" cols="3" id="description" placeholder="Enter car part description"><?= $product[0]['description'] ?></textarea>
        </div>
        <div class="form-group mb-5">
            <label for="make">Number of items</label>
            <input class="form-control" type="text" id="count" placeholder="Enter car part count" value="<?= $product[0]['count']  ?>" />
        </div>
        <div class='form-input-box' id="date_input_box">
            <label for="description">Add Image</label>

            <div class="input-group">
                <input type="text" class="form-control" id="filename">
                <div class="input-group-append">
                    <label for="articleFile" class="custom-file-upload">
                        <i class="icon-upload"></i> Choose File
                    </label>
                    <input id='articleFile' value="<?= $product[0]['file'] ?>" name='articleFile' class='form-control' type="file" onchange="previewFileAndSetFileName(event)" accept=".jpg, .png, .jpeg" />
                </div>
            </div>

            <img src="<?= $product[0]['file'] ?>" id="output_image" style="height: 150px; width: 150px; border-radius: 10px;">

        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block mt-3" onclick="update()" type="button" name="submit">Update</button>
        </div>

    </div>
</div>






<script>
    document.addEventListener("DOMContentLoaded", function() {
        $("#color").select2({
            placeholder: 'Select a color',
            allowClear: true
        });

        // Trigger change event to update Select2 after setting the selected value
        $("#color").trigger('change');
    });


    function update() {
        var name = $('#name').val();
        var variant = $('#variant').val();
        var color = $('#color').val();
        var price = $('#price').val();
        var transmission = $('#transmission').val();
        var description = $('#description').val();
        var count = $('#count').val();
        var file = $('#articleFile')[0].files[0];
        if (name !== "" && name != undefined && price != "" && price != undefined && color != "" && color != undefined) {
            var formData = new FormData();
            if (file != "" && file != undefined) {

                formData.append('file', $('#articleFile')[0].files[0]);
            }
            var id = "<?= $product[0]['id'] ?>";
            formData.append('action', 'updateProduct');
            formData.append('name', $('#name').val());
            formData.append('id', id);
            formData.append('variant', $('#variant').val());
            formData.append('color', $('#color').val());
            formData.append('mileage', $('#mileage').val());
            formData.append('price', $('#price').val());
            formData.append('transmission', $('#transmission').val());
            formData.append('count', $('#count').val());
            formData.append('description', $('#description').val());
            $('.loader-container').css('display', 'flex')

            $.ajax({
                url: 'ajax.php',
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    $('.loader-container').css('display', 'none');
                    var response = JSON.parse(data);
                    if (response.msg == "success") {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Product added successfully!',
                        }).then((result) => {
                            if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add product. ' + response.message,
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred while processing the request.',
                    });
                }
            });

        } else if (name == "" || name == undefined) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter car part name',
            });
        } else if (color == "" || color == undefined) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please enter car part color',
            });
        } else if (file == "" || file == undefined) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select one image',
            });
        } else if (price == "" || price == undefined) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please add car part price',
            });
        }
    }

    function preview_image(event) {
        var reader = new FileReader();
        reader.onload = function() {

            var output = document.getElementById('output_image');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    function previewFileAndSetFileName(event) {
        var input = event.target;
        var fileName = input.files[0].name;

        // Display the filename in the input field
        $('#filename').val(fileName);

        // Check if the file is an image and handle preview
        if (/\.(jpe?g|png|gif|jfif)$/i.test(fileName)) {
            $('#video_preview').hide();
            $('#output_image').show();
            preview_image(event);
        } else if (/\.(mp4|avi|mkv|mov|wmv)$/i.test(fileName)) {
            // Set the video preview
            $('#output_image').hide();
            $('#video_preview').show();
            var videoURL = URL.createObjectURL(input.files[0]);
            $('#video_preview_src').attr('src', videoURL);
            // Release the object URL resources when it's no longer needed
            URL.revokeObjectURL(videoURL);
        } else {
            // Handle other file types as needed
            $('#output_image').hide();
            $('#video_preview').hide();
        }
    }
</script>
</body>

</html>