<?php require("class/activitydetail.class.php") ?>

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


$display_tasks = task::get_display_task_for_current_activity();
$notes = task::get_notes();

$json_data = file_get_contents("json/activitydetail.json");
$stored_tasks = json_decode($json_data, true);

//click remove task icon
foreach ($stored_tasks as $index => $one_task) {
    if ($one_task["activity_id"] == $_GET['activity_id'] && isset($_POST["remove_" . $one_task["task_id"]])) {
        unset($stored_tasks[$index]);
        usort($stored_tasks, function ($a, $b) {
            return intval($a['task_id']) - intval($b['task_id']);
        });
        file_put_contents("json/activitydetail.json", json_encode($stored_tasks, JSON_PRETTY_PRINT));
        header("Refresh:0");
        exit();
    }
}

//click mark as completed icon
foreach ($stored_tasks as $index => $one_task) {
    if ($one_task["activity_id"] == $_GET['activity_id'] && isset($_POST["markascompleted_" . $one_task["task_id"]])) {
        $stored_tasks[$index]['type'] = 'Completed Task';
        usort($stored_tasks, function ($a, $b) {
            return intval($a['task_id']) - intval($b['task_id']);
        });
        file_put_contents("json/activitydetail.json", json_encode($stored_tasks, JSON_PRETTY_PRINT));
        header("Refresh:0");
        exit();
    }
}

//click add a new activity button
if (isset($_POST['add_task'])) {
    if (!isset($_POST['dropdown']))
        $detail = new task($_GET['activity_id'], trim($_POST["new_generaltask"]), false, $_POST["date"]);
    else
        $detail = new task($_GET['activity_id'], trim($_POST["new_generaltask"]), $_POST["dropdown"], $_POST["date"]);
    header("Refresh:0");
    exit();
}

