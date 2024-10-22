<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold text-primary text-uppercase" href="activitylist.php">
            <img src="logo\logo3.png" alt="Proactivity Logo" width="30" height="30" class="me-2" />
            Proactivity
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link text-secondary" href="activitylist.php">Activity List</a></li>
                <li class="nav-item"><a class="nav-link text-secondary" href="to_do_list.php">To Do List</a></li>
                <li class="nav-item"><a class="nav-link text-secondary" href="commonapp.php">Common App</a></li>
                <li class="nav-item"><a class="nav-link text-secondary" href="ucapp.php">UC App</a></li>
                <li class="nav-item"><a class="nav-link text-secondary" href="select_college.php">Essay Brainstormer</a></li>
                <li class="nav-item"><a class="nav-link text-secondary" href="resume.php">Resume</a></li>
            </ul>
            <?php
            $json_data = file_get_contents("json/LOGINdata.json");
            $stored_accounts = json_decode($json_data, true);
            $username = "";
            $aa = $_SESSION['user'];
            foreach ($stored_accounts as $user) {
                if ($user["user id"] == $_SESSION['user']) {
                    $username = $user['username'];
                }
            }
            ?>
            <div class="d-flex align-items-center">
                <span class="navbar-text text-secondary me-3">
                    Welcome, <?php echo $username; ?>
                </span>
                <a class="btn btn-outline-primary btn-sm" href="?logout=true">Logout</a>
            </div>
        </div>
    </div>
</nav>
