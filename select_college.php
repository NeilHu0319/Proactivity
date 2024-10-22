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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/badges/" />
    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/components/dropdowns/" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="js\select_college.js"></script>
</head>
<body>
    <?php include 'menu.php'; ?>
    <div style="height: 30px"></div>
    <div style="height: 40px; width: 80%; margin: auto">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-5">
                    <h4>Available Universities (Beta)</h4>
                    <ul id="availableList" class="list-group">
                        <?php
                        $universities = ["University of California", "Massachusetts Institute of Technology", "Stanford University", "California Institute of Technology", "University of Southern California"];
                        $data = json_decode(file_get_contents('json/selected_college.json'), true);
                        $selectedUniversities = $data['selected'] ?? [];

                        foreach ($universities as $university) {
                            if (!in_array($university, $selectedUniversities)) {
                                echo "<li class='list-group-item'>$university</li>";
                            }
                        }
                        ?>
                    </ul>
                    <div style="margin-top: 20px;">We will keep adding universities to this list.</div>
                </div>
                <div class="col-md-2 d-flex align-items-center justify-content-center">
                    <div>
                        <button id="moveToSelected" class="btn btn-primary mb-3">Select</button>
                        <!--<button id="moveToAvailable" class="btn btn-danger">Remove</button>-->
                    </div>
                </div>
                <div class="col-md-5">
                    <h4>Selected Universities</h4>
                    <ul id="selectedList" class="list-group">
                        <?php
                        foreach ($selectedUniversities as $university) {
                            //echo "<li class='list-group-item'><a href='essay_brainstorming.php' class='university-link'>$university</a><button class='btn btn-sm btn-primary remove-btn'>Remove</button></li>";
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center'><a href='essay_brainstorming.php' class='university-link'>$university</a><button class='btn btn-sm btn-primary remove-btn ms-auto'>Remove</button></li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>