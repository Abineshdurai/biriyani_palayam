<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biryani Palayam</title>
    <link rel="icon" type="image/png" href="/images/icon.png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/menu.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div id="one" class="col-sm-2">
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
                <div class="size"><br>
                    <h2 class="head">Menu Details</h2>
                    <div id="franchiseAccordion" class="accordion"></div>
                </div>
                <div>
                    <a id="addmenu" class="btn btn-primary" href="{{ route('add_menu_page') }}">Add Menu</a>
                </div><br>
                <div class="hidden-menus">
                    <h4>Hidden Menus</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Menu Name</th>
                                    <th>Description</th>
                                    <th>Original Price</th>
                                    <th>Base Price</th>
                                    <th>Menu Image</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Hidden menus will be appended here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
    fetchFranchises();
    checkSession();

    function fetchFranchises() {
        $.ajax({
            url: 'https://tabsquareinfotech.com/App/Abinesh_be_work/tsit_biriyani_palayam/public/api/get_franchise',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response && response.result) {
                    displayFranchiseMenus(response.result);
                } else {
                    console.error("No franchises found in response.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error occurred while fetching franchises:", error);
                alert("Error occurred while fetching franchises. Please try again.");
            }
        });
    }

   
    // Function to display franchises and handle menu fetching for each
    function displayFranchiseMenus(franchises) {
        var franchiseAccordion = $('#franchiseAccordion');
        franchiseAccordion.empty();

        franchises.forEach(function(franchise) {
            var accordionItem = $('<div>').addClass('card');
            var header = $('<div>').addClass('card-header').attr('id', 'heading_' + franchise.franchise_id);
            var button = $('<button>').addClass('btn btn-link').attr('type', 'button').attr('data-toggle', 'collapse')
                                      .attr('data-target', '#collapse_' + franchise.franchise_id).attr('aria-expanded', 'false')
                                      .attr('aria-controls', 'collapse_' + franchise.franchise_id).html(franchise.franchise);

            var collapse = $('<div>').addClass('collapse').attr('id', 'collapse_' + franchise.franchise_id)
                                     .attr('aria-labelledby', 'heading_' + franchise.franchise_id).attr('data-parent', '#franchiseAccordion');
            var body = $('<div>').addClass('card-body').text('Loading menu details...');

            header.append(button);
            accordionItem.append(header);
            collapse.append(body);
            accordionItem.append(collapse);
            franchiseAccordion.append(accordionItem);

            button.on('click', function() {
                if (!collapse.hasClass('show')) {
                    fetchMenu(franchise.franchise_id, collapse);
                }
            });
        });
    }

    // Function to fetch menu details for a specific franchise
    function fetchMenu(franchise_id, collapseElement) {
        console.log("Fetching menu for franchise ID:", franchise_id);
        $.ajax({
            url: `https://tabsquareinfotech.com/App/Abinesh_be_work/tsit_biriyani_palayam/public/api/get_menu/${franchise_id}`,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response && response.result) {
                    displayMenuDetails(response.result, collapseElement);
                } else {
                    console.error("No menu found for the franchise.");
                    collapseElement.find('.card-body').text("No menu found for the franchise.");
                }
            },
            error: function(xhr, status, error) {
                console.error("Error fetching menu:", error);
                collapseElement.find('.card-body').text("There are no menu items for this franchise.");
            }
        });
    }

    // Function to display the fetched menu details in the collapse element
    function displayMenuDetails(menuDetailsArray, collapseElement) {
        var body = collapseElement.find('.card-body');
        body.empty();
        if (Array.isArray(menuDetailsArray) && menuDetailsArray.length > 0) {
            var tableHtml = '<div class="table-responsive"><table class="table table-bordered table-striped"><thead class="thead-dark">' +
                            '<tr><th>Menu Name</th><th>Description</th><th>Original Price</th><th>Base Price</th><th>Menu Image</th><th>Actions</th></tr></thead><tbody>';

            menuDetailsArray.forEach(function(menuDetails) {
                tableHtml += `<tr>
                                <td>${menuDetails.menu_category_name || 'N/A'}</td>
                                <td>${menuDetails.description || 'N/A'}</td>
                                <td>${menuDetails.base_price || 'N/A'}</td>
                                <td>${menuDetails.current_price || 'N/A'}</td>
                                <td>${menuDetails.menu_image ? '<img src="' + menuDetails.menu_image + '" alt="Menu Image" style="max-width: 100px;">' : 'N/A'}</td>
                                <td><div class="btn-group">
                                    <i class="fas fa-edit edit-menu" style="color:rgb(10,162,18); cursor: pointer; margin-left: 10px;" data-menu-id="${menuDetails.menu_category_id}" data-franchise-id="${menuDetails.franchise_id}"></i>
                                    <i class="fas fa-eye hide-menu" style="color:green; cursor: pointer; margin-left: 10px;" data-menu-id="${menuDetails.menu_category_id}"></i>
                                    <i class="fas fa-trash-alt delete-menu" style="color:rgb(255,0,0); cursor: pointer; margin-left: 10px;" data-menu-id="${menuDetails.menu_category_id}"></i>
                                </div></td>
                              </tr>`;
            });

            tableHtml += '</tbody></table></div>';
            body.html(tableHtml);
        } else {
            body.text("No menu found for the franchise.");
        }
    }

    // Event listeners to hide and show menu items, handling all details
    $(document).on('click', '.hide-menu', function() {
        var row = $(this).closest('tr').clone(); // Clone the row to preserve all data
        var hiddenTableBody = $('.hidden-menus tbody');

        // Replace the hide icon with a show icon
        row.find('.hide-menu').replaceWith('<i class="fas fa-eye-slash show-menu" style="color:green; cursor:pointer;" margin-left: 10px;" data-menu-id="' + $(this).data('menu-id') + '"></i>');
        hiddenTableBody.append(row);
        $(this).closest('tr').remove();
    });

    $(document).on('click', '.show-menu', function() {
        var row = $(this).closest('tr').clone(); // Clone the row to preserve all data
        var originalTableBody = $('.table-responsive:first tbody'); // The original table for visible menus

        // Replace the show icon with a hide icon
        row.find('.show-menu').replaceWith('<i class="fas fa-eye" style="color:green; cursor:pointer;" margin-left: 10px;" data-menu-id="' + $(this).data('menu-id') + '"></i>');
        originalTableBody.append(row);
        $(this).closest('tr').remove();
    });



    $(document).on('click', '.edit-menu', function() {
        var menuId = $(this).data('menu-id');
        var franchiseId = $(this).data('franchise-id');
        editMenu(menuId, franchiseId);
    });

    function editMenu(menuId, franchiseId) {
        console.log('Editing menu with ID:', menu_category_id, 'for franchise:', franchiseId);
        $.ajax({
            url: 'https://tabsquareinfotech.com/App/Abinesh_be_work/tsit_biriyani_palayam/public/api/update_menu/' + menu_category_id + '/' + franchiseId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    console.log("Menu item updated successfully.");
                    alert("Menu item updated successfully.");
                } else {
                    console.error("Error updating menu item:", response.message);
                    alert("Error updating menu item: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", error);
                alert("An error occurred while updating the menu item. Please try again later.");
            }
        });
    }

    $(document).on('click', '.delete-menu', function() {
        var menuId = $(this).data('menu-id');
        deleteMenuItem(menuId);
    });

    function deleteMenuItem(menuId) {
        if (confirm("Are you sure you want to delete this menu item?")) {
            $.ajax({
                url: 'https://tabsquareinfotech.com/App/Abinesh_be_work/tsit_biriyani_palayam/public/api/delete_menu/' + menuId,
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        console.log("Menu item deleted successfully.");
                        alert("Menu item deleted successfully.");
                    } else {
                        console.error("Error:", response.message);
                        alert("Error deleting menu item: " + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    console.error("Error:", error);
                    alert("Error deleting menu item. Please try again.");
                }
            });
        } else {
            console.log('Deletion canceled.');
        }
    }

    $(document).on('click', '.toggle-menu-status', function() {
                const row = $(this).closest('tr');
                const menuId = $(this).data('menu-id');
                const franchiseId = $(this).data('franchise-id');
                const url = `https://tabsquareinfotech.com/App/Abinesh_be_work/tsit_biriyani_palayam/public/api/toggle_menu_status/${menuId}/${franchiseId}`;

                $.post(url, function(response) {
                    if (response.success) {
                        alert(response.message);
                        // Move the row to the appropriate section
                        const targetTable = row.find('.fa-eye-slash').length ? $('.table-responsive:first tbody') : $('.hidden-menus tbody');
                        row.find('i').toggleClass('fa-eye fa-eye-slash');
                        targetTable.append(row);
                    } else {
                        alert(response.message);
                    }
                }).fail(function() {
                    alert("Error toggling menu status. Please try again.");
                });
            });
});

    </script>
</body>

</html>