//save notes
if (isset($_POST['save_notes'])) {
    task::save_notes(trim($_POST['textarea_notes']));
    echo "<script type='text/javascript'>alert('Notes has been saved!');</script>";
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
    <script src="js/activitydetail.js"></script>
    <title>Activity Details - Proactivity</title>

    <title>Log in form</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div style="width: 80%; margin: auto">
            <!--add new task-->
            <div>
                <div style="margin-top: 25px">
                    <?php
                    $json_data = file_get_contents("json/activities.json");
                    $stored_activities = json_decode($json_data, true);
                    $activity = "";
                    foreach ($stored_activities as $user) {
                        if ($user["activity_id"] == $_GET['activity_id']) {
                            $activity = $user['name'];
                        }
                    }
                    ?>
                    <div id="div_activity_name">
                        <h5 style="font-weight: bold;">
                            <?php echo $activity; ?>
                        </h5>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-8">
                        <input type="text" name="new_generaltask" class="form-control" placeholder="Enter a new award, to-do, or completed task" />
                    </div>
                    <div class="col-3 d-flex">
                        <select name="dropdown" class="form-select">
                            <option value="" disabled selected>Select type</option>
                            <option value="Completed Task">Finished Tasks</option>
                            <option value="To-Do Task">To-Do Task</option>
                            <option value="Award">Award</option>
                        </select>
                        <input type="date" name="date" class="form-control ms-2" />
                    </div>
                    <div class="col-1">
                        <button type="submit" name="add_task" class="btn btn-primary btn-sm">Add</button>
                    </div>
                </div>
                <div><?php echo @$detail->error ?></div>
                <div><?php echo @$detail->success ?></div>
            </div>
            <!--task list-->
            <div id="div_all_task">
                <div style="height: auto; margin-top: 30px" id="div_completed_task">
                    <h7 style="font-weight: bold;">Finished Tasks</h7>
                    <div style="margin-top: 30px" id="div_completed_task_container">
                        <!--completed_task-->
                        <?php
                        if (!empty($display_tasks) and count($display_tasks) > 0) {
                            foreach ($display_tasks as $one_task) {
                                if ($one_task['type'] == "Completed Task") {
                                    ?>
                                    <div class="row">
                                        <div class="col-8">
                                            <ul>
                                                <li style="height: 30px" id=<?php echo 'li_' . $one_task['task_id'] ?>><?php echo $one_task['name']; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-2">
                                            Finished at <span><?php echo $one_task['date_chosen']; ?></span>
                                        </div>
                                        <div class="col-2">
                                            <div class="row">
                                                <div class="col-6">
                                                    <?php
                                                    echo '<input type="submit" id="' . $one_task['task_id'] . '" name= "edit_' . $one_task['task_id'] . '" value="Edit" class="btn_edit btn btn-info btn-sm" style="">';
                                                    ?>
                                                </div>
                                                <div class="col-6">
                                                    <?php
                                                    echo '<input type="submit" name= "remove_' . $one_task['task_id'] . '" value="Remove" class="mybut btn btn-info btn-sm" style="">';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <!--award list-->
                <div style="height: auto; margin-top: 30px">
                    <h7 style="font-weight: bold;">Awards</h7>
                    <div style="margin-top: 30px" id="div_award_container">
                        <?php
                        if (!empty($display_tasks) and count($display_tasks) > 0) {
                            foreach ($display_tasks as $one_task) {
                                if ($one_task['type'] == "Award") {
                                    ?>
                                    <div class="row">
                                        <div class="col-8">
                                            <ul>
                                                <li style="height: 30px" id=<?php echo 'li_' . $one_task['task_id'] ?>><?php echo $one_task['name']; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-2">
                                            Received at <span><?php echo $one_task['date_chosen']; ?></span>
                                        </div>
                                        <div class="col-2">
                                            <div class="row">
                                                <div class="col-6">
                                                    <?php
                                                    echo '<input type="submit" id="' . $one_task['task_id'] . '" name= "edit_' . $one_task['task_id'] . '" value="Edit" class="btn_edit btn btn-info btn-sm" style="">';
                                                    ?>
                                                </div>
                                                <div class="col-6">
                                                    <?php
                                                    echo '<input type="submit" name= "remove_' . $one_task['task_id'] . '" value="Remove" class="mybut btn btn-info btn-sm" style="">';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
                <!--to do list-->
                <div style="height: auto; margin-top: 30px">
                    <h7 style="font-weight: bold;">To-Do Tasks</h7>
                    <div style="margin-top: 30px" id="div_todotask_container">
                        <?php
                        if (!empty($display_tasks) and count($display_tasks) > 0) {
                            foreach ($display_tasks as $one_task) {
                                if ($one_task['type'] == "To-Do Task") {
                                    ?>
                                    <div class="row">
                                        <div class="col-8">
                                            <ul>
                                                <li style="height: 30px" id=<?php echo 'li_' . $one_task['task_id'] ?>><?php echo $one_task['name']; ?></li>
                                            </ul>
                                        </div>
                                        <div class="col-2">
                                            <?php
                                            $date_chosen = new DateTime($one_task['date_chosen']);
                                            $today = new DateTime();
                                            if ($date_chosen < $today) {
                                                echo '<span style="color: red;">Due at ' . $one_task['date_chosen'] . ' (Overdue)</span>';
                                            } else {
                                                echo 'Due at ' . $one_task['date_chosen'];
                                            }
                                            ?>
                                        </div>
                                        <div class="col-2">
                                            <div class="row">
                                                <div class="col-4">
                                                    <?php
                                                    echo '<input type="submit" id="' . $one_task['task_id'] . '" name= "edit_' . $one_task['task_id'] . '" value="Edit" class="btn_edit btn btn-info btn-sm" style="">';
                                                    ?>
                                                </div>
                                                <div class="col-4">
                                                    <?php
                                                    echo '<input type="submit" name= "remove_' . $one_task['task_id'] . '" value="Remove" class="mybut btn btn-info btn-sm" style="">';
                                                    ?>
                                                </div>
                                                <div class="col-4">
                                                    <?php
                                                    echo '<input type="submit" name= "markascompleted_' . $one_task['task_id'] . '" value="Mark As Completed" class="mybut btn btn-info btn-sm" style="">';
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <!--Notes-->
            <div style="height: auto; margin-top: 30px">
                <h7 style="font-weight: bold;">Notes</h7>
                <div style="margin-top: 30px" class="form-group">
                    <textarea class="form-control" name="textarea_notes" rows="3" runat="server"><?php echo $notes; ?></textarea>
                </div>
            </div>
            <div style="text-align: right; margin-top: 15px">
                <input name="save_notes" type="submit" value="Save Notes" class="btn btn-primary btn-sm" ata-toggle="tooltip" data-placement="top" title="Save Notes" />
            </div>
        </div>
    </form>
</body>
</html>