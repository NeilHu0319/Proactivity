<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected = json_decode($_POST['selected'], true);
    $data = ['selected' => $selected];
    file_put_contents('json/selected_college.json', json_encode($data));
    echo 'Saved successfully';
}