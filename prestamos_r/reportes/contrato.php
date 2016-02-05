<?php
include("conexion/conexion.php");
require_once('tcpdf/tcpdf.php');
//$idSolicitud =$_REQUEST['id'];

//require_once('tcpdf/tcpdf.php');
/*$consultar=mysql_query("SELECT s.id_solicitud as solicitud,s.titular as titular ,s.antiguedad as antiguedad,s.creada AS creada,t.numero AS num_tit, t.nombre AS titular, st.cve_sindicato AS tit_cve_sind, st.sindicato AS tit_sind, TIMESTAMPDIFF(YEAR, t.fec_ingre, CURDATE()) AS tit_ant,
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
}*/
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
$pdf->SetFont("dejavusans", "", 8);
// add a page
$pdf->AddPage();

//-------------------------------------------------------------------------------------------------------------------------
$tb0 = <<<EOD
<table width="665" border="0">
  <tr>
    <td width="659"><div align="justify">CONTRATO DE PRESTAMO A CORTO PLAZO QUE CELEBRAN POR UNA PARTE EL C. {1titular}- {2nombreTitular} Y POR OTRA PARTE LA DIRECCION DE PENSIONES DEL MUNICIPIO DE OAXACA DE JUAREZ, REPRESENTADA POR LA C. {3nombreDirectora} EN SU CARACTER DE DIRECTORA GENERAL EL CUAL SE SUJETA AL TENOR DE LAS SIGUIENTES</div></td>
  </tr>
  <tr>
    <td><div align="right">Contrato No. VarIdContrato.</div></td>
  </tr>
  <tr>
    <td><div align="center">D  E  C  L  A  R  A  C  I  O  N  E  S :</div></td>
  </tr>
  <tr>
    <td><div align="center"></div></td>
  </tr>
  <tr>
    <td><p align="justify">I. EL C. {VarNombreTitular} con domicilio en {VarDireccionTitular} {VarAntiguedad} , agremiado al sindicato {4sindicato} quien presta sus servicios en la {5dependencia} solicita a la Dirección de Pensiones del Municipio de Oaxaca de Juárez, Oax. se le conceda un préstamo por la cantidad de {VarImporte} ( {VarIimporteLetra} )</p>    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>II.- La Dirección de Pensiones del Municipio de Oaxaca de Juárez, Oax., está de acuerdo en conceder dicho préstamo el cuál se sujeta al tenor de las siguientes</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="center">C   L   A  U  S   U   L   A  S  :</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="justify">PRIMERA.- La Dirección de Pensiones del Municipio de Oaxaca de Juárez, Oax. se obliga expresamente a conceder al  C. {VarImporbreTitular} un préstamo por la cantidad de {VarImporte} ( {VarImporteLetra} ) con un interes del  {VarPrcentajeIntereses} % mensual.</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>SEGUNDA.- El C. {VarNombreTitular} se compromete exprésamente a devolver la cantidad prestada en un periodo de {VarpQuincenas} quincenas.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="justify">TERCERA.- El C. {VarNombreTitular} faculta a la Dirección de Pensiones del Municipio de Oaxaca de Juárez, Oax., para que a través de la Tesorería Municipal, le sea descontada en forma quincenal la cantidad de {VardescuentoQuincenal} ( {VardqLetra} ) a través de la {Varur}.</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>CUARTA.- El mutuario se obliga a garantizar dicho préstamo con sus aportaciones y las de los avales cuyos datos se detallan al reverso.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="justify">QUINTA.- Para garantizar el pago de la cantidad mutuada se constituyen como avales los C.C. {Varaval1}- {VarnombreAval1}  y  {Varaval2}- {VarnombreAval2}  cuya responsabilidad fenecerá hasta el día en que se cubra la totalidad del adeudo, renunciando expresamente a los beneficios de orden y excusión, obligándose las partes para que los descuentos efectuados al fiador, por baja temporal o definitiva del deudor, no sean reembolsados.</div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>SEXTA.- Por el total del capital prestado el deudor ha suscrito un pagaré unico que le será entregado a la liquidación total del préstamo.</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><div align="justify">SEPTIMA.- Si por cualquier razón imputable a la Dirección de Pensiones, no se realizara el descuento correspondiente a este préstamo, me comprometo informar inmediatamente a la Dirección de Pensiones para que se efectúe de conformidad con lo estipulado en este contrato.</div></td>
  </tr>

