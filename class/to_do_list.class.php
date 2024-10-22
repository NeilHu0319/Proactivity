<?php
class to_do_list
{
    public static function exportPDF()
    {
        require('fpdf186/fpdf.php');

        $pdf = new FPDF();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'This is Arial Bold 16', 0, 1);

        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, 'This is Arial Italic 12', 0, 1);

        $pdf->SetFont('Times', 'B', 14);
        $pdf->Cell(0, 10, 'This is Times Bold 14', 0, 1);

        $pdf->SetFont('Courier', 'U', 12);
        $pdf->Cell(0, 10, 'This is Courier Underlined 12aa', 0, 1);

        $filePath = 'pdf/todolist_' . date('YmdHis') . '.pdf';

        $pdf->Output('F', $filePath);

        if (file_exists($filePath)) {
            echo "<script>window.open('$filePath', '_blank');</script>";
        } else {
            echo "Failed to save PDF.";
        }
    }
}
