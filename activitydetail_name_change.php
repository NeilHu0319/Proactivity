<?php
if (isset($_POST['action']) && $_POST['action'] === 'changename') {
    echo ChangeNameFunction($_POST['taskid'], $_POST['enteredText']);
}

function ChangeNameFunction($taskid, $enteredText)
{
    $id = $taskid;
    $newname = $enteredText;
    $json_object = file_get_contents('json/activitydetail.json');
    $data = json_decode($json_object, true);
    $f = [];
    foreach ($data as $d) {
        if ($newname == $d["name"] && $id != $d["task_id"]) {
            $error = "This task has already exists, please enter a unique one.";
            return $error;
        }
    }
    foreach ($data as $d) {
        if ($d["task_id"] == $id) {
            $new = [
                "activity_id" => $d["activity_id"],
                "task_id" => $d["task_id"],
                "name" => $newname,
                "type" => $d["type"],
                "date_chosen" => $d["date_chosen"],
                "date made" => $d["date made"]
            ];
        } else {
            $new = [
                "activity_id" => $d["activity_id"],
                "task_id" => $d["task_id"],
                "name" => $d["name"],
                "type" => $d["type"],
                "date_chosen" => $d["date_chosen"],
                "date made" => $d["date made"]
            ];
        }
        array_push($f, $new);

    }
    file_put_contents("json/activitydetail.json", json_encode($f, JSON_PRETTY_PRINT));
    return "ok";
}

