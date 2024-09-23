<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biryani Palayam </title>
    <link rel="icon" type="image/png" href="/images/icon.png">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/home.css') }}">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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
            <!-- Main content -->
            <div id="main-content" class="col-md-10">
                <div>
                    <img class="logo" src="images/icon.png" alt="Logo">
                </div>
                <div class="p-4">
                    <h2 class="heads text-center">Today's Orders</h2>
                    <div id="franchiseAccordion" class="accordion">
                        <!-- Franchise data will be populated dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            checkSession();

            function fetchAndDisplayFranchises() {
                $.ajax({
                    url: 'https://tabsquareinfotech.com/App/Clients/biriyani_palayam/public/api/get_franchise',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        displayFranchises(response.result);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            // Function to check if the session token is present or if the user is logged in
            function checkSession() {
                // Check if the session token is present in local storage or cookies
                const sessionToken = sessionStorage.getItem('token');
                if (!sessionToken) {
                    // Redirect the user to the login page or show a login prompt
                    window.location.href = "{{ route('logout') }}"; // Replace 'login.php' with your actual login page URL
                }
            }

            // Function to remove the token from session storage
            function logout() {
                sessionStorage.removeItem('token');
            }

            // Add click event listener to logout link
            $('.LogOut').on('click', function (e) {
                e.preventDefault(); // Prevent the default link behavior
                logout(); // Remove token from session storage
                window.location.href = "{{ route('logout') }}"; // Redirect to logout route
            });

            function displayFranchises(franchises) {
                var franchiseAccordion = $('#franchiseAccordion');
                franchiseAccordion.empty(); // Clear previous data

                franchises.forEach(function(franchise) {
                    var collapseId = 'collapse_' + franchise.franchise_id;
                    var cardId = 'card_' + franchise.franchise_id;
                    var card = $('<div>').addClass('card');
                    var cardHeader = $('<div>').addClass('card-header').attr('id', cardId);
                    var button = $('<button>').addClass('btn btn-link').attr({
                        'type': 'button',
                        'data-toggle': 'collapse',
                        'data-target': '#' + collapseId,
                        'aria-expanded': 'false',
                        'aria-controls': collapseId
                    }).text(franchise.franchise);

                    fetchFranchiseTurnover(franchise.franchise_id, button);

                    var collapse = $('<div>').addClass('collapse').attr({
                        'id': collapseId,
                        'aria-labelledby': cardId,
                        'data-parent': '#franchiseAccordion'
                    });
                    var cardBody = $('<div>').addClass('card-body');

                    cardHeader.append(button);
                    card.append(cardHeader);
                    card.append(collapse);
                    collapse.append(cardBody);
                    franchiseAccordion.append(card);

                    collapse.on('show.bs.collapse', function() {
                        fetchFranchiseOrderDetails(franchise.franchise_id, cardBody);
                    });
                });
            }

            function fetchFranchiseTurnover(franchiseId, buttonElement) {
                $.ajax({
                    url: 'https://tabsquareinfotech.com/App/Clients/biriyani_palayam/public/api/get_franchise_turnover/' + franchiseId,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var turnoverDetails = ' | Winners: ' + response.winner_count + ', Total: ' + response.total_transaction_amount;
                            buttonElement.append('<span>' + turnoverDetails + '</span>');
                        } else {
                            buttonElement.append('<span> | No turnover details available</span>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching turnover details: " + error);
                    }
                });
            }

            function fetchFranchiseOrderDetails(franchiseId, cardBody) {
                $.ajax({
                    url: 'https://tabsquareinfotech.com/App/Clients/biriyani_palayam/public/api/get_todays_order/' + franchiseId,
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success && response.results && response.results.length > 0) {
                            displayFranchiseOrderDetails(response.results, cardBody);
                        } else {
                            cardBody.text("No order details available for this franchise.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("Error loading franchise order details: " + error);
                        cardBody.text("THERE IS NO ORDER STILL.");
                    }
                });
            }

            function displayFranchiseOrderDetails(orderDetails, cardBody) {
                cardBody.empty();

                var table = $('<table>').addClass('table');
                var thead = $('<thead>').append(
                    '<tr><th>Order ID</th><th>Total Price</th><th>Date</th><th>Pickup Point</th><th>Pickup Time</th><th>Name</th><th>Transaction ID</th><th>Transaction Status</th><th>Order Status</th></tr>'
                );
                var tbody = $('<tbody>');

                orderDetails.forEach(function(order) {
                    var row = $('<tr>');
                    row.append('<td>' + order.order_id + '</td>');
                    row.append('<td>' + order.total_menu_price + '</td>');
                    row.append('<td>' + order.date + '</td>');
                    row.append('<td>' + order.pickup_point + '</td>');
                    row.append('<td>' + order.pickup_time + '</td>');
                    row.append('<td>' + order.name + '</td>');
                    row.append('<td>' + order.transaction_id + '</td>');
                    row.append('<td>' + addEditIcon(order.transaction_status, 'transaction', order.order_id) + '</td>');
                    row.append('<td>' + addEditIcon(order.order_status, 'order', order.order_id) + '</td>');

                    tbody.append(row);
                });

                table.append(thead, tbody);
                cardBody.append(table);

                cardBody.on('click', '.edit-status', function() {
                    var order_id = $(this).data('order-id');
                    var type = $(this).data('type');
                    var cell = $(this).closest('td');
                    var currentValue = cell.text().trim();

                    var newValue = prompt("Enter the new value:", currentValue);
                    if (newValue === null || newValue.trim() === '') {
                        return;
                    }

                    var url = type === 'transaction' ?
                        'https://tabsquareinfotech.com/App/Clients/biriyani_palayam/public/api/update_payment_status/' + order_id :
                        'https://tabsquareinfotech.com/App/Clients/biriyani_palayam/public/api/update_order_status/' + order_id;

                    $.ajax({
                        url: url,
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            status: newValue
                        },
                        success: function(response) {
                            if (response.success) {
                                cell.text(newValue);
                            } else {
                                alert("Error updating value: " + response.message);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Error updating value: " + error);
                            alert("Error updating value. Please try again.");
                        }
                    });
                })
                .on('mouseenter', '.edit-status', function() {
                    $(this).css('color', 'white').css('cursor', 'pointer');
                })
                .on('mouseleave', '.edit-status', function() {
                    $(this).css('color', '');
                });

                function addEditIcon(value, type, order_id) {
                    var icon = '<i class="fas fa-edit edit-status" data-type="' + type + '" data-order-id="' + order_id + '"></i>';
                    return value + icon;
                }
            }

            fetchAndDisplayFranchises();
        });
    </script>
</body>

</html>
