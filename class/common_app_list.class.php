<?php
include "activitydetail.class.php";
class common_app_list
{
    private $Task_Id;
    private $Activity_Id;
    private $Item_Type;
    private $Item_Title;
    private $Item_Completed;
    private $Item_Order;

    public static function SynCommonAppAward()
    {
        $common_app_json = file_get_contents('json/common_app_award.json');
        $common_app_award_array = json_decode($common_app_json, true);
        $common_app_award_task_id_array = [];

        $activity_award_json = file_get_contents('json/activitydetail.json');
        $activity_award_array = json_decode($activity_award_json, true);
        $task_id_arry = [];

        foreach ($activity_award_array as $one_award) {
            if ($one_award["type"] == "Award") {
                array_push($task_id_arry, $one_award["task_id"]);
            }
        }

        foreach ($common_app_award_array as $one_award) {
            array_push($common_app_award_task_id_array, $one_award["Task_Id"]);

        }

        foreach ($common_app_award_array as $index => $one_award) {
            $common_app_task_id = $one_award["Task_Id"];
            if (!in_array($common_app_task_id, $task_id_arry)) {
                unset($common_app_award_array[$index]);
            }
        }

        $max_award_order = 0;
        foreach ($common_app_award_array as $one_award) {
            if ($max_award_order < $one_award["Award_Order"]) {
                $max_award_order = $one_award["Award_Order"];
            }
        }


        foreach ($activity_award_array as $index => $one_award) {
            if ($one_award["type"] == "Award" && !in_array($one_award["task_id"], $common_app_award_task_id_array)) {
                $new_commonapp_award = [
                    "Activity_Id" => $one_award["activity_id"],
                    "Task_Id" => $one_award["task_id"],
                    "Award_Desc" => null,
                    "Award_Grade" => null,
                    "Award_Level_Of_recognition" => null,
                    "Award_Order" => $max_award_order + 1
                ];
                array_push($common_app_award_array, $new_commonapp_award);
                $max_award_order = $max_award_order + 1;
            }
        }

        file_put_contents('json/common_app_award.json', json_encode($common_app_award_array, JSON_PRETTY_PRINT));

    }

    public static function SynCommonAppActivity()
    {
        $common_app_json = file_get_contents('json/common_app_activity.json');
        $common_app_activity_array = json_decode($common_app_json, true);
        $common_app_activity_id_array = [];

        foreach ($common_app_activity_array as $one_activity) {
            array_push($common_app_activity_id_array, $one_activity["Activity_Id"]);

        }

        $real_activity_json = file_get_contents('json/activities.json');
        $real_activity_array = json_decode($real_activity_json, true);
        $real_activity_id_arry = [];

        foreach ($real_activity_array as $one_activity) {
            array_push($real_activity_id_arry, $one_activity["activity_id"]);
        }



        foreach ($common_app_activity_array as $index => $one_activity) {
            $common_app_activity_id = $one_activity["Activity_Id"];
            if (!in_array($common_app_activity_id, $real_activity_id_arry)) {
                unset($common_app_activity_array[$index]);
            }
        }

        $max_activity_order = 0;
        foreach ($common_app_activity_array as $one_activity) {
            if ($max_activity_order < $one_activity["Activity_Order"]) {
                $max_activity_order = $one_activity["Activity_Order"];
            }
        }


        foreach ($real_activity_array as $index => $one_activity) {
            if (!in_array($one_activity["activity_id"], $common_app_activity_id_array)) {
                $new_commonapp_activity = [
                    "Activity_Id" => $one_activity["activity_id"],
                    "Activity_Type" => null,
                    "Position" => null,
                    "Organization_Name" => null,
                    "Desc" => null,
                    "Participant_Grade" => null,
                    "Timing_Of_Participant" => null,
                    "Hours_Spent_Per_Year" => null,
                    "Weeks_Spent_Per_Year" => null,
                    "Intend_Participant_In_College" => null,
                    "Activity_Order" => $max_activity_order + 1
                ];
                array_push($common_app_activity_array, $new_commonapp_activity);
                $max_activity_order = $max_activity_order + 1;
            }
        }

        file_put_contents('json/common_app_activity.json', json_encode($common_app_activity_array, JSON_PRETTY_PRINT));
    }

