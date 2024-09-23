<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="icon" type="image/png" href="/images/icon.png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('assets/login.css') }}">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center centered-form">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Login</div>
                    <div class="card-body">
                        <form id="loginForm">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="user_name" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Check if we are not on the login page before checking the session
            function checkSession() {
                const sessionToken = sessionStorage.getItem('token');
                
            }

            $('#loginForm').submit(function(e) {
                e.preventDefault(); // Prevent form submission

                var formData = {
                    user_name: $('#username').val(),
                    password: $('#password').val()
                };

                // Send login request to backend
                $.ajax({
                    type: 'POST',
                    url: 'https://tabsquareinfotech.com/App/Abinesh_be_work/tsit_biriyani_palayam/public/api/login',
                    data: formData,
                    success: function(response) {
                        // Handle successful login
                        console.log(response);
                        sessionStorage.setItem("token", response.token);
                        sessionStorage.setItem("admin_type", response.admin_type);  
                    },
                    error: function(xhr, status, error) {
                        // Handle login error
                        console.error(xhr.responseText);
                        alert('An error occurred while processing your request. Please try again later.');
                    }
                });
            });
        });
    </script>
</body>

</html>
