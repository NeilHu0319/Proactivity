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
include "class/common_app_award_edit.class.php";
$award_name = task::get_taskname_bytaskid($_GET['task_id']);
$cur_obj = common_app_award::get_current_commonapp_award($_GET['task_id']);
$grade_array = explode(',', $cur_obj["Award_Grade"]);
$recognition_array = explode(',', $cur_obj["Award_Level_Of_recognition"]);

//click save button
if (isset($_POST['save_award'])) {
    common_app_award::save_common_app_award($_GET['task_id'], trim($_POST['task_title_container']), $_POST['CheckBoxGrade'], $_POST['CheckBoxGradeRecognition']);
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
    <title>Common App Awards Edit - Proactivity</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div style="width: 80%; margin: auto">
            <div class="row" style="margin-top: 25px">
                <div class="col-10">
                    <h5 id="task_name_container"><?php echo $award_name; ?></h5>
                </div>
                <div class="col-2" style="text-align: right">
                    <input name="save_award" type="submit" value="Save" class="btn btn-primary btn-sm" ata-toggle="tooltip" data-placement="top" title="Save Activity" />
                </div>
            </div>
            <!-- Honors 3 title-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Honors  title and description</h7>
                </div>
                <div class="col-8" style="text-align: left">
                    <input type="text" name="task_title_container" class="form-control" aria-describedby="basic-addon1" maxlength="100" value="<?php echo $cur_obj['Award_Desc']; ?>" />
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 100)*
                </div>
            </div>
            <!--Grade level-->
            <div class="row" style="margin-top: 30px">
                <div class="col-2">
                    <h7>Grade level</h7>
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
            <!-- Level(s) of recognition-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Level(s) of recognition</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="School" name="CheckBoxGradeRecognition[]" <?php echo (in_array("School", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckDefault"> School
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="State/Regional" name="CheckBoxGradeRecognition[]" <?php echo (in_array("State/Regional", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> State/Regional
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="National" name="CheckBoxGradeRecognition[]" <?php echo (in_array("National", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> National
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="International" name="CheckBoxGradeRecognition[]" <?php echo (in_array("International", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> International
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

