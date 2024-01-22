<?php include_once "header.php";
function getAllProducts()
{
    global $conn;

    $sql = "SELECT * FROM tbl_products where count>0";
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

$products = getAllProducts();
?>
<style>
    body {
        background-color: #f5f5f5;
        overflow-x: hidden;
    }

    .custom-text-color {
        color: white !important;
    }

    .product-card {
        position: relative;
        overflow: hidden;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .product-card:hover .overlay {
        opacity: 1;
    }

    .icon-plus3 {
        color: #fff;
    }

    #itemDropdown {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background: url('path-to-arrow-icon.png') no-repeat right center;
        /* Replace 'path-to-arrow-icon.png' with the path to your arrow icon */
        padding-right: 20px;
        /* Adjust as needed */
    }

    #itemDropdown option {
        background-color: #fff;
        /* Background color for each option */
        padding: 5px;
        /* Adjust as needed */
        display: flex;
        align-items: center;
    }

    #itemDropdown option img {
        margin-right: 5px;
        /* Adjust as needed */
        max-width: 20px;
        /* Adjust as needed */
    }

    .checkout-row-highlight {
        opacity: 1;
        background: rgba(0, 0, 0, 0.1);
        /* Add your desired background color */
        transition: background-color 0.3s ease;
        /* Add a smooth transition effect */
    }

    .toast-container {
        position: fixed;
        top: 10%;
        right: 30%;
        /* Change from bottom to top */
        max-width: 300px;
        z-index: 9999;
    }

    .toast {
        background-color: #333;
        color: #fff;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 10px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .toast.show {
        opacity: 1;
    }
</style>

