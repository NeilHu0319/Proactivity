<?php

class task
{
    private $id;
    private $activity_id;
    private $name;
    private $type;
    private $date;
    private $storage = "json/activitydetail.json";
    private $stored_tasks;
    private $new_task;
    public $success;
    public $error;
    public function __construct($id, $name, $type, $date)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->date = $date;

        $this->stored_tasks = json_decode(file_get_contents($this->storage), true);

        $max_task_id = 0;
        foreach ($this->stored_tasks as $one_task) {
            if ($max_task_id < $one_task["task_id"]) {
                $max_task_id = $one_task["task_id"];
            }
            ;
        }

        $this->new_task = [
            "activity_id" => $this->id,
            "task_id" => $max_task_id + 1,
            "name" => $this->name,
            "type" => $this->type,
            "date_chosen" => $this->date,
            "date made" => date("m/d/Y")
        ];

        if ($this->checkFieldValues()) {
            $this->insertTask();
        }
    }
    private function checkFieldValues()
    {
        if (empty($this->name) || empty($this->type) || empty($this->date)) {
            $this->error = "Please fill out all fields";
            return false;
        } else {
            return true;
        }
    }
    private function taskExists()
    {
        foreach ($this->stored_tasks as $user) {
            if ($this->name == $user["name"] && $this->id == $user["activity_id"] && $this->type == $user["type"]) {
                $this->error = "This task has already exists, please enter a unique one.";
                return true;
            }
        }
        return false;
    }
    private function insertTask()
    {
        if ($this->taskExists() == FALSE) {
            array_push($this->stored_tasks, $this->new_task);
            if (file_put_contents($this->storage, json_encode($this->stored_tasks, JSON_PRETTY_PRINT))) {
                return $this->success = "Task added!";
            } else {
                return $this->error = "Something went wrong, please try again.";
            }
        }
    }


    public static function get_taskname_bytaskid($task_id)
    {
        $jsonString = file_get_contents('json/activitydetail.json');
        $data = json_decode($jsonString, true);
        $return_name = "";
        //$key is array index, $entry is current object
        foreach ($data as $key => $entry) {
            if ($entry['task_id'] == $task_id) {
                $return_name = $data[$key]['name'];
                break;
            }
        }
        return $return_name;
    }

    public static function save_notes($notes)
    {
        $json_activity = file_get_contents("json/activities.json");
        $stored_activity = json_decode($json_activity, true);
        foreach ($stored_activity as $index => $one_activity) {
            if ($one_activity['activity_id'] == $_GET['activity_id']) {
                $stored_activity[$index]['notes'] = $notes;
            }
        }
        file_put_contents("json/activities.json", json_encode($stored_activity, JSON_PRETTY_PRINT));
    }

    public static function get_award_task_id_array_forcurrentuser()
    {
        $current_user_activity_id = self::get_activityidarray_forcurrentuser();
        $json_task = file_get_contents("json/activitydetail.json");
        $stored_task = json_decode($json_task, true);
        $return_array = [];
        foreach ($stored_task as $one_task) {
            if (in_array($one_task['activity_id'], $current_user_activity_id) && $one_task["type"] == "Award") {
                array_push($return_array, $one_task['task_id']);
            }
        }
        return $return_array;
    }

    public static function get_activityidarray_forcurrentuser()
    {
        $jsonString = file_get_contents('json/activities.json');
        $data = json_decode($jsonString, true);
        $current_user_id = $_SESSION["user"];
        $return_array = [];
        //$key is array index, $entry is current object
        foreach ($data as $one_activity) {
            if ($one_activity['id'] == $current_user_id) {
                array_push($return_array, $one_activity['activity_id']);
            }
        }
        return $return_array;
    }

    public static function get_activity_id_by_task_id($task_id)
    {
        $json_task = file_get_contents("json/activitydetail.json");
        $stored_task = json_decode($json_task, true);
        $activity_id = null;
        foreach ($stored_task as $one_task) {
            if ($one_task['task_id'] == $task_id) {
                $activity_id = $one_task['activity_id'];
            }
        }

        return $activity_id;
    }

    public static function get_display_task_for_current_activity()
    {
        $return_array = [];
        $json_task = file_get_contents("json/activitydetail.json");
        $stored_task = json_decode($json_task, true);
        foreach ($stored_task as $one_task) {
            if ($one_task["activity_id"] == $_GET['activity_id']) {
                array_push($return_array, $one_task);
            }
        }
        usort($return_array, function ($a, $b) {
            $dateA = new DateTime($a['date_chosen']);
            $dateB = new DateTime($b['date_chosen']);
            return $dateA <=> $dateB;
        });

        return $return_array;
    }

    public static function get_notes()
    {
        $json_data = file_get_contents("json/activities.json");
        $stored_activities = json_decode($json_data, true);
        $notes = "";
        foreach ($stored_activities as $current_activity) {
            if ($current_activity["activity_id"] == $_GET['activity_id']) {
                $notes = $current_activity['notes'];
            }
        }
        return $notes;
    }

    public static function get_reference()
    {
        $activity_id = null;
        $reference_array = [];
        if (($_GET['activity_id']) == null) {
            $activity_id = task::get_activity_id_by_task_id($_GET['task_id']);
        } else {
            $activity_id = $_GET['activity_id'];
        }

        $json_task = file_get_contents("json/activitydetail.json");
        $stored_task = json_decode($json_task, true);
        foreach ($stored_task as $one_task) {
            if ($one_task['activity_id'] == $activity_id) {
                $one_display_reference = [
                    "Item_Type" => $one_task['type'],
                    "Item_Title" => $one_task['name'],
                    "Item_date" => $one_task["date_chosen"]
                ];

                array_push($reference_array, $one_display_reference);

            }
        }

        $final = [];
        foreach ($reference_array as $one_task) {
            if ($one_task['Item_Type'] == "Award") {
                array_push($final, $one_task);
            }
        }

        foreach ($reference_array as $one_task) {
            if ($one_task['Item_Type'] == "Completed Task") {
                array_push($final, $one_task);
            }
        }

        foreach ($reference_array as $one_task) {
            if ($one_task['Item_Type'] == "To-Do Task") {
                array_push($final, $one_task);
            }
        }

        $json_activity = file_get_contents("json/activities.json");
        $stored_activities = json_decode($json_activity, true);
        foreach ($stored_activities as $one_activity) {
            if ($one_activity['activity_id'] == $activity_id) {
                $one_display_reference = [
                    "Item_Type" => 'Notes',
                    "Item_Title" => $one_activity['notes']
                ];
                array_push($final, $one_display_reference);
            }
        }

        return $final;

    }
}