    public static function GetCommonAppAcitivtyArrayForCurrentUser()
    {
        $current_user_activity_id_array = task::get_activityidarray_forcurrentuser();

        $common_app_json = file_get_contents('json/common_app_activity.json');
        $common_app_activity_array = json_decode($common_app_json, true);
        $current_user_common_app_activity_array = [];

        foreach ($common_app_activity_array as $one_activity) {
            if (in_array($one_activity['Activity_Id'], $current_user_activity_id_array)) {
                array_push($current_user_common_app_activity_array, $one_activity);
            }
        }

        usort($current_user_common_app_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        return $current_user_common_app_activity_array;

    }

    public static function GetCommonAppAwardArrayForCurrentUser()
    {
        $current_user_award_task_id_array = task::get_award_task_id_array_forcurrentuser();
        $common_app_json = file_get_contents('json/common_app_award.json');
        $common_app_award_array = json_decode($common_app_json, true);
        $current_user_common_app_award_array = [];
        foreach ($common_app_award_array as $one_award) {
            if (in_array($one_award['Task_Id'], $current_user_award_task_id_array)) {
                array_push($current_user_common_app_award_array, $one_award);
            }
        }

        usort($current_user_common_app_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });

        return $current_user_common_app_award_array;

    }

    public static function GetAwardList()
    {
        $current_user_common_app_award_array = self::GetCommonAppAwardArrayForCurrentUser();

        $award_display_list = [];
        foreach ($current_user_common_app_award_array as $one_award) {
            $one_display_award = new common_app_list;
            $one_display_award = [
                "Item_Type" => "Award",
                "Item_Title" => task::get_taskname_bytaskid($one_award["Task_Id"]),
                "Task_Id" => $one_award["Task_Id"],
                "Activity_Id" => $one_award["Activity_Id"],
                "Item_Order" => $one_award["Award_Order"]
            ];

            if (self::IsNullOrEmptyString($one_award["Award_Desc"]) || self::IsNullOrEmptyString($one_award["Award_Grade"]) || self::IsNullOrEmptyString($one_award["Award_Level_Of_recognition"])) {
                $one_display_award["Item_Completed"] = "No";
            } else {
                $one_display_award["Item_Completed"] = "Yes";
            }

            array_push($award_display_list, $one_display_award);
        }

        usort($award_display_list, function ($a, $b) {
            return intval($a['Item_Order']) - intval($b['Item_Order']);
        });

        return $award_display_list;
    }

    public static function GetActivityList()
    {
        $current_user_common_app_activity_array = self::GetCommonAppAcitivtyArrayForCurrentUser();

        $activity_display_list = [];
        foreach ($current_user_common_app_activity_array as $one_activity) {
            $one_display_activity = new common_app_list;
            $one_display_activity = [
                "Item_Type" => "Activity",
                "Item_Title" => self::get_activityname_byactivityid($one_activity["Activity_Id"]),
                "Activity_Id" => $one_activity["Activity_Id"],
                "Item_Order" => $one_activity["Activity_Order"]
            ];

            if (self::IsNullOrEmptyString($one_activity["Activity_Type"]) || self::IsNullOrEmptyString($one_activity["Position"]) || self::IsNullOrEmptyString($one_activity["Organization_Name"]) || self::IsNullOrEmptyString($one_activity["Desc"]) || self::IsNullOrEmptyString($one_activity["Participant_Grade"]) || self::IsNullOrEmptyString($one_activity["Timing_Of_Participant"]) || self::IsNullOrEmptyString($one_activity["Hours_Spent_Per_Year"]) || self::IsNullOrEmptyString($one_activity["Weeks_Spent_Per_Year"]) || self::IsNullOrEmptyString($one_activity["Intend_Participant_In_College"])) {
                $one_display_activity["Item_Completed"] = "No";
            } else {
                $one_display_activity["Item_Completed"] = "Yes";
            }

            array_push($activity_display_list, $one_display_activity);
        }

        usort($activity_display_list, function ($a, $b) {
            return intval($a['Item_Order']) - intval($b['Item_Order']);
        });

        return $activity_display_list;
    }

    public static function IsNullOrEmptyString(string|null $str)
    {
        return $str === null || trim($str) === '';
    }


