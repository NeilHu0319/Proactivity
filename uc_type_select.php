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

include "class/activity.class.php";
$activityname = activity::get_activityname_byactivityid($_GET['activity_id']);
$jsonString = file_get_contents('json/uc_activity.json');
$data = json_decode($jsonString, true);
$current_index = 0;
$current_type = null;
foreach ($data as $index => $one_activity) {
    if ($one_activity['Activity_Id'] == $_GET['activity_id']) {
        $current_type = $data[$index]['UC_Type'];
        $current_index = $index;
    }
}
//click save button
if (isset($_POST['btn_save'])) {
    $data[$current_index]['UC_Type'] = $_POST['dropdown'];
    file_put_contents("json/uc_activity.json", json_encode($data, JSON_PRETTY_PRINT));
    if ($_POST['dropdown'] == "Educational Program") {
        header("Location: uc_educational_program.php?activity_id=" . $_GET["activity_id"]);
    } elseif ($_POST['dropdown'] == "Extracurricular Activity") {
        header("Location: uc_extracurricular_activity.php?activity_id=" . $_GET["activity_id"]);
    } elseif ($_POST['dropdown'] == "Other Coursework") {
        header("Location: uc_other_coursework.php?activity_id=" . $_GET["activity_id"]);
    } elseif ($_POST['dropdown'] == "Volunteer") {
        header("Location: uc_volunteer.php?activity_id=" . $_GET["activity_id"]);
    } elseif ($_POST['dropdown'] == "Working Experience") {
        header("Location: uc_working_experience.php?activity_id=" . $_GET["activity_id"]);
    }
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
    <title>UC App Activity Type Select - Proactivity</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div class="text-center" style="margin-top: 30px">
            <h5 id="item_name_container">Please select type for the activity :  <?php echo $activityname; ?></h5>
        </div>
        <div class="text-center" style="margin-top: 30px">
            <select name="dropdown">
                <option value="" disabled selected>Select activity type</option>
                <option value="Educational Program"
                    <?php if ($current_type == "Educational Program")
                        echo 'selected="selected"'; ?>>Educational Program</option>
                <option value="Extracurricular Activity"
                    <?php if ($current_type == "Extracurricular Activity")
                        echo 'selected="selected"'; ?>>Extracurricular Activity</option>
                <option value="Other Coursework"
                    <?php if ($current_type == "Other Coursework")
                        echo 'selected="selected"'; ?>>Other Coursework</option>
                <option value="Volunteer"
                    <?php if ($current_type == "Volunteer")
                        echo 'selected="selected"'; ?>>Volunteer</option>
                <option value="Working Experience"
                    <?php if ($current_type == "Working Experience")
                        echo 'selected="selected"'; ?>>Working Experience</option>
            </select>
        </div>
        <div class="text-center" style="margin-top: 30px">
            <input name="btn_save" type="submit" value="Go" class="btn btn-primary btn-sm" ata-toggle="tooltip" data-placement="top" title="Save the activity type" />
        </div>
        <!--hint-->
        <div style="height: auto; margin-top: 50px;" class="row">
            <div class="col-3"></div>
            <div class="col-9">
                <div style="margin-top:20px">Educational preparation programs: Any programs or activities that have enriched your academic experiences or helped you prepare for college. </div>
                <div style="margin-top:20px">Extracurricular activity: These could include hobbies, clubs, sports or anything else you haven't had the chance to tell us about.</div>
                <div style="margin-top:20px">Other coursework: These are courses other than those required for UC admission (courses that do not fit in UC's A-G subject areas).</div>
                <div style="margin-top:20px">Volunteering / Community service: These are activities you've donated time and effort to without getting paid.</div>
                <div style="margin-top:20px">Work experience: This is for telling us about any paid jobs or paid internships you've had</div>
            </div>
        </div>
    </form>
</body>
</html>