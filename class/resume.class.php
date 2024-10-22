<?php
class resume
{
    public static function export_resume($name, $address, $phone, $email, $school)
    {
        require('fpdf186/fpdf.php');

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetMargins(20, 20, 20);
        //$pdf->SetAutoPageBreak(true, 20);

        $pdf->SetDrawColor(0, 51, 102);
        $pdf->SetLineWidth(0.5);

        $pdf->SetFont('Arial', 'B', 20);
        $pdf->SetTextColor(0, 51, 102);
        $pdf->Cell(0, 10, $name, 0, 1, 'C');

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(0, 10, $address, 0, 1, 'C');
        $pdf->Cell(0, 10, $phone . ' | ' . $email, 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor(230, 230, 250);
        $pdf->Cell(0, 10, 'Education', 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(2);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, $school, 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'High School Diploma', 0, 1);
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor(230, 230, 250);
        $pdf->Cell(0, 10, 'Award', 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(2);

        include "class/activitydetail.class.php";
        $current_user_award_task_id_array = task::get_award_task_id_array_forcurrentuser();
        $current_user_activity_id_array = task::get_activityidarray_forcurrentuser();

        $TaskData = file_get_contents('json/activitydetail.json');
        $task_array = json_decode($TaskData, true);
        usort($task_array, function ($a, $b) {
            $dateA = new DateTime($a['date_chosen']);
            $dateB = new DateTime($b['date_chosen']);
            return $dateA <=> $dateB;
        });

        foreach ($task_array as $one_task) {
            if (in_array($one_task['task_id'], $current_user_award_task_id_array)) {
                if ($pdf->GetY() + 10 > $pdf->GetPageHeight() - 20) {
                    $pdf->AddPage();
                }

                $pdf->Cell(5);
                $pdf->Cell(150, 10, '- ' . $one_task['name'], 0, 0, 'L');
                $pdf->Cell(0, 10, $one_task['date_chosen'], 0, 1, 'R');
            }
        }

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetFillColor(230, 230, 250);
        $pdf->Cell(0, 10, 'Experience', 0, 1, 'L', true);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Ln(2);

        $jsonData = file_get_contents('json/activities.json');
        $activities = json_decode($jsonData, true);
        foreach ($activities as $one_activity) {
            if (in_array($one_activity['activity_id'], $current_user_activity_id_array)) {
                if ($pdf->GetY() + 20 > $pdf->GetPageHeight() - 20) {
                    $pdf->AddPage();
                }

                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, $one_activity['name'], 0, 1);
                $pdf->SetFont('Arial', '', 10);
                $pdf->MultiCell(0, 6, $one_activity['notes']);
                $pdf->Ln(2);
            }
        }

        //$pdf->SetY(-20);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Cell(0, 10, 'Page ' . $pdf->PageNo(), 0, 0, 'C');

        $pdf->Output('I', 'resume.pdf');

        //$filePath = 'pdf/resume_' . date('YmdHis') . '.pdf';

        //$pdf->Output('F', $filePath);

        //if (file_exists($filePath)) {
        //    echo "<script>window.open('$filePath', '_blank');</script>";
        //} else {
        //    echo "Failed to save PDF.";
        //}

    }
}

