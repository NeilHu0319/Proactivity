<?php require("class/login.class.php") ?>
<?php
if (isset($_POST['submit'])) {
    $user = new LoginUser($_POST['username'], $_POST['password']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <title>Login to Proactivity</title>
    <style>
        body {
            background-color: #f0f2f5;
        }

        .card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            border-radius: 8px;
        }

        .error, .success {
            text-align: center;
            font-weight: bold;
        }

        .error {
            color: #dc3545;
        }

        .success {
            color: #28a745;
        }

        .social-buttons .btn {
            border-radius: 50px;
        }

        .logo {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 100px;
        }

    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card col-md-6 p-4">

            <img src="logo/logo3.png" alt="Proactivity Logo" class="logo" />

            <h2 class="text-center mb-4">Login to Proactivity</h2>

            <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email" required />
                    </div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password" required />
                    </div>
                </div>
                <div class="d-flex justify-content-center mb-3">
                    <button type="submit" name="submit" class="btn btn-primary">Login</button>
                </div>
                <div class="text-center">
                    <p class="error"><?php echo @$user->error ?></p>
                    <p class="success"><?php echo @$user->success ?></p>
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
</body>
</html>
