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
include "class/uc_al.class.php";
uc_list::SynUCAppAward();
uc_list::SynUCAppActivity();
$uc_display_list = uc_list::GetDisplayList();

//click moving up button
foreach ($uc_display_list as $one_item) {
    $name = "up_" . $one_item['Item_Order'];
    if (isset($_POST["up_" . $one_item['Item_Order']]) && $one_item['Item_Order'] != $uc_display_list[0]["Item_Order"]) {
        uc_list::MovingUp($one_item['Item_Order']);
        //refresh current page
        header("Refresh:0");
    }
}


//click moving down button
foreach ($uc_display_list as $one_item) {
    $name = "down_" . $one_item['Item_Order'];
    if (isset($_POST["down_" . $one_item['Item_Order']]) && $one_item['Item_Order'] != $uc_display_list[count($uc_display_list) - 1]["Item_Order"]) {
        uc_list::MovingDown($one_item['Item_Order']);
        //refresh current page
        header("Refresh:0");
    }
}

if (isset($_POST['export_pdf'])) {
    uc_list::export_pdf();
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
    <title>UC App Awards and Activities List - Proactivity</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div style="height: auto; width: 80%; margin: auto">
            <div style="margin-top: 30px; text-align: left">
                <input name="export_pdf" type="submit" value="Generate UC App Report" class="btn btn-primary btn-sm" />
            </div>
            <div style="height: auto; margin-top: 30px; font-size: small;">
                <div>Please make sure all required information is completed first. The UC App allows up to 20 awards and activities. Please arrange the awards and activities in order of significance..</div>
            </div>
            <!--UC activity and Award list-->
            <div style="height: 30px"></div>
            <div tyle="height: auto; margin-top: 50px;">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col" class="col-1">#</th>
                            <th scope="col" class="col-6">Name</th>
                            <th scope="col" class="col-1">Completed</th>
                            <th scope="col" class="col-2">Type</th>
                            <th scope="col" class="col-1">Up</th>
                            <th scope="col" class="col-1">Down</th>
                        </tr>
                    </thead>
                    <tbody id="tbbody_UClist">
                        <?php
                        $display_id = 1;
                        if (!empty($uc_display_list) and count($uc_display_list) > 0) {
                            foreach ($uc_display_list as $one_item) {
                                if ($one_item['Item_Type'] == "Award") {
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $display_id; ?></th>
                                        <td><?php echo '<a href="uc_award_edit.php?task_id=' . $one_item['Task_Id'] . '">' . $one_item['Item_Title'] . '</a>'; ?></td>
                                        <td>
                                            <?php
                                            if ($one_item['Item_Completed'] == "Yes") {
                                                echo 'Yes';
                                            } else {
                                                echo '<span style="color: red;">No</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $one_item['Item_Type']; ?></td>
                                        <td>
                                            <?php
                                            echo '<input type="submit" name= "up_' . $one_item['Item_Order'] . '" value="Up" class="mybut btn btn-info btn-sm" style="">';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo '<input type="submit" name= "down_' . $one_item['Item_Order'] . '" value="Down" class="mybut btn btn-info btn-sm" style="">';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                } else {
                                    ?>
                                    <tr>
                                        <th scope="row"><?php echo $display_id; ?></th>
                                        <td><?php echo '<a href="uc_type_select.php?activity_id=' . $one_item['Activity_Id'] . '">' . $one_item['Item_Title'] . '</a>'; ?></td>
                                        <td>
                                            <?php
                                            if ($one_item['Item_Completed'] == "Yes") {
                                                echo 'Yes';
                                            } else {
                                                echo '<span style="color: red;">No</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo $one_item['Item_Type']; ?></td>
                                        <td>
                                            <?php
                                            echo '<input type="submit" name= "up_' . $one_item['Item_Order'] . '" value="Up" class="mybut btn btn-info btn-sm" style="">';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            echo '<input type="submit" name= "down_' . $one_item['Item_Order'] . '" value="Down" class="mybut btn btn-info btn-sm" style="">';
                                            ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                $display_id++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 50px"></div>

        </div>
        <div style="width: 80%; margin: auto">
            <h5>Useful Links</h5>
            <ul class="list-group">
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.collegeessayguy.com/blog/guide-uc-activities-list-application-example">How to Write Your UC Activities List</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.commandeducation.com/resource/uc-activities-list/">UC Activities List</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://bemoacademicconsulting.com/blog/uc-activities-awards-examples">UC Activities and Awards Examples</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.collegetransitions.com/blog/how-to-write-the-uc-activities-list/">How to Write the UC Activities List</a></li>
                <li class="list-group-item"><a target="_blank" class="link-dark link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="https://www.quadeducationgroup.com/blog/uc-activities-list">UC Activities List: Examples Of UC Activities And Awards</a></li>
            </ul>
        </div>
    </form>
</body>
</html>