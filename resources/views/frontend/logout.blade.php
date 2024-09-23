<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Logout Page</title>
  <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/logout.css') }}">
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- First Page -->
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
        <a class="banner" href="{{ route('banner_page') }}"><b> Add Banner</b></a>
        <hr class="dashed">
        <a class="LogOut" href="{{ route('logout') }}"><b>Log Out</b></a>
        <hr class="dashed">
        </div>
      </div>

      <!-- Logo Page -->
      <div id="two" class="col-md-10">
        <div>
          <img class="logo" src="images/icon.png" alt="Logo">
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript code -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function confirmLogout() {
            var confirmation = confirm("Are you sure you want to log out?");
            if (confirmation) {
                document.getElementById("confirmationInput").value = "Yes";
                document.getElementById("logoutForm").submit();
            }
        }
  </script>
</body>
</html>
