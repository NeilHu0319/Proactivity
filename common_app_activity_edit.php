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


<?php
include "class/activitydetail.class.php";
//save common app activity
$json_data = file_get_contents("json/common_app_activity.json");
$c = json_decode($json_data, true);
$id = $_GET['activity_id'];
if (isset($_POST['save_activity'])) {
    if (isset($_POST['dropdown'])) {
        foreach ($c as $idx => $cc) {
            if ($cc["Activity_Id"] == $id)
                $c[$idx]["Activity_Type"] = $_POST['dropdown'];
        }
    }
    if (isset($_POST['pld'])) {
        foreach ($c as $idx => $cc) {
            if ($cc["Activity_Id"] == $id)
                $c[$idx]["Position"] = (trim($_POST['pld']) != "") ? trim($_POST['pld']) : null;
        }
    }
    if (isset($_POST['on'])) {
        foreach ($c as $idx => $cc) {
            if ($cc["Activity_Id"] == $id)
                $c[$idx]["Organization_Name"] = (trim($_POST['on']) != "") ? trim($_POST['on']) : null;
        }
    }
    if (isset($_POST['ad'])) {
        foreach ($c as $idx => $cc) {
            if ($cc["Activity_Id"] == $id)
                $c[$idx]["Desc"] = (trim($_POST['ad']) != "") ? trim($_POST['ad']) : null;
        }
    }
    if (isset($_POST['CheckBoxGrade'])) {
        //echo "RAN";
        $grade = implode(',', $_POST['CheckBoxGrade']);
        //echo $grade;
        foreach ($c as $idx => $cc) {
            if ($cc["Activity_Id"] == $id)
                $c[$idx]["Participant_Grade"] = (count($_POST['CheckBoxGrade']) != 0) ? $grade : null;
        }
    }
    if (isset($_POST['CheckBoxTime'])) {
        //echo "RAN";
        $time = implode(',', $_POST['CheckBoxTime']);
        //echo $time;
        foreach ($c as $idx => $cc) {
            if ($cc["Activity_Id"] == $id)
                $c[$idx]["Timing_Of_Participant"] = (count($_POST['CheckBoxTime']) != 0) ? $time : null;
        }
    }
    if (isset($_POST['hpw'])) {
        foreach ($c as $idx => $cc) {
            if ($cc["Activity_Id"] == $id)
                $c[$idx]["Hours_Spent_Per_Year"] = (trim($_POST['hpw']) != "") ? trim($_POST['hpw']) : null;
        }
    }

    if (isset($_POST['wpy'])) {
        foreach ($c as $idx => $cc) {
            if ($cc["Activity_Id"] == $id)
                $c[$idx]["Weeks_Spent_Per_Year"] = (trim($_POST['wpy']) != "") ? trim($_POST['wpy']) : null;
        }
    }

    if (isset($_POST['y/n'])) {
        foreach ($c as $idx => $cc) {

            if ($cc["Activity_Id"] == $id) {
                //print_r($_POST);
                if ($_POST['y/n'] == "")
                    $c[$idx]["Intend_Participant_In_College"] = null;
                else
                    $c[$idx]["Intend_Participant_In_College"] = ($_POST['y/n'] == "Yes") ? "Yes" : "No";
            }
        }
    }
    //print_r($_POST);
    $f = json_encode($c, JSON_PRETTY_PRINT);
    file_put_contents('json/common_app_activity.json', $f, );
    echo '<script>alert("Information has been saved")</script>';
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
    <title>Common App Activities Edit - Proactivity</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div style="width: 80%; margin: auto">
            <?php
            $x = "";
            $idx = 0;
            $json_data = file_get_contents("json/common_app_activity.json");
            $activities = json_decode($json_data, true);
            foreach ($activities as $i => $a) {
                if ($a["Activity_Id"] == $_GET['activity_id']) {
                    $x = $a["Name"];
                    $idx = $i;
                }
            }
            if ($activities[$idx]["Participant_Grade"] != null)
                $grade_array = explode(',', $activities[$idx]["Participant_Grade"]);
            else
                $grade_array = [];

            if ($activities[$idx]["Timing_Of_Participant"] != null)
                $time_array = explode(',', $activities[$idx]["Timing_Of_Participant"]);
            else
                $time_array = [];
            ?>
            <div class="row" style="margin-top: 25px">
                <div class="col-10">
                    <h4 id="item_name_container"><?php echo $x; ?></h4>
                </div>
                <div class="col-2" style="text-align: right">
                    <input name="save_activity" type="submit" value="Save" class="btn btn-primary btn-sm" ata-toggle="tooltip" data-placement="top" title="Save Activity" />
                </div>
            </div>
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Activity Type</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <div class="dropdown">
                        <select name="dropdown">
                            <option value="Academic"
                                <?php if ($activities[$idx]["Activity_Type"] == "Academic")
                                    echo 'selected="selected"'; ?>>Academic</option>
                            <option value="Art"
                                <?php if ($activities[$idx]["Activity_Type"] == "Art")
                                    echo 'selected="selected"'; ?>>Art</option>
                            <option value="Athletics:Club"
                                <?php if ($activities[$idx]["Activity_Type"] == "Athletics:Club")
                                    echo 'selected="selected"'; ?>>Athletics:Club</option>
                            <option value="Athletics:JV/Varsity"
                                <?php if ($activities[$idx]["Activity_Type"] == "Athletics:JV/Varsity")
                                    echo 'selected="selected"'; ?>>Athletics:JV/Varsity</option>
                            <option value="Career Oriented"
                                <?php if ($activities[$idx]["Activity_Type"] == "Career Oriented")
                                    echo 'selected="selected"'; ?>>Career Oriented</option>
                            <option value="Community Service(Volunteer)"
                                <?php if ($activities[$idx]["Activity_Type"] == "Community Service(Volunteer)")
                                    echo 'selected="selected"'; ?>>Community Service(Volunteer)</option>
                            <option value="Computer/Technology"
                                <?php if ($activities[$idx]["Activity_Type"] == "Computer/Technology")
                                    echo 'selected="selected"'; ?>>Computer/Technology</option>
                            <option value="Cultural"
                                <?php if ($activities[$idx]["Activity_Type"] == "Cultural")
                                    echo 'selected="selected"'; ?>>Cultural</option>
                            <option value="Dance"
                                <?php if ($activities[$idx]["Activity_Type"] == "Dance")
                                    echo 'selected="selected"'; ?>>Dance</option>
                            <option value="Debate/Speech"
                                <?php if ($activities[$idx]["Activity_Type"] == "Debate/Speech")
                                    echo 'selected="selected"'; ?>>Debate/Speech</option>
                            <option value="Environmental"
                                <?php if ($activities[$idx]["Activity_Type"] == "Environmental")
                                    echo 'selected="selected"'; ?>>Environmental</option>
                            <option value="Family responsibility"
                                <?php if ($activities[$idx]["Activity_Type"] == "Family responsibility")
                                    echo 'selected="selected"'; ?>>Family responsibility</option>
                            <option value="Foreign exchange"
                                <?php if ($activities[$idx]["Activity_Type"] == "Foreign exchange")
                                    echo 'selected="selected"'; ?>>Foreign exchange</option>
                            <option value="Internship"
                                <?php if ($activities[$idx]["Activity_Type"] == "Internship")
                                    echo 'selected="selected"'; ?>>Internship</option>
                            <option value="Journalism/Publication"
                                <?php if ($activities[$idx]["Activity_Type"] == "Journalism/Publication")
                                    echo 'selected="selected"'; ?>>Journalism/Publication</option>
                            <option value="Junior R.O.T.C"
                                <?php if ($activities[$idx]["Activity_Type"] == "Junior R.O.T.C")
                                    echo 'selected="selected"'; ?>>Junior R.O.T.C</option>
                            <option value="LGBT"
                                <?php if ($activities[$idx]["Activity_Type"] == "LGBT")
                                    echo 'selected="selected"'; ?>>LGBT</option>
                            <option value="Music: Instrumental"
                                <?php if ($activities[$idx]["Activity_Type"] == "Music: Instrumental")
                                    echo 'selected="selected"'; ?>>Music: Instrumental</option>
                            <option value="Music:Vocal"
                                <?php if ($activities[$idx]["Activity_Type"] == "Music:Vocal")
                                    echo 'selected="selected"'; ?>>Music:Vocal</option>
                            <option value="Religious"
                                <?php if ($activities[$idx]["Activity_Type"] == "Religious")
                                    echo 'selected="selected"'; ?>>Religious</option>
                            <option value="Research"
                                <?php if ($activities[$idx]["Activity_Type"] == "Research")
                                    echo 'selected="selected"'; ?>>Research</option>
                            <option value="Robotics"
                                <?php if ($activities[$idx]["Activity_Type"] == "Robotics")
                                    echo 'selected="selected"'; ?>>Robotics</option>
                            <option value="School Spirit"
                                <?php if ($activities[$idx]["Activity_Type"] == "School Spirit")
                                    echo 'selected="selected"'; ?>>School Spirit</option>
                            <option value="Science/Math"
                                <?php if ($activities[$idx]["Activity_Type"] == "Science/Math")
                                    echo 'selected="selected"'; ?>>Science/Math</option>
                            <option value="Social Justice"
                                <?php if ($activities[$idx]["Activity_Type"] == "Social Justice")
                                    echo 'selected="selected"'; ?>>Social Justice</option>
                            <option value="Student Govt./Polities"
                                <?php if ($activities[$idx]["Activity_Type"] == "Student Govt./Polities")
                                    echo 'selected="selected"'; ?>>Student Govt./Polities</option>
                            <option value="Theater/Drama"
                                <?php if ($activities[$idx]["Activity_Type"] == "Theater/Drama")
                                    echo 'selected="selected"'; ?>>Theater/Drama</option>
                            <option value="Work(Paid)"
                                <?php if ($activities[$idx]["Activity_Type"] == "Work(Paid)")
                                    echo 'selected="selected"'; ?>>Work(Paid)</option>
                            <option value="Other Club/Activity"
                                <?php if ($activities[$idx]["Activity_Type"] == "Other Club/Activity")
                                    echo 'selected="selected"'; ?>>Other Club/Activity</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Position/Leadership description</h7>
                </div>
                <div class="col-8" style="text-align: left">
                    <input type="text" name="pld" class="form-control" aria-describedby="basic-addon1" maxlength="50" value="<?php echo $activities[$idx]["Position"]; ?>" />
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 50)*
                </div>
            </div>
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Organization Name</h7>
                </div>
                <div class="col-8" style="text-align: left">
                    <input type="text" name="on" class="form-control" aria-describedby="basic-addon1" maxlength="100" value="<?php echo $activities[$idx]["Organization_Name"]; ?>" />
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 100)*
                </div>
            </div>
            <!--describe this activity-->
            <div class="row" style="margin-top: 30px">
                <div class="col-2"> Please describe this activity, including what you accomplished and any recognition you received, etc.
                </div>
                <div class="col-8" style="text-align: left">
                    <textarea class="form-control" name="ad" rows="3" maxlength="150"><?php echo $activities[$idx]["Desc"] ?></textarea>
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 150)*
                </div>
            </div>
            <!--Participation grade levels checkbox-->
            <div class="row" style="margin-top: 30px">
                <div class="col-2">
                    <h7>Participation grade levels</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="CheckBoxGrade[]" value="9" <?php echo (in_array("9", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckDefault"> 9
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="10" name="CheckBoxGrade[]" <?php echo (in_array("10", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> 10
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="11" name="CheckBoxGrade[]" <?php echo (in_array("11", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> 11
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="12" name="CheckBoxGrade[]" <?php echo (in_array("12", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> 12
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Post-graduate" name="CheckBoxGrade[]" <?php echo (in_array("Post-graduate", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> Post-graduate
                        </label>
                    </div>
                </div>
            </div>
            <!--Timing of participation* checkbox-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Timing of participation</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="During school year" name="CheckBoxTime[]" <?php echo (in_array("During school year", $time_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckDefault"> During school year
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="During school break" name="CheckBoxTime[]" <?php echo (in_array("During school break", $time_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> During school break
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="All year" name="CheckBoxTime[]" <?php echo (in_array("All year", $time_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> All year
                        </label>
                    </div>
                </div>
            </div>
            <!--<Hours spent per week-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Hours spent per week</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <input type="text" name="hpw" class="form-control" aria-describedby="basic-addon1" value="<?php echo $activities[$idx]["Hours_Spent_Per_Year"]; ?>" />
                </div>
            </div>
            <!--Weeks spent per year-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Weeks spent per year</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <input type="text" name="wpy" class="form-control" aria-describedby="basic-addon1" value="<?php echo $activities[$idx]["Weeks_Spent_Per_Year"]; ?>" />
                </div>
            </div>
            <!--I intend to participate in a similar activity in college-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>I intend to participate in a similar activity in college</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="y/n" value="Yes" <?php echo ($activities[$idx]["Intend_Participant_In_College"] == "Yes" ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexRadioDefault1"> Yes
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="y/n" value="No" <?php echo ($activities[$idx]["Intend_Participant_In_College"] == "No" ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexRadioDefault2"> No
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <!--activity reference-->
        <?php include 'reference.php'; ?>
    </form>
</body>
</html>
