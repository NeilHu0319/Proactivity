<?php
include "activitydetail.class.php";
class uc_list
{
    private $Task_Id;
    private $Activity_Id;
    private $Item_Type;
    private $Item_Title;
    private $Item_Completed;
    private $Item_Order;

    public static function SynUCAppAward()
    {
        $uc_json = file_get_contents('json/uc_award.json');
        $uc_award_array = json_decode($uc_json, true);
        $uc_award_task_id_array = [];

        foreach ($uc_award_array as $one_award) {
            array_push($uc_award_task_id_array, $one_award["Task_Id"]);
        }

        $activity_award_json = file_get_contents('json/activitydetail.json');
        $activity_award_array = json_decode($activity_award_json, true);
        $task_id_arry = [];

        foreach ($activity_award_array as $one_award) {
            if ($one_award["type"] == "Award") {
                array_push($task_id_arry, $one_award["task_id"]);
            }
        }

        foreach ($uc_award_array as $index => $one_award) {
            $uc_award_task_id = $one_award["Task_Id"];
            if (!in_array($uc_award_task_id, $task_id_arry)) {
                unset($uc_award_array[$index]);
            }
        }

        $max_award_order = 0;
        foreach ($uc_award_array as $one_award) {
            if ($max_award_order < $one_award["Award_Order"]) {
                $max_award_order = $one_award["Award_Order"];
            }
        }

        $uc_activity_json = file_get_contents('json/uc_activity.json');
        $uc_activity_array = json_decode($uc_activity_json, true);
        foreach ($uc_activity_array as $one_activity) {
            if ($max_award_order < $one_activity["Activity_Order"]) {
                $max_award_order = $one_activity["Activity_Order"];
            }
        }


        foreach ($activity_award_array as $index => $one_award) {
            if ($one_award["type"] == "Award" && !in_array($one_award["task_id"], $uc_award_task_id_array)) {
                $new_uc_award = [
                    "Activity_Id" => $one_award["activity_id"],
                    "Task_Id" => $one_award["task_id"],
                    "Award_UC_Name" => null,
                    "Award_Level_Of_recognition" => null,
                    "Award_Type" => null,
                    "Award_Grade" => null,
                    "Award_Eligibility" => null,
                    "Award_Desc" => null,
                    "Award_Order" => $max_award_order + 1

                ];
                array_push($uc_award_array, $new_uc_award);
                $max_award_order = $max_award_order + 1;
            }
        }

        usort($uc_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });

