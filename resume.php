<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit;
}

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: index.php");
    exit();
}
include "class/resume.class.php";
if (isset($_POST['export_resume'])) {
    resume::export_resume(trim($_POST['name']), trim($_POST['address']), trim($_POST['phone']), trim($_POST['email']), trim($_POST['school']));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

            .btn-custom:hover {
                background-color: #084298;
                border-color: #084298;
            }

    </style>
    <title>Generate Resume - Proactivity</title>
</head>
<body>
    <div>
        <?php include 'menu.php'; ?>
        <div class="container">
            <div class="form-container">
                <h2 class="form-header">Enter Your Information</h2>
                <div class="text-center"><h7>(We don't save these sensitive information)</h7></div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required />
                        <div class="invalid-feedback"> Please enter your name.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required />
                        <div class="invalid-feedback"> Please enter your address.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phone" name="phone" pattern="[0-9]{10}" required />
                        <div class="invalid-feedback"> Please enter a valid phone number (10 digits).
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required />
                        <div class="invalid-feedback"> Please enter a valid email address.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="school" class="form-label">School</label>
                        <input type="text" class="form-control" id="school" name="school" required />
                        <div class="invalid-feedback"> Please enter your school.
                        </div>
                    </div>
                    <button type="Submit" name="export_resume" class="btn btn-custom w-100">Submit and Generate Resume</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>