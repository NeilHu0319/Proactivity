<?php
class uc_activity
{
    private $Activity_Id;
    private $UC_Type;
    private $Organization_Program_Course_Name;
    private $Desc;
    private $What_You_Did;
    private $Grade;
    private $Hours_Spent_Per_Week;
    private $Weeks_Spent_Per_Year;
    private $Working_Place;
    private $Job_Title;
    private $Job_Responsibility;
    private $Still_Work;
    private $Activity_Order;

    public static function save_uc_activity($activity_id, $organization_program_course_name, $desc, $what_you_did, $checkboxgrade, $hours_spent_per_week, $weeks_spent_per_year, $working_place, $job_title, $job_responsibility, $still_work)
    {
        $grade = implode(',', $checkboxgrade);
        $jsonString = file_get_contents('json/uc_activity.json');
        $data = json_decode($jsonString, true);
        foreach ($data as $key => $entry) {
            if ($entry['Activity_Id'] == $activity_id) {
                $data[$key]['Organization_Program_Course_Name'] = $organization_program_course_name;
                $data[$key]['Desc'] = $desc;
                $data[$key]['What_You_Did'] = $what_you_did;
                $data[$key]['Grade'] = $grade;
                $data[$key]['Hours_Spent_Per_Week'] = $hours_spent_per_week;
                $data[$key]['Weeks_Spent_Per_Year'] = $weeks_spent_per_year;
                $data[$key]['Working_Place'] = $working_place;
                $data[$key]['Job_Title'] = $job_title;
                $data[$key]['Job_Responsibility'] = $job_responsibility;
                $data[$key]['Still_Work'] = $still_work;
            }
        }
        file_put_contents('json/uc_activity.json', json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function get_current_uc_activity($activity_id)
    {
        $jsonString = file_get_contents('json/uc_activity.json');
        $data = json_decode($jsonString, true);
        foreach ($data as $key => $entry) {
            if ($entry['Activity_Id'] == $activity_id) {
                return $data[$key];
            }
        }
    }
}
