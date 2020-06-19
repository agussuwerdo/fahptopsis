<?php
class Mypdf {
	// Page header
	var $params_header = array();
	
    function __construct() {
        include_once APPPATH . '/libraries/Fpdf.php';
    }
	
	function Header()
	{
		// Logo
		$image_logo = $this->Image(''.base_url().'image/'.get_myconf('img_logo_name'),10,6,30);
		// Arial bold 15
		$this->SetFont('Arial','B',15);
		
		$text_left = isset($this->params_header['text_left']) ? $this->params_header['text_left'] : '';
		$text_center = isset($this->params_header['text_center']) ? $this->params_header['text_center'] : '';
		$text_right = isset($this->params_header['text_right']) ? $this->params_header['text_right'] : '';
		
        if($text_left!='')
		{
			$this->Cell(0,10,$text_left,0,0,'L');
			$this->SetX($this->lMargin);
		}
        if($text_center!='')
		{
			$this->Cell(0,10,$text_center,0,0,'C');
			$this->SetX($this->lMargin);
		}
        if($text_right!='')
		{
			$this->Cell( 0, 10, $text_right, 0, 0, 'R' ); 
		}
		// $this->Cell(80);
		// Title
		// $this->Cell(30,10,$this->title,1,0,'C');
		// Line break
		$this->Ln(10);
	}

	// Page footer
	function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-15);
		// Arial italic 8
		$this->SetFont('Arial','I',8);
		// Page number
		$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
	}
	
	function set_params_header($params)
	{
		$this->params_header = $params;
	}
}
?>