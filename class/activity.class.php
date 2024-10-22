<?php

class activity
{
    private $id;
    private $activity_id;
    private $name;
    private $finished_tasks;
    private $awards;
    private $to_do_tasks;
    private $created_date;
    private $notes;
    private $storage = "json/activities.json";
    private $stored_activities;
    private $new_activity;
    public $success;
    public $error;
    public function __construct($userid, $name)
    {
        $this->id = $userid;
        $this->name = $name;
        $this->finished_tasks = 0;
        $this->awards = 0;
        $this->to_do_tasks = 0;
        $this->created_date = date("m/d/Y");

        $this->stored_activities = json_decode(file_get_contents($this->storage), true); //true makes data into array, false makes it into an object
        $max_activity_id = 0;
        $p = 0;
        foreach ($this->stored_activities as $user) {
            if ($max_activity_id < $user["activity_id"]) {
                $max_activity_id = $user["activity_id"];
            }
            ;
            if ($userid == $user["id"])
                $p += 1;
        }
        $this->new_activity = [
            "id" => $this->id,
            "activity_id" => $max_activity_id + 1,
            "position" => $p,
            "name" => $this->name,
            "finished_tasks" => $this->finished_tasks,
            "awards" => $this->awards,
            "to_do_tasks" => $this->to_do_tasks,
            "date created" => $this->created_date,
            "notes" => null,
        ];

        if ($this->checkFieldValues()) {
            $this->insertActivity();
        }
    }

    private function checkFieldValues()
    {
        if (empty($this->name)) {
            $this->error = "Activity name cannot be blank";
            return false;
        } else {
            return true;
        }
    }

    private function activityExists()
    {
        foreach ($this->stored_activities as $user) {
            if ($this->name == $user["name"] && $this->id == $user["id"]) {
                $this->error = "This activity has already been entered, please enter a unique one.";
                return true;
            }
        }
        return false;
    }

    private function insertActivity()
    {
        if ($this->activityExists() == FALSE) {
            array_push($this->stored_activities, $this->new_activity); //add user data to stored users
            if (file_put_contents($this->storage, json_encode($this->stored_activities, JSON_PRETTY_PRINT))) { //add user data to json file
                return $this->success = "Activity added!";
            } else {
                return $this->error = "Something went wrong, please try again.";
            }
        }
    }

    public static function get_activityname_byactivityid($activity_id)
    {
        $jsonString = file_get_contents('json/activities.json');
        $data = json_decode($jsonString, true);
        $return_name = "";
        //$key is array index, $entry is current object
        foreach ($data as $key => $entry) {
            if ($entry['activity_id'] == $activity_id) {
                $return_name = $data[$key]['name'];
                break;
            }
        }
        return $return_name;
    }

    public static function get_completed_task_count($activity_id)
    {
        $jsonString = file_get_contents('json/activitydetail.json');
        $data = json_decode($jsonString, true);
        $completed_task_count = 0;
        foreach ($data as $one_task) {
            if ($one_task['activity_id'] == $activity_id && $one_task['type'] == 'Completed Task') {
                $completed_task_count++;
            }
        }
        return $completed_task_count;
    }


    public static function get_award_count($activity_id)
    {
        $jsonString = file_get_contents('json/activitydetail.json');
        $data = json_decode($jsonString, true);
        $award_count = 0;
        foreach ($data as $one_task) {
            if ($one_task['activity_id'] == $activity_id && $one_task['type'] == 'Award') {
                $award_count++;
            }
        }
        return $award_count;
    }

    public static function get_todolist_count($activity_id)
    {
        $jsonString = file_get_contents('json/activitydetail.json');
        $data = json_decode($jsonString, true);
        $todolist_count = 0;
        foreach ($data as $one_task) {
            if ($one_task['activity_id'] == $activity_id && $one_task['type'] == 'To-Do Task') {
                $todolist_count++;
            }
        }
        return $todolist_count;
    }


}