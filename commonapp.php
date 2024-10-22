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

if (isset($_POST['submit'])) {
    $user = new LoginUser($_POST['username'], $_POST['password']);
}

$json_data = file_get_contents("json/common_app_activity.json");
$a = json_decode($json_data, true);

if (count($a) != 0) {
    $indexes = [];
    foreach ($a as $c) {
        if ($c["Id"] == $_SESSION['user']) {
            array_push($indexes, $c["Activity_Order"]);
        }
    }
    $f = [];
    //click activity order up button
    foreach ($a as $c) {
        if (isset($_POST["up_activity_" . $c["Activity_Order"]])) {
            $idx = array_search($c["Activity_Order"], $indexes);

            if ($idx > 0) {

                $to = $idx - 1;
                $vidx = [];
                $vto = [];
                $oidx = 0;
                $oto = 0;
                foreach ($a as $cur) {
                    if ($cur["Activity_Order"] == $indexes[$idx]) {
                        $oidx = $cur["Activity_Order"];
                        $vidx = $cur;
                    } else if ($cur["Activity_Order"] == $indexes[$to]) {
                        $oto = $cur["Activity_Order"];
                        $vto = $cur;
                    }
                }

                if (count($vto) == 0 || count($vidx) == 0)
                    continue;
                foreach ($a as $cur) {
                    if ($cur["Activity_Order"] == $indexes[$idx]) {
                        $vto["Activity_Order"] = $oidx;
                        array_push($f, $vto);
                    } else if ($cur["Activity_Order"] == $indexes[$to]) {
                        $vidx["Activity_Order"] = $oto;
                        array_push($f, $vidx);
                    } else {
                        array_push($f, $cur);
                    }
                }

                file_put_contents("json/test.json", json_encode($f, JSON_PRETTY_PRINT));
                file_put_contents("json/common_app_activity.json", json_encode($f, JSON_PRETTY_PRINT));
                $json_data = file_get_contents("json/common_app_activity.json");
                $b = json_decode($json_data, true);
                //foreach ($b as $cur) {
                //    echo $cur["Name"] . " ";
                //}
                break;
            }
        }
        //click activity order up button
        if (isset($_POST["down_activity_" . $c["Activity_Order"]])) {
            $idx = array_search($c["Activity_Order"], $indexes);

            if ($idx < count($indexes) - 1) {

                $to = $idx + 1;
                $vidx = [];
                $vto = [];
                $oidx = 0;
                $oto = 0;
                foreach ($a as $cur) {
                    if ($cur["Activity_Order"] == $indexes[$idx]) {
                        $oidx = $cur["Activity_Order"];
                        $vidx = $cur;
                    } else if ($cur["Activity_Order"] == $indexes[$to]) {
                        $oto = $cur["Activity_Order"];
                        $vto = $cur;
                    }
                }

                if (count($vto) == 0 || count($vidx) == 0)
                    continue;
                foreach ($a as $cur) {
                    if ($cur["Activity_Order"] == $indexes[$idx]) {
                        $vto["Activity_Order"] = $oidx;
                        array_push($f, $vto);
                    } else if ($cur["Activity_Order"] == $indexes[$to]) {
                        $vidx["Activity_Order"] = $oto;
                        array_push($f, $vidx);
                    } else {
                        array_push($f, $cur);
                    }
                }

                file_put_contents("json/test.json", json_encode($f, JSON_PRETTY_PRINT));
                file_put_contents("json/common_app_activity.json", json_encode($f, JSON_PRETTY_PRINT));
                $json_data = file_get_contents("json/common_app_activity.json");
                $b = json_decode($json_data, true);
                //foreach ($b as $cur) {
                //    echo $cur["Name"] . " ";
                //}
                break;
            }
        }
    }

}
?>

<?php
include "class/common_app_list.class.php";
common_app_list::SynCommonAppAward();
$award_display_list = common_app_list::GetAwardList();

if (isset($_POST['export_pdf'])) {
    common_app_list::export_pdf();
}


