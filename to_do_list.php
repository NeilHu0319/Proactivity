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
include "class/to_do_list.class.php";
include "class/activitydetail.class.php";
$current_user_activity_id = task::get_activityidarray_forcurrentuser();
$json_data = file_get_contents("json/activitydetail.json");
$activities_detail = json_decode($json_data, true);

usort($activities_detail, function ($a, $b) {
    $dateA = new DateTime($a['date_chosen']);
    $dateB = new DateTime($b['date_chosen']);
    return $dateA <=> $dateB;
});
$to_do_list_array = [];
foreach ($activities_detail as $one_task) {
    if ($one_task['type'] == "To-Do Task" && in_array($one_task['activity_id'], $current_user_activity_id)) {
        array_push($to_do_list_array, $one_task);
    }
}

if (isset($_POST['export_pdf'])) {
    require('fpdf186/fpdf.php');

    $pdf = new FPDF();
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 14);
    $pdf->SetTextColor(255, 0, 0);
    $title = "To Do List. Generated on " . date('m/d/Y');
    $pdf->Cell(0, 10, $title, 0, 1);
    $display_id = 1;
    foreach ($to_do_list_array as $one_task) {
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, $display_id . '. ' . $one_task['name'], 0, 1);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 255);
        $dueday = date("m/d/Y", strtotime($one_task['date_chosen']));
        $pdf->Cell(0, 10, 'Due Day : ' . $dueday, 0, 1);
        $display_id++;
    }

    $filePath = 'pdf/todolist_' . date('YmdHis') . '.pdf';

    $pdf->Output('F', $filePath);

    if (file_exists($filePath)) {
        echo "<script>window.open('$filePath', '_blank');</script>";
    } else {
        echo "Failed to save PDF.";
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
    <title>To Do List - Proactivity</title>
</head>
<body>
    <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
        <!--menu bar-->
        <?php include 'menu.php'; ?>
        <div style="height: 30px"></div>
        <div style="height: 40px; width: 80%; margin: auto">
            <h5>To Do List Overview</h5>
        </div>
        <div style="height: auto; width: 80%; margin: auto">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" class="col-1">#</th>
                        <th scope="col" class="col-7">Task</th>
                        <th scope="col" class="col-2">Due Day</th>
                        <th scope="col" class="col-2">Add to Google Calendar</th>

                    </tr>
                </thead>
                <tbody id="tbbody_ToDolist">
                    <?php
                    $display_id = 1;
                    if (!empty($to_do_list_array) and count($to_do_list_array) > 0) {
                        foreach ($to_do_list_array as $one_task) {
                            ?>
                            <tr>
                                <th scope="row"><?php echo $display_id; ?></th>
                                <td><?php echo '<a href="activitydetails.php?activity_id=' . $one_task['activity_id'] . '">' . $one_task['name'] . '</a>'; ?></td>
                                <td>
                                    <?php
                                    $date_chosen = new DateTime($one_task['date_chosen']);
                                    $today = new DateTime();
                                    if ($date_chosen < $today) {
                                        echo '<span style="color: red;">' . $one_task['date_chosen'] . ' (Overdue)</span>';
                                    } else {
                                        echo $one_task['date_chosen'];
                                    }
                                    ?>
                                </td>
                                <!--<td><?php echo '<a href="https://www.google.com/calendar/render?action=TEMPLATE&text=' . $one_task['name'] . '&details=Description&location=London&dates=' . date("Ymd", strtotime($one_task['date_chosen'])) . 'T200000Z%2F' . date("Ymd", strtotime($one_task['date_chosen'])) . 'T210000Z&add=neilhu0319@gmail.com" target="_blank">Add</a>'; ?></td>-->
                                <td><?php echo '<a href="https://www.google.com/calendar/render?action=TEMPLATE&text=' . $one_task['name'] . '&details=Description&dates=' . date("Ymd", strtotime($one_task['date_chosen'])) . 'T200000Z%2F' . date("Ymd", strtotime($one_task['date_chosen'])) . 'T210000Z" target="_blank">Add</a>'; ?></td>

                            </tr>
                            <?php
                            $display_id++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top: 30px; text-align: right; width: 80%; margin: auto">
            <input name="export_pdf" type="submit" value="Export PDF File" class="btn btn-primary btn-sm" ata-toggle="tooltip" data-placement="top" title="Save Notes" />
        </div>
    </form>
</body>
</html>