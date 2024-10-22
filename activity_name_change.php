<?php
if (isset($_POST['action']) && $_POST['action'] === 'changename') {
    echo ChangeNameFunction($_POST['activityid'], $_POST['enteredText']);
}

function ChangeNameFunction($activityid, $enteredText)
{
    $json_object = file_get_contents('json/activities.json');
    $data = json_decode($json_object, true);
    foreach ($data as $key => $entry) {
        if ($entry['activity_id'] == $activityid) {
            $data[$key]['name'] = $enteredText;
        }
    }
    file_put_contents("json/activities.json", json_encode($data, JSON_PRETTY_PRINT));
    return "ok";
}

