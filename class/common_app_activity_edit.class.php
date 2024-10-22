<?php
class common_app_activity
{
    private $Activity_Id;
    private $Activity_Type;
    private $Position;
    private $Organization_Name;
    private $Desc;
    private $Participant_Grade;
    private $Timing_Of_Participant;
    private $Hours_Spent_Per_Year;
    private $Weeks_Spent_Per_Year;
    private $Intend_Participant_In_College;
    private $Activity_Order;
    private $storage = "json/activities.json";
    private $stored_activities;
    private $new_activity;
    public $success;
    public $error;

    public static function save_common_app_activity($activity_id, $dropdown, $position, $organization, $desc, $checkboxgrade, $timing_of_participant, $Hours_Spent_Per_Year, $Weeks_Spent_Per_Year, $Intend_Participant_In_College)
    {
        $grade = implode(',', $checkboxgrade);
        $timing_of_participant = implode(',', $timing_of_participant);
        $jsonString = file_get_contents('json/common_app_activity.json');
        $data = json_decode($jsonString, true);
        //$key is array index, $entry is current object
        foreach ($data as $key => $entry) {
            if ($entry['Activity_Id'] == $activity_id) {
                $data[$key]['Activity_Type'] = $dropdown;
                $data[$key]['Position'] = $position;
                $data[$key]['Organization_Name'] = $organization;
                $data[$key]['Desc'] = $desc;
                $data[$key]['Participant_Grade'] = $grade;
                $data[$key]['Timing_Of_Participant'] = $timing_of_participant;
                $data[$key]['Hours_Spent_Per_Year'] = $Hours_Spent_Per_Year;
                $data[$key]['Weeks_Spent_Per_Year'] = $Weeks_Spent_Per_Year;
                $data[$key]['Intend_Participant_In_College'] = $Intend_Participant_In_College;
            }
        }
        file_put_contents('json/common_app_activity.json', json_encode($data, JSON_PRETTY_PRINT));

    }

    public static function get_current_commonapp_activity($activity_id)
    {
        $jsonString = file_get_contents('json/common_app_activity.json');
        $data = json_decode($jsonString, true);
        //$key is array index, $entry is current object
        foreach ($data as $key => $entry) {
            if ($entry['Activity_Id'] == $activity_id) {
                return $data[$key];
            }
        }
    }

}

