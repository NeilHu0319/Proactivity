<?php
class uc_award
{
    private $Activity_Id;
    private $Task_Id;
    private $Award_UC_Name;
    private $Award_Level_Of_recognition;
    private $Award_Type;
    private $Award_Grade;
    private $Award_Eligibility;
    private $Award_Desc;


    public static function save_uc_award($task_id, $award_uc_name, $award_level_of_recognition, $award_type, $award_grade, $award_eligibility, $award_desc)
    {
        $grade = implode(',', $award_grade);
        $recognition = implode(',', $award_level_of_recognition);
        $jsonString = file_get_contents('json/uc_award.json');
        $data = json_decode($jsonString, true);
        foreach ($data as $key => $entry) {
            if ($entry['Task_Id'] == $task_id) {
                $data[$key]['Award_UC_Name'] = $award_uc_name;
                $data[$key]['Award_Level_Of_recognition'] = $recognition;
                $data[$key]['Award_Type'] = $award_type;
                $data[$key]['Award_Grade'] = $grade;
                $data[$key]['Award_Eligibility'] = $award_eligibility;
                $data[$key]['Award_Desc'] = $award_desc;
            }
        }
        file_put_contents('json/uc_award.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function get_current_uc_award($task_id)
    {
        $jsonString = file_get_contents('json/uc_award.json');
        $data = json_decode($jsonString, true);
        foreach ($data as $key => $entry) {
            if ($entry['Task_Id'] == $task_id) {
                return $data[$key];
            }
        }
    }
}