    public static function AwardMovingUp($current_order)
    {
        //$current_user_award_array already sorted by Award_Order
        $current_user_award_array = self::GetCommonAppAwardArrayForCurrentUser();

        $last_order = 0;
        foreach ($current_user_award_array as $index => $one_award) {
            if ($one_award["Award_Order"] == $current_order) {
                $last_order = $current_user_award_array[$index - 1]["Award_Order"];
            }
        }

        $common_app_json = file_get_contents('json/common_app_award.json');
        $all_award_array = json_decode($common_app_json, true);

        usort($all_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });

        $current_award_index = 0;
        $last_award_index = 0;
        foreach ($all_award_array as $index => $one_award) {
            if ($one_award["Award_Order"] == $current_order) {
                $current_award_index = $index;
            }
        }

        foreach ($all_award_array as $index => $one_award) {
            if ($one_award["Award_Order"] == $last_order) {
                $last_award_index = $index;
            }
        }

        $all_award_array[$current_award_index]["Award_Order"] = $last_order;
        $all_award_array[$last_award_index]["Award_Order"] = $current_order;


        usort($all_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });

        file_put_contents('json/common_app_award.json', json_encode($all_award_array, JSON_PRETTY_PRINT));
    }


    public static function AwardMovingDown($current_order)
    {
        $current_user_award_array = self::GetCommonAppAwardArrayForCurrentUser();

        $next_order = 0;
        foreach ($current_user_award_array as $index => $one_award) {
            if ($one_award["Award_Order"] == $current_order) {
                $next_order = $current_user_award_array[$index + 1]["Award_Order"];
            }
        }

        $common_app_json = file_get_contents('json/common_app_award.json');
        $all_award_array = json_decode($common_app_json, true);

        usort($all_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });

        $current_award_index = 0;
        $next_award_index = 0;
        foreach ($all_award_array as $index => $one_award) {
            if ($one_award["Award_Order"] == $current_order) {
                $current_award_index = $index;
            }
        }

        foreach ($all_award_array as $index => $one_award) {
            if ($one_award["Award_Order"] == $next_order) {
                $next_award_index = $index;
            }
        }

        $all_award_array[$current_award_index]["Award_Order"] = $next_order;
        $all_award_array[$next_award_index]["Award_Order"] = $current_order;


        usort($all_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });

        file_put_contents('json/common_app_award.json', json_encode($all_award_array, JSON_PRETTY_PRINT));
    }


    public static function ActivityMovingUp($current_order)
    {
        $current_user_activity_array = self::GetCommonAppAcitivtyArrayForCurrentUser();

        $last_order = 0;
        foreach ($current_user_activity_array as $index => $one_activity) {
            if ($one_activity["Activity_Order"] == $current_order) {
                $last_order = $current_user_activity_array[$index - 1]["Activity_Order"];
            }
        }

        $common_app_json = file_get_contents('json/common_app_activity.json');
        $all_activity_array = json_decode($common_app_json, true);

        usort($all_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        $current_activity_index = 0;
        $last_activity_index = 0;
        foreach ($all_activity_array as $index => $one_activity) {
            if ($one_activity["Activity_Order"] == $current_order) {
                $current_activity_index = $index;
            }
        }

        foreach ($all_activity_array as $index => $one_activity) {
            if ($one_activity["Activity_Order"] == $last_order) {
                $last_activity_index = $index;
            }
        }

        $all_activity_array[$current_activity_index]["Activity_Order"] = $last_order;
        $all_activity_array[$last_activity_index]["Activity_Order"] = $current_order;

        usort($all_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        file_put_contents('json/common_app_activity.json', json_encode($all_activity_array, JSON_PRETTY_PRINT));
    }


    public static function ActivityMovingDown($current_order)
    {
        $current_user_activity_array = self::GetCommonAppAcitivtyArrayForCurrentUser();

        $next_order = 0;
        foreach ($current_user_activity_array as $index => $one_activity) {
            if ($one_activity["Activity_Order"] == $current_order) {
                $next_order = $current_user_activity_array[$index + 1]["Activity_Order"];
            }
        }

        $common_app_json = file_get_contents('json/common_app_activity.json');
        $all_activity_array = json_decode($common_app_json, true);

        usort($all_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        $current_activity_index = 0;
        $next_activity_index = 0;
        foreach ($all_activity_array as $index => $one_activity) {
            if ($one_activity["Activity_Order"] == $current_order) {
                $current_activity_index = $index;
            }
        }

        foreach ($all_activity_array as $index => $one_activity) {
            if ($one_activity["Activity_Order"] == $next_order) {
                $next_activity_index = $index;
            }
        }

        $all_activity_array[$current_activity_index]["Activity_Order"] = $next_order;
        $all_activity_array[$next_activity_index]["Activity_Order"] = $current_order;

        usort($all_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        file_put_contents('json/common_app_activity.json', json_encode($all_activity_array, JSON_PRETTY_PRINT));
    }


    public static function get_activityname_byactivityid($activity_id)
    {
        $jsonString = file_get_contents('json/activities.json');
        $data = json_decode($jsonString, true);
        $return_name = "";
        foreach ($data as $key => $entry) {
            if ($entry['activity_id'] == $activity_id) {
                $return_name = $data[$key]['name'];
                break;
            }
        }
        return $return_name;
    }


    public static function export_pdf()
    {
        require('fpdf186/fpdf.php');

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(255, 0, 0);
        $title = "Common App Award Report. Generated on " . date('m/d/Y');
        $pdf->Cell(0, 10, $title, 0, 1);
        $pdf->Cell(0, 10, '', 0, 1);

        //award
        $display_award_id = 1;
        $current_user_common_app_award_array = self::GetCommonAppAwardArrayForCurrentUser();
        foreach ($current_user_common_app_award_array as $one_award) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetTextColor(0, 0, 255);
            $award_name = task::get_taskname_bytaskid($one_award['Task_Id']);
            $pdf->Cell(0, 10, $display_award_id . '. ' . $award_name, 0, 1);

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(0, 10, 'Honors title and description : ' . $one_award['Award_Desc'], 0, 1);
            $pdf->Cell(0, 10, 'Grade level : ' . $one_award['Award_Grade'], 0, 1);
            $pdf->Cell(0, 10, 'Level(s) of recognition : ' . $one_award['Award_Level_Of_recognition'], 0, 1);
            $pdf->Cell(0, 10, '', 0, 1);
            $display_award_id++;
        }

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetTextColor(255, 0, 0);
        $title = "Common App Activity Report. Generated on " . date('m/d/Y');
        $pdf->Cell(0, 10, $title, 0, 1);
        $pdf->Cell(0, 10, '', 0, 1);

        //activity
        $display_activity_id = 1;
        $current_user_common_app_activity_array = self::GetCommonAppAcitivtyArrayForCurrentUser();
        foreach ($current_user_common_app_activity_array as $one_activity) {
            $pdf->SetFont('Arial', '', 12);
            $pdf->SetTextColor(0, 0, 255);
            $activity_name = self::get_activityname_byactivityid($one_activity['Activity_Id']);
            $pdf->Cell(0, 10, $display_activity_id . '. ' . $activity_name, 0, 1);

            $pdf->SetFont('Arial', '', 12);
            $pdf->SetTextColor(0, 0, 0);
            $pdf->Cell(0, 10, 'Activity Type : ' . $one_activity['Activity_Type'], 0, 1);
            $pdf->MultiCell(0, 10, 'Position/Leadership description : ' . $one_activity['Position'], 0, 1);
            $pdf->MultiCell(0, 10, 'Organization Name : ' . $one_activity['Organization_Name'], 0, 1);
            $pdf->MultiCell(0, 10, 'Activity Description : ' . $one_activity['Desc'], 0, 1);
            $pdf->Cell(0, 10, 'Participation grade levels : ' . $one_activity['Participant_Grade'], 0, 1);
            $pdf->Cell(0, 10, 'Timing of participation : ' . $one_activity['Timing_Of_Participant'], 0, 1);
            $pdf->Cell(0, 10, 'Hours spent per week : ' . $one_activity['Hours_Spent_Per_Year'], 0, 1);
            $pdf->Cell(0, 10, 'Weeks spent per year : ' . $one_activity['Weeks_Spent_Per_Year'], 0, 1);
            $pdf->Cell(0, 10, 'I intend to participate in a similar activity in college ? ' . $one_activity['Intend_Participant_In_College'], 0, 1);
            $display_activity_id++;
            if ($display_activity_id <= count($current_user_common_app_activity_array)) {
                $pdf->Cell(0, 10, '', 0, 1);
            }
        }


        $filePath = 'pdf/commonapp_' . date('YmdHis') . '.pdf';

        $pdf->Output('F', $filePath);

        if (file_exists($filePath)) {
            echo "<script>window.open('$filePath', '_blank');</script>";
        } else {
            echo "Failed to save PDF.";
        }
    }

}