//click award moving up button
foreach ($award_display_list as $one_award) {
    $name = "up_award" . $one_award['Task_Id'] . '_' . $one_award['Item_Order'];
    if (isset($_POST["up_award" . $one_award['Task_Id'] . '_' . $one_award['Item_Order']]) && $one_award['Task_Id'] != $award_display_list[0]["Task_Id"]) {
        common_app_list::AwardMovingUp($one_award['Item_Order']);
        header("Refresh:0");
    }
}

//click award moving down button
foreach ($award_display_list as $one_award) {
    $name = "down_award" . $one_award['Task_Id'] . '_' . $one_award['Item_Order'];
    if (isset($_POST["down_award" . $one_award['Task_Id'] . '_' . $one_award['Item_Order']]) && $one_award['Task_Id'] != $award_display_list[count($award_display_list) - 1]["Task_Id"]) {
        common_app_list::AwardMovingDown($one_award['Item_Order']);
        header("Refresh:0");
    }
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
    <title>Common App Awards and Activities List - Proactivity</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <!--common app award and activity list-->
        <div style="height: auto; width: 80%; margin: auto">
            <div style="margin-top: 30px; text-align: left">
                <input name="export_pdf" type="submit" value="Generate Common App Report" class="btn btn-primary btn-sm" />
            </div>
            <!--Award List-->
            <h5 style="height: 30px; margin-top: 30px">Award List - <span style="font-size: small">Please ensure all required information is filled out. The Common App allows up to 5 awards. Please arrange the awards in order of significance.</span></h5>
            <div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="col-1">#</th>
                            <th scope="col" class="col-7">Award Name</th>
                            <th scope="col" class="col-2">Completed</th>
                            <th scope="col" class="col-1"></th>
                            <th scope="col" class="col-1"></th>
                        </tr>
                    </thead>
                    <tbody id="tbbody_Awardlist">
                        <?php
                        $display_id = 1;
                        if (!empty($award_display_list) and count($award_display_list) > 0) {
                            foreach ($award_display_list as $one_award) {
                                ?>
                                <tr>
                                    <th scope="row"><?php echo $display_id; ?></th>
                                    <td><?php echo '<a href="common_app_award_edit.php?task_id=' . $one_award['Task_Id'] . '">' . $one_award['Item_Title'] . '</a>'; ?></td>
                                    <td>
                                        <?php
                                        if ($one_award['Item_Completed'] == "Yes") {
                                            echo 'Yes';
                                        } else {
                                            echo '<span style="color: red;">No</span>';
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<input type="submit" name= "up_award' . $one_award['Task_Id'] . '_' . $one_award['Item_Order'] . '" value="Up" class="mybut btn btn-info btn-sm" style="">';
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        echo '<input type="submit" name= "down_award' . $one_award['Task_Id'] . '_' . $one_award['Item_Order'] . '" value="Down" class="mybut btn btn-info btn-sm" style="">';
                                        ?>
                                    </td>
                                </tr>
                                <?php
                                $display_id++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!--activity list-->
            <h5 style="height: 30px; margin-top: 50px">Activity List - <span style="font-size: small">Please ensure all required information is filled out. The Common App allows up to 10 activities. Please arrange the activities in order of significance.</span></h5>
            <div>
                <table class="table table-hover" id="activity_table">
                    <thead>
                        <tr>
                            <!--columns-->
                            <th scope="col" class="col-1">#</th>
                            <th scope="col" class="col-7">Activity Name</th>
                            <th scope="col" class="col-2">Completed</th>
                            <th scope="col" class="col-1"></th>
                            <th scope="col" class="col-1"></th>
                        </tr>
                    </thead>

                    <?php
                    $json_data = file_get_contents("json/activities.json");
                    $activities = json_decode($json_data, true);

                    $json_data = file_get_contents("json/common_app_activity.json");
                    $CAactivities = json_decode($json_data, true);

                    $f = [];
                    if (count($CAactivities) != 0) {
                        foreach ($CAactivities as $ca) {
                            foreach ($activities as $a) {
                                if ($ca["Activity_Id"] == $a["activity_id"]) {
                                    array_push($f, $ca);
                                    break;
                                }
                            }

                        }
                    }
                    $max = -1;
                    if (count($activities) != 0) {
                        foreach ($activities as $a) {
                            $ok = 0;
                            if (count($f) != 0) {
                                foreach ($f as $cur) {
                                    $max = max($max, $cur["Activity_Order"]);
                                }
                            }
                            if (count($CAactivities) != 0) {
                                foreach ($CAactivities as $CAa) {
                                    if ($CAa["Activity_Id"] == $a["activity_id"]) { //old
                                        // echo $CAa["Name"] . ' ';
                                        // array_push($f,$CAa);
                                        $ok = 1;
                                        break;
                                    }
                                }
                            }

                            if (!$ok) {
                                $NEW = [
                                    "Activity_Id" => $a["activity_id"],
                                    "Id" => $a["id"],
                                    "Name" => $a["name"],
                                    "Activity_Type" => null,
                                    "Position" => null,
                                    "Organization_Name" => null,
                                    "Desc" => null,
                                    "Participant_Grade" => null,
                                    "Timing_Of_Participant" => null,
                                    "Hours_Spent_Per_Year" => null,
                                    "Weeks_Spent_Per_Year" => null,
                                    "Intend_Participant_In_College" => null,
                                    "Activity_Order" => $max + 1
                                ];
                                array_push($f, $NEW);
                            }

                        }
                    }


                    file_put_contents("json/common_app_activity.json", json_encode($f, JSON_PRETTY_PRINT));
                    $json_data = file_get_contents("json/common_app_activity.json");
                    $activities = json_decode($json_data, true);
                    usort($activities, function ($a, $b) {
                        return intval($a['Activity_Order']) - intval($b['Activity_Order']);
                    });

                    if (count($activities) != 0) {
                        $idx = 1;
                        foreach ($activities as $activities) {

                            if ($activities['Id'] == $_SESSION['user']) {
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $idx; ?>
                                    </td>
                                    <td><?php echo '<a href="common_app_activity_edit.php?activity_id=' . $activities['Activity_Id'] . '">' . $activities['Name'] . '</a>'; ?></td>

                                    <td>
                                        <?php if ($activities["Activity_Type"] != null && $activities["Organization_Name"] != null && $activities["Desc"] != null && $activities["Participant_Grade"] != null && $activities["Timing_Of_Participant"] != null && $activities["Hours_Spent_Per_Year"] != null && $activities["Weeks_Spent_Per_Year"] != null && $activities["Intend_Participant_In_College"] != null)
                                            echo "Yes";
                                        else
                                            echo '<span style="color: red;">No</span>'; ?>
                                    </td>
                                    <td>
                                        <input type="submit" name="<?php echo 'up_activity_' . $activities['Activity_Order'] ?>" value="Up" class="mybut btn btn-info btn-sm" style="" />
                                    </td>
                                    <td>
                                        <input type="submit" name="<?php echo 'down_activity_' . $activities['Activity_Order'] ?>" value="Down" class="mybut btn btn-info btn-sm" style="" />
                                    </td>
                                </tr>

                                <?php
                                $idx++;
                            }

                        }
                    }
                    ?>
                </table>
            </div>
        </div>
        <div style="margin-top: 50px"></div>
        <div style="width: 80%; margin: auto">
            <h5>Useful Links</h5>
            <ul class="list-group">
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://scholarships360.org/college-admissions/common-app-honors-section/">How to Complete the Common App Honors Section</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.myprompt.com/post/short-and-sweet-listing-your-awards">Short and Sweet: Listing your Awards on the Common App</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.collegeessayguy.com/blog/guide-college-activities-list-common-app-example-application">How to write a successful common app activities list</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.commandeducation.com/resource/common-app-activities-list/">Common App 101: Activities List & Honors | Comprehensive Guide</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.shemmassianconsulting.com/blog/common-app-activities-section">How to Stand Out on the Common App Activities Section (Example Included)</a></li>
            </ul>
        </div>
    </form>

</body>
</html>