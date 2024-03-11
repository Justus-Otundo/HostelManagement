<?php
    // Include the FPDF library
    require('fpdf.php');

     
    // Create a new PDF instance
    class MyPDF extends FPDF {
        // Override the Header method to set the colored margin
        function Header() {
            $this->SetFillColor(255, 0, 0); // Set the fill color to red
            $this->Rect(0, 0, $this->GetPageWidth(), 10, 'F'); // Draw a filled rectangle for the margin
        }
    }
    
    
    // Create a new PDF instance
    $pdf = new FPDF();
    
    // Add a page to the PDF
    $pdf->AddPage();
    
    // Set the font for the document
    $pdf->SetFont('Arial', 'B', 16);
    
    // Add content to the PDF
    $pdf->Cell(0, 10, 'View Student Accounts Report', 0, 1, 'C');
    
    // Include the necessary files and perform necessary database queries
    include('../includes/dbconn.php');
    $query = "SELECT * FROM userregistration";
    $result = mysqli_query($mysqli, $query);
    
    // Generate the report table
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 10, 'regNo', 1);
    $pdf->Cell(60, 10, 'firstName', 1);
    $pdf->Cell(60, 10, 'gender', 1);
    $pdf->Cell(30, 10, 'contactNo', 1);
    $pdf->Cell(60, 10, 'email', 1);
    $pdf->Ln();
    
    // Loop through the query results and populate the table rows
    while($row = mysqli_fetch_assoc($result)) {
        $pdf->Cell(40, 10, $row['regNo'], 1);
        $pdf->Cell(60, 10, $row['firstName'], 1);
        $pdf->Cell(60, 10, $row['gender'], 1);
        $pdf->Cell(30, 10, $row['contactNo'], 1);
        $pdf->Cell(60, 10, $row['email'], 1);
        $pdf->Ln();
    }
    
    // Output the PDF as a file (download) as an inline content
    $pdf->Output('view_student_accounts_report.pdf', 'I');
?>