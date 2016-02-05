SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

}
$dimensiones=array (215.9,355.6);
// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $dimensiones, true, 'UTF-8', false);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) 
	{
		require_once(dirname(__FILE__).'/lang/eng.php');
		$pdf->setLanguageArray($l);
	}
// ---------------------------------------------------------------------------------------------
$pdf->SetFont("dejavusans", "", 7);
// add a page
$pdf->AddPage();
/*$html =  <<<EOD
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
$tbl = <<<EOD
<table border="0" cellpadding="0" cellspacing="0" nobr="true">
 <tr>
  <th colspan="3" align="right">FOLIO: $usuaImpre</th>
 </tr>
<tr>
  <th colspan="3" align="rignt">FECHA DE EXPEDIC$html =  <<<EOD
th>
 </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" nobr="true">
 <tr>
  <th colspan="3" align="left"><strong>CONTRIBUYENTE: </strong> $nombreC</th>
 </tr>
<tr>
  <th colspan="3" align="left"><strong>DOMICILIO FISCAL: </strong> $domicilioC</th>
 </tr>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');

$html = <<<EOD
<h1>Welcome to <a href="http://www.tcpdf.org" style="text-decoration:none;background-color:#CC0000;color:black;">&nbsp;<span style="color:black;">TC</span><span style="color:white;">PDF</span>&nbsp;</a>!</h1>
<i>This$pdf->writeHTML($tbl, true, false, false, false, '');