</table>
EOD;
$pdf->writeHTML($tb0, true, false, false, false, '');
//-------------------------------------------------------------------------------------------------------------------------
$tb1 = <<<EOD
<table width="643" border="0" align="center">
  <tr>
    <td colspan="2"><div align="center">A  V  A  L     1</div></td>
    <td colspan="2"><div align="center">A  V  A  L     2</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">VarnombreAval1</div></td>
    <td colspan="2"><div align="left">Varnombreaval2</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left"><span class="Estilo1">Nombre Completo</span></div></td>
    <td colspan="2"><div align="left"><span class="Estilo1">Nombre Completo</span></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">VardireccionAval1</div></td>
    <td colspan="2"><div align="left">VardireccionAval2</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left"><span class="Estilo1">Domicilio Particular</span></div></td>
    <td colspan="2"><div align="left"><span class="Estilo1">Domicilio Particular</span></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">VardependeciaAval1</div></td>
    <td colspan="2"><div align="left">VardependeciaAval2</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left"><span class="Estilo1">Dependencia donde presta servicios</span></div></td>
    <td colspan="2"><div align="left"><span class="Estilo1">Dependencia donde presta servicios</span></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">VarcategoriaAval1</div></td>
    <td colspan="2"><div align="left">VarcategoriaAval2</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left"><span class="Estilo1">Empleo que desempeña</span></div></td>
    <td colspan="2"><div align="left"><span class="Estilo1">Empleo que desempeña</span></div></td>
  </tr>
  <tr>
    <td width="166"><div align="left">VarAval1 </div></td>
    <td width="163"><div align="left">VarantiguedadAval1 años</div></td>
    <td width="158"><div align="left">VarAval1 </div></td>
    <td width="166"><div align="left">VarantiguedadAval2 años</div></td>
  </tr>
  <tr>
    <td><div align="left"><span class="Estilo1">Numero ùnico </span></div></td>
    <td><div align="left"><span class="Estilo1">Antiguedad en el servicio</span></div></td>
    <td><div align="left"><span class="Estilo1">Numero ùnico </span></div></td>
    <td><div align="left"><span class="Estilo1">Antiguedad en el servicio</span></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left">VarsindicatoAval1</div></td>
    <td colspan="2"><div align="left">VarsindicatoAval2</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left"><span class="Estilo1">Sindicato al que pertenece</span></div></td>
    <td colspan="2"><div align="left"><span class="Estilo1">Sindicato al que pertenece</span></div></td>
  </tr>
  <tr>
    <td colspan="2"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">Firma del Aval</div></td>
    <td colspan="2"><div align="center">Firma del Aval</div></td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb1, true, false, false, false, '');
//---------------------------------------------------------------------------------------------------------
$tb2 = <<<EOD
<table width="677" border="0" align="center">
    <tr>
      <td width="158">&nbsp;</td>
      <td width="158"><div align="left">Importe del préstamo</div></td>
      <td width="168"><div align="left">Varimporte</div></td>
      <td width="175">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="left">Intereses</div></td>
      <td><div align="left">Varinterese</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="left">Subtotal</div></td>
      <td><div align="left">Varsubtotal</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="left">Saldo Préstamo Anterior</div></td>
      <td><div align="left">Varsaldoanterior</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="left">Importe del cheque</div></td>
      <td><div align="left">VarImportecheque</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="left">Descuento Quincenal</div></td>
      <td><div align="left">VardescQuincena</div></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>iquido cheque Nº.</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><div align="left"></div></td>
      <td>&nbsp;</td>
      <td><div align="left"></div></td>
    </tr>
  </table>
EOD;
$pdf->writeHTML($tb2, true, false, false, false, '');

//----------------------------------------------------------------------------------------------------------------
$tb3 = <<<EOD
<table width="564" border="0" align="center">
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Recibí cheque y copia del ________________________________________________</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2">Oaxaca de Juárez, Oax. a ______  de _________________________  de ___________</td>
  </tr>
  <tr>
    <td colspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td width="255"><div align="center">Acreditado</div></td>
    <td width="293"><div align="center">Directora de Pensiones</div></td>
  </tr>
  <tr>
    <td><p align="center">&nbsp;</p>
    <p align="center">________________________________</p></td>
    <td><p align="center">&nbsp;</p>
    <p align="center">____________________________________</p></td>
  </tr>
  <tr>
    <td><div align="center">VarNombreTtitular</div></td>
    <td><div align="center">VarNombreDirectora</div></td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb3, true, false, false, false, '');
//Close and output PDF document
$pdf->Output('contrato.pdf', 'I');