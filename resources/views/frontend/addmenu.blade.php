<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biryani Palayam </title>
    <link rel="icon" type="image/png" href="/images/icon.png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/addmenu.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div id="one" class="col-sm-2">
                <!-- Content for the smaller side -->
                <div class="p-4">
                    <h2>ADMIN</h2>
                    <p></p><br>
                    <a class="home" href="{{ route('home') }}"><b>Home</b>
                        <hr class="dashed">
                    </a>
                    <a class="Profile" href="{{ route('profile') }}"><b>AdminProfile</b></a>
                    <hr class="dashed">
                    <a class="Franchise" href="{{ route('franchise') }}"><b>Franchise</b></a>
                    <hr class="dashed">
                    <a class="Menu" href="{{ route('menu') }}"><b>Menu</b></a>
                    <hr class="dashed">
                    <a class="Bidding" href="{{ route('bidding') }}"><b>Time Slots</b></a>
                    <hr class="dashed">
                    <a class="Pickuppoint" href="{{ route('pickuppoint') }}"><b>Pickup Point</b></a>
                    <hr class="dashed">
                    <a class="pickuptime" href="{{ route('pickuptime') }}"><b>pickup Time</b></a>
                    <hr class="dashed">
                    <a class="Orderhistry" href="{{ route('orderhistry') }}"><b>Order History</b></a>
                    <hr class="dashed">
                    <a class="Orderhistry" href="{{ route('customer') }}"><b>Customers</b></a>
                    <hr class="dashed">
                    <a class="Orderhistry" href="{{ route('winners') }}"><b>Winners</b></a>
                    <hr class="dashed">
                    <a class="Profile" href="{{ route('referralcode') }}"><b>Referral Code</b></a>
                    <hr class="dashed">
                    <a class="banner" href="{{ route('banner_page') }}"><b> Add Banner</b></a>
                    <hr class="dashed">
                    <a class="LogOut" href="{{ route('logout') }}"><b>Log Out</b></a>
                    <hr class="dashed">
                </div>
            </div>
            <div id="two" class="col-md-10">
                <div>
                    <img class="logo" src="images/icon.png" alt="Logo">
                </div>
                <!-- Content for the bigger side -->
                <div class="p-4">
                    <h2 class="head">Add Menu</h2><br>
                    <form id="biryaniForm">
                        <div class="form-group">
                            <label for="franchise">Select Franchise</label>
                            <select id="franchise" class="form-control" required>
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="menu_category_name">Name</label>
                            <input type="text" placeholder="Enter the name of the menu" class="form-control" id="menu_category_name" required>
                        </div>
                        <div class="form-group">
                            <label for="menu_image">Image</label>
                            <input type="file" class="form-control-file" id="menu_image" accept="image/*" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" placeholder="Enter brief discription of the menu" id="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="base_price">Original price </label>
                            <input type="number" class="form-control" placeholder="Enter the Original price for bidding" id="base_price" required>
                        </div>
                        <div class="form-group">
                            <label for="current_price">Base price</label>
                            <input type="number" class="form-control" placeholder="Enter the Base price for bidding" id="current_price" required>
                        </div>
                        {{-- <a id="addmenu" href="{{ route('add_menu_page') }}"> <i class="fas fa-plus-circle"></i> Add More</a><br><br> --}}
                        <button id="submit" type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div><br>
                <p id="copyright">Copyright © 2024 All Rights Reserved.</p>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            checkSession();
            // Function to handle form submission via AJAX
            $('#biryaniForm').submit(function(e) {
                e.preventDefault();
            });

            // Function to check if the session token is present or if the user is logged in
            
            // Function to add a new menu item to the menuItems array
            $('#submit').click(function(e) {
                e.preventDefault();

                // Get the selected franchise id and name
                var franchiseId = $('#franchise').val();
                var franchiseName = $('#franchise option:selected').text();

                // Get the menu category name
                var menuCategoryName = $('#menu_category_name').val();

                // Get the description
                var description = $('#description').val();

                // Get the base price
                var basePrice = $('#base_price').val();

                // Get the current price
                var currentPrice = $('#current_price').val();

                // Check if current price is provided
                if (!currentPrice) {
                    alert("Discounted Price is required.");
                    return;
                }

                // Get the menu image
                var menuImage = $('#menu_image').prop('files')[0]; // Assuming 'menu_image' is the id of the file input field

                // Create an object to store the menu item data
                var menuItem = {
                    franchise_id: franchiseId,
                    franchise_name: franchiseName,
                    menu_category_name: menuCategoryName,
                    description: description,
                    base_price: basePrice,
                    current_price: currentPrice,
                    menu_image: menuImage
                };

                // Submit the menu item
                submitMenuItem(menuItem);
            });

            fetchFranchises(); // Fetch franchises when the page loads
        });

        function submitMenuItem(menuItem) {
            // Create a FormData object to store form data
            var formData = new FormData();

            // Add menu item data to FormData object
            formData.append('franchise_id', menuItem.franchise_id);
            formData.append('franchise_name', menuItem.franchise_name);
            formData.append('menu_category_name', menuItem.menu_category_name);
            formData.append('description', menuItem.description);
            formData.append('base_price', menuItem.base_price);
            formData.append('current_price', menuItem.current_price);
            formData.append('menu_image', menuItem.menu_image);

            // Send AJAX request to save data
            $.ajax({
                url: "https://tabsquareinfotech.com/App/Abinesh_be_work/tsit_biriyani_palayam/public/api/create_menu",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    alert("Biryani item added successfully!");
                    // Reset form or do any additional handling
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    alert("Error occurred while adding biryani item. Please try again.");
                }
            });
        }

        function fetchFranchises() {
            $.ajax({
                url: 'https://tabsquareinfotech.com/App/Abinesh_be_work/tsit_biriyani_palayam/public/api/get_franchise',
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    console.log(response); // Log the entire response object
                    if (response && response.result) {
                        displayFranchiseNames(response.result);
                    } else {
                        console.error("No franchises found in response.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert("Error occurred while fetching franchises. Please try again.");
                }
            });
        }

        function displayFranchiseNames(franchises) {
            var franchiseDropdown = $('#franchise');
            franchiseDropdown.empty(); // Clear previous options

            // Check if franchises is an array and has at least one element
            if (Array.isArray(franchises) && franchises.length > 0) {
                // Create option elements for each franchise
                franchises.forEach(function(franchise) {
                    franchiseDropdown.append($('<option>', {
                        value: franchise.franchise_id,
                        text: franchise.franchise
                    }));
                });
            } else {
                console.error("No franchises found in response.");
            }
        }
    </script>

</body>

</html>
