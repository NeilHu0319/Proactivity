<?php

class common_app_award
{
    private $Task_Id;
    private $Award_Desc;
    private $Award_Grade;
    private $Award_Level_Of_recognition;


    public static function save_common_app_award($task_id, $award_desc, $award_grade, $award_level_of_recognition)
    {
        $grade = implode(',', $award_grade);
        $recognition = implode(',', $award_level_of_recognition);
        $jsonString = file_get_contents('json/common_app_award.json');
        $data = json_decode($jsonString, true);
        foreach ($data as $key => $entry) {
            if ($entry['Task_Id'] == $task_id) {
                $data[$key]['Award_Desc'] = $award_desc;
                $data[$key]['Award_Grade'] = $grade;
                $data[$key]['Award_Level_Of_recognition'] = $recognition;
            }
        }
        file_put_contents('json/common_app_award.json', json_encode($data, JSON_PRETTY_PRINT));

    }

    public static function get_current_commonapp_award($task_id)
    {
        $jsonString = file_get_contents('json/common_app_award.json');
        $data = json_decode($jsonString, true);
        //$key is array index, $entry is current object
        foreach ($data as $key => $entry) {
            if ($entry['Task_Id'] == $task_id) {
                return $data[$key];
            }
        }
    }
}

