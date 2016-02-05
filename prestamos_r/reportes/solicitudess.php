<?php
include("conexion/conexion.php");
require_once('tcpdf/tcpdf.php');
$idSolicitud =$_REQUEST['id'];

//require_once('tcpdf/tcpdf.php');
$consultar=mysql_query("SELECT s.id_solicitud as solicitud,s.titular as titular ,s.antiguedad as antiguedad,s.creada AS creada,t.numero AS num_tit, t.nombre AS titular, st.cve_sindicato AS tit_cve_sind, st.sindicato AS tit_sind, TIMESTAMPDIFF(YEAR, t.fec_ingre, CURDATE()) AS tit_ant,
			s.aval1, a1.nombre AS aval1_n, sa1.cve_sindicato AS aval1_cve_sind, sa1.sindicato AS aval1_sind, TIMESTAMPDIFF(YEAR, a1.fec_ingre, CURDATE()) AS aval1_ant,
			s.aval2, a2.nombre AS aval2_n, sa2.cve_sindicato AS aval2_cve_sind, sa2.sindicato AS aval2_sind, TIMESTAMPDIFF(YEAR, a2.fec_ingre, CURDATE()) AS aval2_ant,
			firma, importe, plazo, tasa, saldo_anterior, descuento
-- FROM contrato c
FROM Solicitud s -- ON s.id_solicitud = c.id_solicitud
LEFT JOIN sujetos AS t ON t.numero = s.titular
LEFT JOIN catsindicatos st ON st.cve_sindicato = s.cve_sindicato
LEFT JOIN sujetos AS a1 ON a1.numero= s.aval1
LEFT JOIN catsindicatos sa1 ON sa1.cve_sindicato = s.cve_sind_Aval1
LEFT JOIN sujetos AS a2 ON a2.numero = s.aval2
LEFT JOIN catsindicatos sa2 ON sa2.cve_sindicato = s.cve_sind_Aval2
WHERE s.id_solicitud = (SELECT MAX(id_solicitud) AS id_solicitud FROM Solicitud WHERE  titular = $idSolicitud)");
while($rows=mysql_fetch_array($consultar))
{
	$VarSolicitante=$rows['solicitud'];
	$Solicitud=$rows['titular'];
	$VarFechadeIngreso=$rows['creada'];
	$VarAntiguedad=$rows['antiguedad'];
	$VarSindicato=$rows['tit_sind'];
	$VarAval1=$rows['aval1_n'];
	$VarAval2=$rows['aval2_n'];
	$VarPlazo=$rows['plazo'];
	$VarImporte=$rows['importe'];
}
// Extend the TCPDF class to create custom Header and Footer  
class MYPDF extends TCPDF {

    //Page header
    public function Header() 
	{
    
		
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
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
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------- datos de la ubicacion-----------------------------------http://www.tcpdf.org/examples/example_048.phps

// set font
$pdf->SetFont("dejavusans", "", 12);
// add a page
$pdf->AddPage();

$html = <<<EOD
<p align="center"><strong>DIRECCION DE PENSIONES.</strong><br />
    <strong>DEL MUNICIPIO DE OAXACA DE JUÁREZ OAX..</strong><br />
    <strong>SOLICITUD DE PRESTAMO</strong></p>
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
//-------------------------------------------------------------------------------------------------------------------------
$tb0 = <<<EOD
<table width="637" border="0">
  <tr>
    <td width="279">&nbsp;</td>
    <td width="110">&nbsp;</td>
    <td width="45">&nbsp;</td>
    <td width="72">Número:</td>
    <td width="109">$VarSolicitante</td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb0, true, false, false, false, '');
//-------------------------------------------------------------------------------------------------------------------------
$tbl = <<<EOD
<table width="630" border="0">
  <tr>
    <td width="178">&nbsp;</td>
    <td width="151">&nbsp;</td>
    <td width="141">&nbsp;</td>
    <td width="132">&nbsp;</td>
  </tr>
  <tr>
    <td><span class="Estilo3">Solicitante:</span></td>
    <td><span class="Estilo3">$Solicitud</span></td>
    <td><span class="Estilo3"></span></td>
    <td><span class="Estilo3"></span></td>
  </tr>
  
  <tr>
    <td><span class="Estilo3">Fecha de Ingreso:</span></td>
    <td><span class="Estilo3">$VarFechadeIngreso</span></td>
    <td valign="top"><p class="Estilo3">Antiguedad:</p></td>
    <td valign="top"><p class="Estilo3">$VarAntiguedad</p></td>
  </tr>
  <tr>
    <td><span class="Estilo3">Sindicato:</span></td>
    <td><span class="Estilo3">$VarSindicato</span></td>
    <td valign="top"><p class="Estilo3">Puesto:</p></td>
    <td     <td valign="top"><p class="Estilo3">VarPuesto:</p></td>
</table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, '');

//---------------------------------------------------------------------------------------------
$tb2 = <<<EOD
<table width="630" border="0">
  <tr>
    <td width="180">&nbsp;</td>
    <td width="241">&nbsp;</td>
    <td width="55">&nbsp;</td>
    <td width="136">&nbsp;</td>
  </tr>
  <tr>
    <td width="180" valign="top"><p class="Estilo3">Aval 1:</p></td>
    <td><span class="Estilo3">$VarAval1</span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb2, true, false, false, false, '');

//---------------------------------------------------------------------------------------------
$tb3 = <<<EOD
<table width="630" border="0">
  <tr>
    <td width="180">&nbsp;</td>
    <td width="241">&nbsp;</td>
    <td width="55">&nbsp;</td>
    <td width="136">&nbsp;</td>
  </tr>
  <tr>
    <td width="180" valign="top"><p class="Estilo3">Aval 2:</p></td>
    <td><span class="Estilo3">$VarAval2</span></td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb3, true, false, false, false, '');

//---------------------------------------------------------------------------------------------
$tb4 = <<<EOD
<table width="639" border="0">
  <tr>
    <td width="110">Plazo de Pago:</td>
    <td width="114">$VarPlazo</td>
    <td width="98">Meses</td>
    <td width="90"><div align="right">Monto:</div></td>
    <td width="193">$VarImporte</td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb4, true, false, false, false, '');

//---------------------------------------------------------------------------------------------
$tb5 = <<<EOD
<table width="641" border="0">
  <tr>
    <td width="295"><div align="center"></div></td>
    <td width="330"><div align="center">DIRECTORA DE PENSIONES</div></td>
  </tr>
  <tr>
    <td><div align="center"></div></td>
    <td><div align="center"></div></td>
  </tr>
  <tr>
    <td><div align="center">VarNombreFirma1</div></td>
    <td><div align="center">VarNombreFirma2</div></td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb5, true, false, false, false, '');
//Close and output PDF document
$pdf->Output('solicitud.pdf', 'I');