<?php
namespace model;
require('fpdf/html_table.php');
class PDF extends HPDF{
    var $heading,//Heading shown at the top of the document
        $title;//title of the document
    function Header(){
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(40,10,$this->heading);
        $this->Ln(20);
    }
    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->AliasNbPages();
        $this->Cell(0,10,$this->PageNo(),0,0,'C');
    }

    function infoRow($w, $c1, $c2){
        $oy=$this->GetY();
        $ox=$this->GetX();
        $this->Cell($w, 4, $c1, 0, 1);
        $this->SetXY($ox+$w, $oy);
        $this->Cell($w, 4, $c2, 0, 1);
    }

    function setTitlePDF($title){
        $this->SetTitle($title);
        $title=str_replace(' ', '_', $title);
        $title=str_replace('_-_', '-', $title);
        $this->title=$title.'.pdf';
    }
}
?>
