<?php require("class/activity.class.php") ?>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("location: index.php");
    exit();
}

if (isset($_GET['logout'])) {
    unset($_SESSION['user']);
    header("location: index.php");
    exit();
}

//click delete button
$idx = 0;
$json_data = file_get_contents("json/activities.json");
$stored_activities = json_decode($json_data, true);
$id = $_SESSION['user'];

$tasks = json_decode(file_get_contents("json/activitydetail.json"), true);
foreach ($stored_activities as $user) {
    if ($user["id"] == $id && isset($_POST["remove_" . $user["activity_id"]])) {
        $tidx = 0;
        $tfinal = [];
        foreach ($tasks as $t) {
            if ($t["activity_id"] != $user["activity_id"]) {
                array_push($tfinal, $t);
            }
        }
        unset($stored_activities[$idx]);
        $final = [];
        foreach ($stored_activities as $a) {
            if ($id == $a["id"]) {
                $cnt = 0;
                $p = 0;
                foreach ($final as $u) {
                    if ($id == $u["id"]) {
                        $p++;
                    }
                }
                $new_activity = [
                    "id" => $id,
                    "activity_id" => $a["activity_id"],
                    "position" => $p,
                    "name" => $a["name"],
                    "finished_tasks" => $a["finished_tasks"],
                    "awards" => $a["awards"],
                    "to_do_tasks" => $a["to_do_tasks"],
                    "date created" => $a["date created"]
                ];
                array_push($final, $new_activity);
            } else
                array_push($final, $a);

        }
        file_put_contents("json/activities.json", json_encode($final, JSON_PRETTY_PRINT));
        file_put_contents("json/activitydetail.json", json_encode($tfinal, JSON_PRETTY_PRINT));
        $idx = 0;
        break;
    }
    $idx++;
}


//click add new activity button
if (isset($_POST['add_activity'])) {
    $user = new activity($_SESSION['user'], trim($_POST['activity']));
    header("Refresh:0");
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/activities.js"></script>
    <title>Activity List - Proactivity</title>
</head>
<body>
    <div class="content">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <!--add new activity-->
        <div style="height: 30px"></div>
        <div style="width: 80%; margin: auto">
            <div style="height: 40px">
                <h5>Activity Overview</h5>
            </div>
            <form actions="" method="post" enctype="multipart/form-data" autocomplete="off">
                <div style="margin-top: 30px;" class="row">
                    <div class="col-11" style="text-align: left">
                        <input type="text" id="input_new_activity_name" name="activity" class="form-control" placeholder="Enter a new activity" aria-label="Enter a new finished task, award or task" aria-describedby="basic-addon1" />
                    </div>
                    <div class="col-1" style="text-align: right">
                        <button type="submit" name="add_activity" class="btn btn-primary btn-sm">Add Activity</button>
                    </div>
                </div>
                <div id="add_new_activity_error"><?php echo @$user->error ?></div>
                <div id="add_new_activity_success"><?php echo @$user->success ?></div>

                <div style="margin-top: 60px; height: auto">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" class="col-1">#</th>
                                <th scope="col" class="col-5">Activity Name</th>
                                <th scope="col" class="col-1">Finished Tasks</th>
                                <th scope="col" class="col-1">Awards</th>
                                <th scope="col" class="col-1">To Do Tasks</th>
                                <th scope="col" class="col-1">Created Date</th>
                                <th scope="col" class="col-1">Rename</th>
                                <th scope="col" class="col-1">Remove</th>
                            </tr>
                        </thead>
                        <tbody id="tbbody_Activity_OverView">
                            <?php
                            $json_data = file_get_contents("json/activities.json");
                            $activities = json_decode($json_data, true);
                            $display_id = 1;

                            if (!empty($activities) and count($activities) > 0) {

                                foreach ($activities as $one_activitie) {

                                    //only display current user's activity
                                    if ($one_activitie['id'] == $_SESSION['user']) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $display_id; ?>
                                            </td>
                                            <td id="<?php echo 'td_' . $one_activitie['activity_id']; ?>">
                                                <a href="<?php echo 'activitydetails.php?activity_id=' . $one_activitie['activity_id']; ?>"><?php echo $one_activitie['name']; ?></a>
                                            </td>
                                            <td>
                                                <?php echo activity::get_completed_task_count($one_activitie['activity_id']); ?>
                                            </td>
                                            <td>
                                                <?php echo activity::get_award_count($one_activitie['activity_id']); ?>
                                            </td>
                                            <td>
                                                <?php echo activity::get_todolist_count($one_activitie['activity_id']); ?>
                                            </td>
                                            <td>
                                                <?php echo $one_activitie['date created']; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $bename = $one_activitie['activity_id'];
                                                $beid = $_SESSION['user'];
                                                echo '<input id= "edit_' . $one_activitie['activity_id'] . '" value="Rename" class="btn_edit btn btn-info btn-sm" style="">';
                                                ?>

                                                <script>
                                                    var buttonName = <?php echo $bename ?>;
                                                    var buttonId = <?php echo $beid ?>;
                                                    document.getElementById("edit_" + buttonName).name = "edit_" + buttonName;
                                                </script>
                                            </td>
                                            <td>
                                                <?php
                                                $brname = $one_activitie['activity_id'];
                                                $brid = $_SESSION['user'];
                                                echo '<input type="submit" id= "remove_' . $one_activitie['activity_id'] . '' . $_SESSION['user'] . '" value="Remove" class="mybut btn btn-info btn-sm" style="">';
                                                ?>

                                                <script>
                                                    var buttonName = <?php echo $brname ?>;
                                                    var buttonId = <?php echo $brid ?>;
                                                    document.getElementById("remove_" + buttonName + buttonId).name = "remove_" + buttonName;
                                                </script>
                                            </td>
                                        </tr>
                                        <?php
                                        $display_id++;
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>

        </div>
        <div style="margin-top: 50px"></div>
        <div style="width: 80%; margin: auto">
            <h5>Useful Links</h5>
            <ul class="list-group">
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://post.edu/blog/the-best-extracurricular-activities-to-include-on-your-college-application/">The Best Extracurricular Activities for College Applications</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.princetonreview.com/college-advice/summer-activities-for-college-applications">14 Summer Activities to Boost Your College Application</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://bigfuture.collegeboard.org/plan-for-college/stand-out-in-high-school/extracurriculars-matter-to-you-and-to-colleges">Extracurriculars Matter To You and To Colleges</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.hceducationconsulting.com/2024/03/14/how-extracurricular-activities-can-boost-your-college-application/">How Your Extracurricular Activities Can Boost Your College Application</a></li>
            </ul>
        </div>
    </div>
</body>
</html>