<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-md-4">
                <h3><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;Product Listing</h3>

            </div>
            <div class="col-md-6">
                <div class="form-group">

                    <div class="toast-container" id="toastContainer"></div>

                </div>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary" onclick="checkout()"><i class="fa fa-shopping-cart mr-2"></i>Checkout</button>
            </div>

        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach ($products as $product) : ?>
                <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="card ">
                        <div class="card-img-actions product-card">
                            <a href="<?php echo $product['file']; ?>" data-popup="lightbox">
                                <img src="<?php echo $product['file']; ?>" style="height: 200px;" class="card-img" alt="Product Image">
                                <div class="overlay">
                                    <i class="icon-plus3 icon-2x"></i>
                                </div>
                            </a>
                        </div>
                        <div class="card-body" style="text-align:center">
                            <div class="mb-2">
                                <h6 class="font-weight-semibold mb-0">

                                    <a class="text-default"><?php echo $product['name']; ?></a>
                                </h6>
                                <a class="product-variant text-muted"> <?php echo $product['variant']; ?></a>

                            </div>
                            <h3 class="product-price mb-0 font-weight-semibold"> $<?php echo $product['price']; ?></h3>

                            <p> <?php echo $product['transmission'] 
                                     ?></p>
                            <p class="text-muted mb-3"><strong>Items Left: </strong> <?php echo $product['count']; ?></p>
                            <p class="product-description"><?php echo $product['description']; ?></p>
                            <button class="btn btn-primary" onclick="addToCart(<?php echo $product['id']; ?>)"><i class="fa fa-shopping-cart mr-"></i>Add to Cart</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="modal" id="checkoutModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Checkout Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="checkoutModalBody">
            </div>
            <div class="modal-footer">
                <div id="checkoutTotalDiv">Total Amount: $0.00</div>
                <button type="button" class="btn btn-primary" onclick="order()">Order Now</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
    function addToCart(productId) {
        $('.loader-container').css('display', 'flex')

        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: {
                productId: productId,
                action: 'getProductDetails'
            },
            success: function(response) {
                const data = JSON.parse(response);
                $('.loader-container').css('display', '');

                let modalDiv = $('#checkoutModalBody');
                if (modalDiv.length === 0) {
                    modalDiv = $('<div id="checkoutModalBody"></div>');
                    $('#checkoutModal .modal-body').append(modalDiv);
                }

                const existingEntry = modalDiv.find(`[data-product-id="${productId}"]`);
                if (existingEntry.length > 0) {
                    const currentCount = parseInt(existingEntry.attr('data-count')) || 0;
                    existingEntry.attr('data-count', currentCount + 1);
                    existingEntry.find('.count-value').text(currentCount + 1);

                    // Update total price for the existing entry
                    const totalPrice = (currentCount + 1) * data.productPrice;
                    existingEntry.find('.total-price').text(`Total Price: $${totalPrice.toFixed(2)}`);
                } else {
                    const newEntry = $(`
        <div class="checkout-entry" data-product-id="${productId}">
            <div class="row checkout-row" onmouseover="highlightRow(this)" onmouseout="unhighlightRow(this)">
                <div class="col-4">
                    <img src="${data.productImage}" alt="${data.productName}" class="checkout-image" style="max-width: 50px;">
                </div>
                <div class="col-4">
                    <p class="product-name">${data.productName}</p>
                </div>
                <p class="price" hidden> ${data.totalPrice} </p>
                <div class="col-4">
                    <button class="btn btn-outline-secondary rounded btn-sm" type="button" onclick="decrementCount(${productId})">-</button>
                    <button class="count-value rounded btn btn-outline-secondary btn-sm">1</button>
                    <button class="btn btn-outline-secondary rounded btn-sm" type="button" onclick="incrementCount(${productId})">+</button>
                </div>
            </div>
        </div>
    `);

                    modalDiv.append(newEntry);
                    displayToast(`${data.productName} added to cart`);


                }
                calculateTotalPrice();
            },
            error: function(xhr, status, error) {
                console.error('Error fetching product details:', error);
            }
        });
    }

    // Rest of your code remains the same
    function updateTotalPrice() {
        const totalAmount = calculateTotalPrice();
        $('#checkoutTotalDiv').text(`Total Amount: $${totalAmount.toFixed(2)}`);
    }

    function calculateTotalPrice() {
        var productData = {};
        var checkoutEntries = document.querySelectorAll('.checkout-entry');

        // Iterate through each checkout entry
        checkoutEntries.forEach(function(entry) {
            var productId = entry.getAttribute('data-product-id');
            var countElement = entry.querySelector('.count-value');
            var countPrice = entry.querySelector('.price');
            var countPriceValue = countPrice ? countPrice.innerText : '0';
            var countValue = countElement ? countElement.innerText : '0';
            productData[productId] = countValue * countPriceValue;

        });
        var totalSum = Object.values(productData).reduce(function(sum, value) {
            return sum + value;
        }, 0);
        console.log(totalSum)
        $('#checkoutTotalDiv').text(`Total Amount: $${totalSum.toFixed(2)}`);
    }

    function checkout() {
        $('#checkoutModal').modal('show');
    }

    function incrementCount(productId) {
        let modalDiv = $('#checkoutModalBody');
        if (modalDiv.length === 0) {
            modalDiv = $('<div id="checkoutModalBody"></div>');
            $('#checkoutModal .modal-body').append(modalDiv);
        }
        const entry = modalDiv.find(`[data-product-id="${productId}"]`);
        const currentCount = parseInt(entry.attr('data-count')) || 0;
        entry.attr('data-count', currentCount + 1);
        entry.find('.count-value').text(currentCount + 1);
        calculateTotalPrice()
    }

    function decrementCount(productId) {
        let modalDiv = $('#checkoutModalBody');
        if (modalDiv.length === 0) {
            modalDiv = $('<div id="checkoutModalBody"></div>');
            $('#checkoutModal .modal-body').append(modalDiv);
        }
        const entry = modalDiv.find(`[data-product-id="${productId}"]`);
        const currentCount = parseInt(entry.attr('data-count')) || 0;
        if (currentCount > 1) {
            entry.attr('data-count', currentCount - 1);
            entry.find('.count-value').text(currentCount - 1);
        }
        calculateTotalPrice()
    }

    function highlightRow(row) {
        $(row).addClass('checkout-row-highlight');
    }

    function unhighlightRow(row) {
        $(row).removeClass('checkout-row-highlight');
    }

    function displayToast(message) {
        const toastContainer = document.getElementById('toastContainer');

        const toast = document.createElement('div');
        toast.className = 'toast';
        toast.innerText = message;

        toastContainer.appendChild(toast);
        toast.offsetWidth;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 1000);
    }

    function order() {
        const entries = $('.checkout-entry');

        if (entries.length === 0) {
            alert('Your cart is empty.');
            return;
        }

        const orderDetails = [];

        entries.each(function() {
            const productId = $(this).data('product-id');
            const count = $(this).find('.count-value').text(); // Use find() for jQuery selection
            orderDetails.push({
                productId: productId,
                count: count
            });
        });
        console.log(orderDetails)
        $('.loader-container').css('display', 'flex');

        $.ajax({
            url: 'ajax.php',
            method: 'POST',
            data: {
                action: 'updateProductCounts',
                orderDetails: JSON.stringify(orderDetails)
            },
            success: function(response) {
                var res = JSON.parse(response);
                console.log(res);
                if (res.msg == "success") {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Product updated successfully!',
                    }).then((result) => {
                        if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to update product. ' + res.message,
                    });
                }
                $('#checkoutTotalDiv').empty();
                $('#checkoutModal').modal('hide');
                $('.loader-container').css('display', 'none');
                setTimeout(function() {
                   // window.location.reload();
                }, 500);
            },
            error: function(xhr, status, error) {
                console.error('Error updating product counts:', error);
                displayToast('Error placing the order. Please try again.');

                $('.loader-container').css('display', 'none');
            }
        });
    }
</script>