        file_put_contents('json/uc_award.json', json_encode($uc_award_array, JSON_PRETTY_PRINT));

    }

    public static function SynUCAppActivity()
    {
        $uc_json = file_get_contents('json/uc_activity.json');
        $uc_activity_array = json_decode($uc_json, true);
        $uc_activity_id_array = [];

        foreach ($uc_activity_array as $one_activity) {
            array_push($uc_activity_id_array, $one_activity["Activity_Id"]);

        }

        $real_activity_json = file_get_contents('json/activities.json');
        $real_activity_array = json_decode($real_activity_json, true);
        $real_activity_id_arry = [];

        foreach ($real_activity_array as $one_activity) {
            array_push($real_activity_id_arry, $one_activity["activity_id"]);
        }



        foreach ($uc_activity_array as $index => $one_activity) {
            $uc_activity_id = $one_activity["Activity_Id"];
            if (!in_array($uc_activity_id, $real_activity_id_arry)) {
                unset($uc_activity_array[$index]);
            }
        }

        $max_activity_order = 0;
        foreach ($uc_activity_array as $one_activity) {
            if ($max_activity_order < $one_activity["Activity_Order"]) {
                $max_activity_order = $one_activity["Activity_Order"];
            }
        }

        $uc_award_json = file_get_contents('json/uc_award.json');
        $uc_award_array = json_decode($uc_award_json, true);
        foreach ($uc_award_array as $one_award) {
            if ($max_activity_order < $one_award["Award_Order"]) {
                $max_activity_order = $one_award["Award_Order"];
            }
        }


        foreach ($real_activity_array as $index => $one_activity) {
            if (!in_array($one_activity["activity_id"], $uc_activity_id_array)) {
                $new_uc_activity = [
                    "Activity_Id" => $one_activity["activity_id"],
                    "UC_Type" => null,
                    "Organization_Program_Course_Name" => null,
                    "Desc" => null,
                    "What_You_Did" => null,
                    "Grade" => null,
                    "Hours_Spent_Per_Week" => null,
                    "Weeks_Spent_Per_Year" => null,
                    "Working_Place" => null,
                    "Job_Title" => null,
                    "Job_Responsibility" => null,
                    "Still_Work" => null,
                    "Activity_Order" => $max_activity_order + 1
                ];
                array_push($uc_activity_array, $new_uc_activity);
                $max_activity_order = $max_activity_order + 1;
            }
        }

        usort($uc_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        file_put_contents('json/uc_activity.json', json_encode($uc_activity_array, JSON_PRETTY_PRINT));
    }

    public static function GetDisplayList()
    {
        $current_user_uc_award_array = self::GetUCAwardArrayForCurrentUser();
        $current_user_uc_activity_array = self::GetUCActivityForCurrentUser();

        $display_list = [];
        foreach ($current_user_uc_award_array as $one_award) {
            $one_display_award = new uc_list;
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

            array_push($display_list, $one_display_award);
        }


        foreach ($current_user_uc_activity_array as $one_activity) {
            $one_display_activity = new uc_list;
            $one_display_activity = [
                "Item_Type" => $one_activity["UC_Type"],
                "Item_Title" => self::get_activityname_byactivityid($one_activity["Activity_Id"]),
                "Task_Id" => null,
                "Activity_Id" => $one_activity["Activity_Id"],
                "Item_Order" => $one_activity["Activity_Order"]
            ];

            $one_display_activity["Item_Completed"] = "No";

            if ($one_activity["UC_Type"] == "Educational Program" && !self::IsNullOrEmptyString($one_activity["Organization_Program_Course_Name"]) && !self::IsNullOrEmptyString($one_activity["Desc"]) && !self::IsNullOrEmptyString($one_activity["Grade"]) && !self::IsNullOrEmptyString($one_activity["Hours_Spent_Per_Week"]) && !self::IsNullOrEmptyString($one_activity["Weeks_Spent_Per_Year"])) {
                $one_display_activity["Item_Completed"] = "Yes";
            }

            if ($one_activity["UC_Type"] == "Extracurricular Activity" && !self::IsNullOrEmptyString($one_activity["Organization_Program_Course_Name"]) && !self::IsNullOrEmptyString($one_activity["Desc"]) && !self::IsNullOrEmptyString($one_activity["Grade"]) && !self::IsNullOrEmptyString($one_activity["Hours_Spent_Per_Week"]) && !self::IsNullOrEmptyString($one_activity["Weeks_Spent_Per_Year"])) {
                $one_display_activity["Item_Completed"] = "Yes";
            }

            if ($one_activity["UC_Type"] == "Volunteer" && !self::IsNullOrEmptyString($one_activity["Organization_Program_Course_Name"]) && !self::IsNullOrEmptyString($one_activity["Desc"]) && !self::IsNullOrEmptyString($one_activity["What_You_Did"]) && !self::IsNullOrEmptyString($one_activity["Grade"]) && !self::IsNullOrEmptyString($one_activity["Hours_Spent_Per_Week"]) && !self::IsNullOrEmptyString($one_activity["Weeks_Spent_Per_Year"])) {
                $one_display_activity["Item_Completed"] = "Yes";
            }

            if ($one_activity["UC_Type"] == "Working Experience" && !self::IsNullOrEmptyString($one_activity["Desc"]) && !self::IsNullOrEmptyString($one_activity["Grade"]) && !self::IsNullOrEmptyString($one_activity["Working_Place"]) && !self::IsNullOrEmptyString($one_activity["Job_Title"]) && !self::IsNullOrEmptyString($one_activity["Job_Responsibility"]) && !self::IsNullOrEmptyString($one_activity["Still_Work"])) {
                $one_display_activity["Item_Completed"] = "Yes";
            }

            if ($one_activity["UC_Type"] == "Other Coursework" && !self::IsNullOrEmptyString($one_activity["Organization_Program_Course_Name"]) && !self::IsNullOrEmptyString($one_activity["Desc"]) && !self::IsNullOrEmptyString($one_activity["Grade"]) && !self::IsNullOrEmptyString($one_activity["Hours_Spent_Per_Week"]) && !self::IsNullOrEmptyString($one_activity["Weeks_Spent_Per_Year"])) {
                $one_display_activity["Item_Completed"] = "Yes";
            }


            array_push($display_list, $one_display_activity);
        }

        usort($display_list, function ($a, $b) {
            return intval($a['Item_Order']) - intval($b['Item_Order']);
        });

        return $display_list;
    }

    public static function GetUCActivityForCurrentUser()
    {
        $current_user_activity_id_array = task::get_activityidarray_forcurrentuser();

        $uc_json = file_get_contents('json/uc_activity.json');
        $uc_activity_array = json_decode($uc_json, true);
        $current_user_uc_activity_array = [];

        foreach ($uc_activity_array as $one_activity) {
            if (in_array($one_activity['Activity_Id'], $current_user_activity_id_array)) {
                array_push($current_user_uc_activity_array, $one_activity);
            }
        }

        usort($current_user_uc_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        return $current_user_uc_activity_array;

    }


    public static function GetUCAwardArrayForCurrentUser()
    {
        $current_user_award_task_id_array = task::get_award_task_id_array_forcurrentuser();
        $uc_json = file_get_contents('json/uc_award.json');
        $uc_award_array = json_decode($uc_json, true);
        $current_user_uc_award_array = [];
        foreach ($uc_award_array as $one_award) {
            if (in_array($one_award['Task_Id'], $current_user_award_task_id_array)) {
                array_push($current_user_uc_award_array, $one_award);
            }
        }

        usort($current_user_uc_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });

        return $current_user_uc_award_array;

    }

    public static function IsNullOrEmptyString(string|null $str)
    {
        return $str === null || trim($str) === '';
    }


    public static function MovingUp($current_order)
    {
        $uc_display_list = self::GetDisplayList();
        $current_item_type = "";
        $last_item_type = "";

        $last_order = 0;
        foreach ($uc_display_list as $index => $one_item) {
            if ($one_item["Item_Order"] == $current_order) {
                $current_item_type = $uc_display_list[$index]["Item_Type"];
                $last_item_type = $uc_display_list[$index - 1]["Item_Type"];
                $last_order = $uc_display_list[$index - 1]["Item_Order"];
            }
        }

        $uc_activity_json = file_get_contents('json/uc_activity.json');
        $all_activity_array = json_decode($uc_activity_json, true);
        usort($all_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        $uc_award_json = file_get_contents('json/uc_award.json');
        $all_award_array = json_decode($uc_award_json, true);
        usort($all_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });


        if ($current_item_type == "Award" && $last_item_type == "Award") {
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

            file_put_contents('json/uc_award.json', json_encode($all_award_array, JSON_PRETTY_PRINT));
        }

        if ($current_item_type != "Award" && $last_item_type != "Award") {
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

            file_put_contents('json/uc_activity.json', json_encode($all_activity_array, JSON_PRETTY_PRINT));
        }

        if ($current_item_type == "Award" && $last_item_type != "Award") {
            $current_award_index = 0;
            $last_activity_index = 0;
            foreach ($all_award_array as $index => $one_award) {
                if ($one_award["Award_Order"] == $current_order) {
                    $current_award_index = $index;
                }
            }

            foreach ($all_activity_array as $index => $one_activity) {
                if ($one_activity["Activity_Order"] == $last_order) {
                    $last_activity_index = $index;
                }
            }

            $all_award_array[$current_award_index]["Award_Order"] = $last_order;
            $all_activity_array[$last_activity_index]["Activity_Order"] = $current_order;

            usort($all_award_array, function ($a, $b) {
                return intval($a['Award_Order']) - intval($b['Award_Order']);
            });

            file_put_contents('json/uc_award.json', json_encode($all_award_array, JSON_PRETTY_PRINT));

            usort($all_activity_array, function ($a, $b) {
                return intval($a['Activity_Order']) - intval($b['Activity_Order']);
            });

            file_put_contents('json/uc_activity.json', json_encode($all_activity_array, JSON_PRETTY_PRINT));
        }


        if ($current_item_type != "Award" && $last_item_type == "Award") {
            $current_activity_index = 0;
            $last_award_index = 0;
            foreach ($all_activity_array as $index => $one_activity) {
                if ($one_activity["Activity_Order"] == $current_order) {
                    $current_activity_index = $index;
                }
            }

            foreach ($all_award_array as $index => $one_award) {
                if ($one_award["Award_Order"] == $last_order) {
                    $last_award_index = $index;
                }
            }

            $all_award_array[$last_award_index]["Award_Order"] = $current_order;
            $all_activity_array[$current_activity_index]["Activity_Order"] = $last_order;

            usort($all_award_array, function ($a, $b) {
                return intval($a['Award_Order']) - intval($b['Award_Order']);
            });

            file_put_contents('json/uc_award.json', json_encode($all_award_array, JSON_PRETTY_PRINT));

            usort($all_activity_array, function ($a, $b) {
                return intval($a['Activity_Order']) - intval($b['Activity_Order']);
            });

            file_put_contents('json/uc_activity.json', json_encode($all_activity_array, JSON_PRETTY_PRINT));
        }


    }


    public static function MovingDown($current_order)
    {
        $uc_display_list = self::GetDisplayList();
        $current_item_type = "";
        $next_item_type = "";

        $next_order = 0;
        foreach ($uc_display_list as $index => $one_item) {
            if ($one_item["Item_Order"] == $current_order) {
                $current_item_type = $uc_display_list[$index]["Item_Type"];
                $next_item_type = $uc_display_list[$index + 1]["Item_Type"];
                $next_order = $uc_display_list[$index + 1]["Item_Order"];
            }
        }

        $uc_activity_json = file_get_contents('json/uc_activity.json');
        $all_activity_array = json_decode($uc_activity_json, true);
        usort($all_activity_array, function ($a, $b) {
            return intval($a['Activity_Order']) - intval($b['Activity_Order']);
        });

        $uc_award_json = file_get_contents('json/uc_award.json');
        $all_award_array = json_decode($uc_award_json, true);
        usort($all_award_array, function ($a, $b) {
            return intval($a['Award_Order']) - intval($b['Award_Order']);
        });


        if ($current_item_type == "Award" && $next_item_type == "Award") {
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

            file_put_contents('json/uc_award.json', json_encode($all_award_array, JSON_PRETTY_PRINT));
        }

        if ($current_item_type != "Award" && $next_item_type != "Award") {
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

            file_put_contents('json/uc_activity.json', json_encode($all_activity_array, JSON_PRETTY_PRINT));
        }

        if ($current_item_type == "Award" && $next_item_type != "Award") {
            $current_award_index = 0;
            $next_activity_index = 0;
            foreach ($all_award_array as $index => $one_award) {
                if ($one_award["Award_Order"] == $current_order) {
                    $current_award_index = $index;
                }
            }

            foreach ($all_activity_array as $index => $one_activity) {
                if ($one_activity["Activity_Order"] == $next_order) {
                    $next_activity_index = $index;
                }
            }

            $all_award_array[$current_award_index]["Award_Order"] = $next_order;
            $all_activity_array[$next_activity_index]["Activity_Order"] = $current_order;

            usort($all_award_array, function ($a, $b) {
                return intval($a['Award_Order']) - intval($b['Award_Order']);
            });

            file_put_contents('json/uc_award.json', json_encode($all_award_array, JSON_PRETTY_PRINT));

            usort($all_activity_array, function ($a, $b) {
                return intval($a['Activity_Order']) - intval($b['Activity_Order']);
            });

            file_put_contents('json/uc_activity.json', json_encode($all_activity_array, JSON_PRETTY_PRINT));
        }


        if ($current_item_type != "Award" && $next_item_type == "Award") {
            $current_activity_index = 0;
            $next_award_index = 0;
            foreach ($all_activity_array as $index => $one_activity) {
                if ($one_activity["Activity_Order"] == $current_order) {
                    $current_activity_index = $index;
                }
            }

            foreach ($all_award_array as $index => $one_award) {
                if ($one_award["Award_Order"] == $next_order) {
                    $next_award_index = $index;
                }
            }

            $all_award_array[$next_award_index]["Award_Order"] = $current_order;
            $all_activity_array[$current_activity_index]["Activity_Order"] = $next_order;

            usort($all_award_array, function ($a, $b) {
                return intval($a['Award_Order']) - intval($b['Award_Order']);
            });

            file_put_contents('json/uc_award.json', json_encode($all_award_array, JSON_PRETTY_PRINT));

            usort($all_activity_array, function ($a, $b) {
                return intval($a['Activity_Order']) - intval($b['Activity_Order']);
            });

            file_put_contents('json/uc_activity.json', json_encode($all_activity_array, JSON_PRETTY_PRINT));
        }
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
        $title = "UC Application Award and Activity Report. Generated on " . date('m/d/Y');
        $pdf->Cell(0, 10, $title, 0, 1);
        $pdf->Cell(0, 10, '', 0, 1);

        $dictionary = [];
        $current_user_uc_award_array = self::GetUCAwardArrayForCurrentUser();
        $current_user_uc_activity_array = self::GetUCActivityForCurrentUser();
        foreach ($current_user_uc_award_array as $one_award) {
            $dictionary[$one_award['Award_Order']] = "Award";
        }
        foreach ($current_user_uc_activity_array as $one_activity) {
            $dictionary[$one_activity['Activity_Order']] = "Activity";
        }
        ksort($dictionary);

        $display_id = 1;
        foreach ($dictionary as $cur_order => $one_item) {
            $type = $dictionary[$cur_order];
            if ($type == "Award") {
                $cur_award = self::Get_UC_Award_Obj_By_Order($cur_order);
                $award_name = task::get_taskname_bytaskid($cur_award['Task_Id']);

                $pdf->SetFont('Arial', '', 12);
                $pdf->SetTextColor(0, 0, 255);
                $pdf->Cell(0, 10, $display_id . '. ' . $award_name, 0, 1);

                $pdf->SetFont('Arial', '', 12);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->MultiCell(0, 10, 'What is the name of the award or honor ? ' . $cur_award['Award_UC_Name'], 0, 1);
                $pdf->Cell(0, 10, 'Level of recognition : ' . $cur_award['Award_Level_Of_recognition'], 0, 1);
                $pdf->Cell(0, 10, 'Type of award or honor : ' . $cur_award['Award_Type'], 0, 1);
                $pdf->Cell(0, 10, 'When did you receive it ? ' . $cur_award['Award_Grade'], 0, 1);
                $pdf->MultiCell(0, 10, 'What are the eligibility requirements for this award or honor ? ' . $cur_award['Award_Eligibility'], 0, 1);
                $pdf->MultiCell(0, 10, 'What did you do to achieve this award or honor ? ' . $cur_award['Award_Desc'], 0, 1);

            } else {
                $cur_activity = self::Get_UC_Activity_Obj_By_Order($cur_order);
                $activity_name = self::get_activityname_byactivityid($cur_activity['Activity_Id']);
                $uc_type = $cur_activity['UC_Type'];

                $pdf->SetFont('Arial', '', 12);
                $pdf->SetTextColor(0, 0, 255);
                $pdf->Cell(0, 10, $display_id . '. ' . $activity_name . ' - ' . $uc_type, 0, 1);

                $pdf->SetFont('Arial', '', 12);
                $pdf->SetTextColor(0, 0, 0);
                if ($uc_type == "Educational Program") {
                    $pdf->MultiCell(0, 10, 'Program Name : ' . $cur_activity['Organization_Program_Course_Name'], 0, 1);
                    $pdf->MultiCell(0, 10, 'Briefly describe the program : ' . $cur_activity['Desc'], 0, 1);
                    $pdf->Cell(0, 10, 'When did you participate in the program : ' . $cur_activity['Grade'], 0, 1);
                    $pdf->Cell(0, 10, 'How much time did you spend in the program ? Hours per week : ' . $cur_activity['Hours_Spent_Per_Week'] . ' Weeks per year : ' . $cur_activity['Weeks_Spent_Per_Year'], 0, 1);
                }

                if ($uc_type == "Extracurricular Activity") {
                    $pdf->MultiCell(0, 10, 'Program Name :  ' . $cur_activity['Organization_Program_Course_Name'], 0, 1);
                    $pdf->MultiCell(0, 10, 'Briefly describe the program : ' . $cur_activity['Desc'], 0, 1);
                    $pdf->Cell(0, 10, 'When did you participate in the program ? ' . $cur_activity['Grade'], 0, 1);
                    $pdf->Cell(0, 10, 'How much time did you spend in the program ? Hours per week : ' . $cur_activity['Hours_Spent_Per_Week'] . ' Weeks per year : ' . $cur_activity['Weeks_Spent_Per_Year'], 0, 1);
                }

                if ($uc_type == "Other Coursework") {
                    $pdf->MultiCell(0, 10, 'What was the course name?  ' . $cur_activity['Organization_Program_Course_Name'], 0, 1);
                    $pdf->MultiCell(0, 10, 'Briefly describe the course : ' . $cur_activity['Desc'], 0, 1);
                    $pdf->Cell(0, 10, 'When did you take this course ? ' . $cur_activity['Grade'], 0, 1);
                    $pdf->Cell(0, 10, 'How much time did you spend in class? ? Hours per week : ' . $cur_activity['Hours_Spent_Per_Week'] . ' Weeks per year : ' . $cur_activity['Weeks_Spent_Per_Year'], 0, 1);
                }

                if ($uc_type == "Working Experience") {
                    $pdf->MultiCell(0, 10, 'Where did you work ? ' . $cur_activity['Working_Place'], 0, 1);
                    $pdf->MultiCell(0, 10, 'Please briefly describe the company or organization where you worked : ' . $cur_activity['Desc'], 0, 1);
                    $pdf->MultiCell(0, 10, 'What was your job title : ' . $cur_activity['Job_Title'], 0, 1);
                    $pdf->MultiCell(0, 10, 'What were your job responsibilities ? ' . $cur_activity['Job_Responsibility'], 0, 1);
                    $pdf->Cell(0, 10, 'When did you work at this job ? ' . $cur_activity['Grade'], 0, 1);
                    $pdf->Cell(0, 10, 'Do you still work at this job ? ' . $cur_activity['Still_Work'], 0, 1);
                }

                if ($uc_type == "Volunteer") {
                    $pdf->MultiCell(0, 10, 'What is the name of the organization, program, school or group you volunteered for ? ' . $cur_activity['Organization_Program_Course_Name'], 0, 1);
                    $pdf->MultiCell(0, 10, 'Please describe the organization, program, school or group : ' . $cur_activity['Desc'], 0, 1);
                    $pdf->MultiCell(0, 10, 'What did you do ? ' . $cur_activity['What_You_Did'], 0, 1);
                    $pdf->Cell(0, 10, 'When did you volunteer ? ' . $cur_activity['Grade'], 0, 1);
                    $pdf->Cell(0, 10, 'How much time did you spend volunteering ? Hours per week :' . $cur_activity['Hours_Spent_Per_Week'] . ' Weeks per year : ' . $cur_activity['Weeks_Spent_Per_Year'], 0, 1);
                }
            }
            $display_id++;
            if ($display_id <= count($dictionary)) {
                $pdf->Cell(0, 10, '', 0, 1);
            }
        }

        $filePath = 'pdf/ucapp_' . date('YmdHis') . '.pdf';

        $pdf->Output('F', $filePath);

        if (file_exists($filePath)) {
            echo "<script>window.open('$filePath', '_blank');</script>";
        } else {
            echo "Failed to save PDF.";
        }
    }

    public static function Get_UC_Award_Obj_By_Order($award_order)
    {
        $current_user_uc_award_array = self::GetUCAwardArrayForCurrentUser();
        $current_obj = '';
        foreach ($current_user_uc_award_array as $index => $one_award) {
            if ($one_award['Award_Order'] == $award_order) {
                $current_obj = $current_user_uc_award_array[$index];
            }
        }
        return $current_obj;
    }

    public static function Get_UC_Activity_Obj_By_Order($activity_order)
    {
        $current_user_uc_activity_array = self::GetUCActivityForCurrentUser();
        $current_obj = '';
        foreach ($current_user_uc_activity_array as $index => $one_activity) {
            if ($one_activity['Activity_Order'] == $activity_order) {
                $current_obj = $current_user_uc_activity_array[$index];
            }
        }
        return $current_obj;
    }
}
