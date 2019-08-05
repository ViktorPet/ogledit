<?php

namespace Application\Helper;

/**
 * Layout helper for logged user details.
 */
class PdfHelper extends FpdfHelper {

    // Page header
    function Header() {
        // Logo
        $this->Image(FPDF_IMGPATH . '/logo_ogledi.png', 10, 10, 40);
        $this->SetFont('DejaVu', '', 18);
        // Move to the right       
        if ($this->DefOrientation == 'L') {
            $this->Cell(123);
        } else {
            $this->Cell(80);
        }
        $result = $this->GetTitle();
        // Title
        $this->Cell(30, 10, $result, 0, 0, 'C');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer() {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('DejaVu', '', 8);
        // Page number
        $this->Cell(0, 10, 'Страница ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Load data
    function LoadData($file) {
        // Read file lines
        $lines = file($file);
        $data = array();
        foreach ($lines as $line)
            $data[] = explode(';', trim($line));
        return $data;
    }

    // Colored table
    function FancyTable($header, $data, $w = []) {
        // if not set the width of a cell
        $countW = count($w);
        $countHeader = count($header);
        if ($countW < $countHeader) {
            for ($i = $countW; $i < $countHeader; $i++) {
                $w[$i] = 20;
            }
        }

        // Colors, line width and bold font
        $this->SetFillColor(124, 121, 132);
        $this->SetTextColor(255);
        $this->SetLineWidth(.3);
        // Header
        for ($i = 0; $i < count($header); $i++)
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(219, 215, 229);
        $this->SetTextColor(0);
        // Data
        $fill = false;
        foreach ($data as $row) {
            $i = 0;
            foreach ($row as $value) {
                $this->Cell($w[$i++], 10, $value, 'LR', 0, 'C', $fill);
            }
            $this->Ln();
            $fill = !$fill;
        }
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}