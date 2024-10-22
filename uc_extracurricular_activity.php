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
include "class/activity.class.php";
include "class/activitydetail.class.php";
include "class/uc_activity_edit.class.php";
$activity_name = activity::get_activityname_byactivityid($_GET['activity_id']);
$cur_obj = uc_activity::get_current_uc_activity($_GET['activity_id']);
$grade_array = explode(',', $cur_obj["Grade"]);


//click save button
if (isset($_POST['save_activity'])) {
    uc_activity::save_uc_activity($_GET['activity_id'], trim($_POST['input_program_name']), trim($_POST['textarea_activity_describe']), null, $_POST['CheckBoxGrade'], trim($_POST['input_hour_spent']), trim($_POST['input_weeks_spent']), null, null, null, null);
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
    <title>Common App Extracurricular Activity Edit - Proactivity</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div style="width: 80%; margin: auto">
            <!-- title-->
            <div class="row" style="margin-top: 25px">
                <div class="col-10">
                    <h4 id="item_name_container"><?php echo $activity_name; ?></h4>
                </div>
                <div class="col-2" style="text-align: right">
                    <input id="btn_save" name="save_activity" type="submit" value="Save" class="btn btn-primary btn-sm" ata-toggle="tooltip" data-placement="top" title="Save Activity" />
                </div>
            </div>
            <!--Program Name-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Program Name</h7>
                </div>
                <div class="col-8" style="text-align: left">
                    <input type="text" name="input_program_name" class="form-control" aria-describedby="basic-addon1" maxlength="30" value="<?php echo $cur_obj['Organization_Program_Course_Name']; ?>" />
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 30)*
                </div>
            </div>
            <!--Briefly describe the program-->
            <div class="row" style="margin-top: 30px">
                <div class="col-2">
                    Briefly describe the program.<span style="font-size: small">(Think about the program's main focus, your experience, and what you accomplished and learned while participating in the program)</span>
                </div>
                <div class="col-8" style="text-align: left">
                    <textarea class="form-control" name="textarea_activity_describe" rows="5" maxlength="350"><?php echo $cur_obj['Desc']; ?></textarea>
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 350)*
                </div>
            </div>
            <!-- When did you participate in the program? *-->
            <div class="row" style="margin-top: 30px">
                <div class="col-2">
                    <h7>When did you participate in the program  </h7>
                    <span style="font-size: small">(If you participated in the program during the summer, select the grade you were in before that summer)</span>
                </div>
                <div class="col-10" style="text-align: left">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="CheckBoxGrade[]" value="9" <?php echo (in_array("9", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckDefault"> 9th grade
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="10" name="CheckBoxGrade[]" <?php echo (in_array("10", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> 10th grade
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="11" name="CheckBoxGrade[]" <?php echo (in_array("11", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> 11th grade
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="12" name="CheckBoxGrade[]" <?php echo (in_array("12", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> 12th grade
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="After 12th grade" name="CheckBoxGrade[]" <?php echo (in_array("After 12th grade", $grade_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> After 12th grade
                        </label>
                    </div>
                </div>
            </div>
            <!--How much time did you spend in the program?-->
            <div class="row" style="margin-top: 30px">
                <div class="col-2">
                    <h7>How much time did you spend in the program?</h7>
                </div>
                <div class="col-5">
                    Hours spent per week:
                    <input type="text" name="input_hour_spent" class="form-control" aria-describedby="basic-addon1" value="<?php echo $cur_obj['Hours_Spent_Per_Week']; ?>" />
                </div>

                <div class="col-5" style="text-align: left">
                    Weeks spent per year:
                    <input type="text" name="input_weeks_spent" class="form-control" aria-describedby="basic-addon1" value="<?php echo $cur_obj['Weeks_Spent_Per_Year']; ?>" />
                </div>
            </div>
        </div>
        <!--activity reference-->
        <?php include 'reference.php'; ?>
    </form>
</body>
</html>

