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
include "class/uc_award_edit.class.php";
$award_name = task::get_taskname_bytaskid($_GET['task_id']);
$cur_obj = uc_award::get_current_uc_award($_GET['task_id']);
$grade_array = explode(',', $cur_obj["Award_Grade"]);
$recognition_array = explode(',', $cur_obj["Award_Level_Of_recognition"]);


//click save button
if (isset($_POST['save_award'])) {
    uc_award::save_uc_award($_GET['task_id'], trim($_POST['input_program_name']), $_POST['recognition'], $_POST['RadioAwardType'], $_POST['CheckBoxGrade'], trim($_POST['textarea_eligibility']), trim($_POST['textarea_describe']));
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
    <title>UC Application Award Edit - Proactivity</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div style="width: 80%; margin: auto">
            <!--title-->
            <div class="row" style="margin-top: 25px">
                <div class="col-10">
                    <h5 id="item_name_container"><?php echo $award_name; ?></h5>
                </div>
                <div class="col-2" style="text-align: right">
                    <input id="btn_save" name="save_award" type="submit" value="Save" class="btn btn-primary btn-sm" ata-toggle="tooltip" data-placement="top" title="Save Award" />
                </div>
            </div>
            <!--What's the name of the award or honor? *-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>What's the name of the award or honor? *</h7>
                </div>
                <div class="col-8" style="text-align: left">
                    <input type="text" name="input_program_name" class="form-control" aria-describedby="basic-addon1" maxlength="60" value="<?php echo $cur_obj['Award_UC_Name']; ?>" />
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 60)*
                </div>
            </div>
            <!-- Level(s) of recognition-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Level of recognition</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="School" name="recognition[]" <?php echo (in_array("School", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckDefault"> School
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="City/Community" name="recognition[]" <?php echo (in_array("City/Community", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> City/Community
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="Regional" name="recognition[]" <?php echo (in_array("Regional", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> Regional
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="State" name="recognition[]" <?php echo (in_array("State", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> State
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="National" name="recognition[]" <?php echo (in_array("National", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> National
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="International" name="recognition[]" <?php echo (in_array("International", $recognition_array) ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexCheckChecked"> International
                        </label>
                    </div>
                </div>
            </div>
            <!--Type of award or honor *-->
            <div class="row" style="margin-top: 25px">
                <div class="col-2">
                    <h7>Type of award or honor *</h7>
                </div>
                <div class="col-10" style="text-align: left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadioAwardType" value="Academic" <?php echo ($cur_obj['Award_Type'] == "Academic" ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexRadioDefault1"> Academic (for example: Honor societies, academic competitions & programs, grade-based & department awards)
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="RadioAwardType" value="Non-academic" <?php echo ($cur_obj['Award_Type'] == "Non-academic" ? 'checked' : ''); ?> />
                        <label class="form-check-label" for="flexRadioDefault2"> Non-academic (for example: Athletics, leadership, volunteering/community service)
                        </label>
                    </div>
                </div>
            </div>
            <!--When did you receive it? *-->
            <div class="row" style="margin-top: 30px">
                <div class="col-2">
                    <h7>When did you receive it? *</h7>
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
            <!--What are the eligibility requirements for this award or honor? *-->
            <div class="row" style="margin-top: 40px">
                <div class="col-2">
                    What are the eligibility requirements for this award or honor? *<span style="font-size: small"> For example: How are award recipients chosen? How many people are selected to receive the award? Is there an application or nomination for the award?</span>
                </div>
                <div class="col-8" style="text-align: left">
                    <textarea class="form-control" name="textarea_eligibility" rows="6" maxlength="250"><?php echo $cur_obj['Award_Eligibility']; ?></textarea>
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 250)*
                </div>
            </div>
            <!--What did you do to achieve this award or honor? -->
            <div class="row" style="margin-top: 40px">
                <div class="col-2">
                    What did you do to achieve this award or honor? *<span style="font-size: small">We'd like to understand what it took - on your part - to achieve this award. For instance: Were there multiple competitions that you had to participate in? How much time did you dedicate to winning this award?</span>
                </div>
                <div class="col-8" style="text-align: left">
                    <textarea class="form-control" name="textarea_describe" rows="8" maxlength="350"><?php echo $cur_obj['Award_Desc']; ?></textarea>
                </div>
                <div class="col-2" style="text-align: left"> (Max characters: 350)*
                </div>
            </div>
        </div>
        <!--activity reference-->
        <?php include 'reference.php'; ?>
    </form>
</body>
</html>