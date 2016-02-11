<?php
include("conexion/conexion.php");
require_once('tcpdf/tcpdf.php');
$idSolicitud = $_REQUEST['id'];
//$idSolicitud = 41166; //$_REQUEST['id'];

//require_once('tcpdf/tcpdf.php');
$consultar=mysql_query("SELECT s.id_solicitud as solicitud,s.titular as titularr ,s.antiguedad as antiguedad,DATE_FORMAT(t.fec_ingre,'%d/%m/%Y') AS creada,t.numero AS num_tit, t.nombre AS titular, st.cve_sindicato AS tit_cve_sind, st.sindicato AS tit_sind, TIMESTAMPDIFF(YEAR, t.fec_ingre, CURDATE()) AS tit_ant,
			aval1, a1.nombre AS aval1_n, sa1.cve_sindicato AS aval1_cve_sind, sa1.sindicato AS aval1_sind, TIMESTAMPDIFF(YEAR, a1.fec_ingre, CURDATE()) AS aval1_ant,
			aval2, a2.nombre AS aval2_n, sa2.cve_sindicato AS aval2_cve_sind, sa2.sindicato AS aval2_sind, TIMESTAMPDIFF(YEAR, a2.fec_ingre, CURDATE()) AS aval2_ant,
			(SELECT representante FROM CATSINDICATOS WHERE cve_sindicato = tit_cve_sind) as SindicatoRpre
			,DATE_FORMAT(s.firma,'%d/%m/%Y') AS firma, importe, plazo, tasa, saldo_anterior, descuento,AntLetraTi
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
	$VarClaveSindicato=$rows['tit_cve_sind'];
	$VarRepreSindicato=$rows['SindicatoRpre'];
	$Solicitud=$rows['titular'];
	$VarSolicitud=$rows['titularr'];
	$VarFechadeIngreso=$rows['creada'];
	$VarAntiguedad=$rows['antiguedad'];
	$VarSindicato=$rows['tit_sind'];
	$VarFirmAvales=$rows['firma'];
	$Aval1=$rows['aval1'];
	$Aval2=$rows['aval2'];
	$VarAval1=$rows['aval1_n'];
	$VarAval2=$rows['aval2_n'];
	$VarPlazo=$rows['plazo'];
	$Importe=$rows['importe'];
	$AntLetra=$rows['AntLetraTi'];
}
$VarImporte = number_format($Importe,2);

$mes = date("n");
$meses = 11 - $mes;

$hoy = date("Y"); 
$consultaDirec=mysql_query("SELECT Nombre_completo FROM cat_director WHERE anio = $hoy");
while($rows=mysql_fetch_array($consultaDirec))
{
	$VarDirector=$rows['Nombre_completo'];
}

$fecha = date('Y/m/j');
$i = strtotime($fecha);
$dia = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)) , 0 ); 
if ($dia >= 4 ){ 
	$nuevafecha = strtotime ( '+4 day' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'j/m/Y' , $nuevafecha );
}else {
	$nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
	$nuevafecha = date ( 'j/m/Y' , $nuevafecha );
}

$VarIimporteLetra = numtoletras($VarImporte);

function numtoletras($xcifra) // convertir numero a letras
{
    $xarray = array(0 => "Cero",
        1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
        "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
        "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
        100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
    );
//
    $xcifra = trim($xcifra);
    $xlength = strlen($xcifra);
    $xpos_punto = strpos($xcifra, ".");
    $xaux_int = $xcifra;
    $xdecimales = "00";
    if (!($xpos_punto === false)) {
        if ($xpos_punto == 0) {
            $xcifra = "0" . $xcifra;
            $xpos_punto = strpos($xcifra, ".");
        }
        $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
        $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
    }
 
    $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
    $xcadena = "";
    for ($xz = 0; $xz < 3; $xz++) {
        $xaux = substr($XAUX, $xz * 6, 6);
        $xi = 0;
        $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
        $xexit = true; // bandera para controlar el ciclo del While
        while ($xexit) {
            if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                break; // termina el ciclo
            }
 
            $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
            $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
            for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                switch ($xy) {
                    case 1: // checa las centenas
                        if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                             
                        } else {
                            $key = (int) substr($xaux, 0, 3);
                            if (TRUE === array_key_exists($key, $xarray)){  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                if (substr($xaux, 0, 3) == 100)
                                    $xcadena = " " . $xcadena . " CIEN " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                            }
                            else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                $key = (int) substr($xaux, 0, 1) * 100;
                                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                $xcadena = " " . $xcadena . " " . $xseek;
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 0, 3) < 100)
                        break;
                    case 2: // checa las decenas (con la misma lógica que las centenas)
                        if (substr($xaux, 1, 2) < 10) {
                             
                        } else {
                            $key = (int) substr($xaux, 1, 2);
                            if (TRUE === array_key_exists($key, $xarray)) {
                                $xseek = $xarray[$key];
                                $xsub = subfijo($xaux);
                                if (substr($xaux, 1, 2) == 20)
                                    $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                                $xy = 3;
                            }
                            else {
                                $key = (int) substr($xaux, 1, 1) * 10;
                                $xseek = $xarray[$key];
                                if (20 == substr($xaux, 1, 1) * 10)
                                    $xcadena = " " . $xcadena . " " . $xseek;
                                else
                                    $xcadena = " " . $xcadena . " " . $xseek . " Y ";
                            } // ENDIF ($xseek)
                        } // ENDIF (substr($xaux, 1, 2) < 10)
                        break;
                    case 3: // checa las unidades
                        if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                             
                        } else {
                            $key = (int) substr($xaux, 2, 1);
                            $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                            $xsub = subfijo($xaux);
                            $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                        } // ENDIF (substr($xaux, 2, 1) < 1)
                        break;
                } // END SWITCH
            } // END FOR
            $xi = $xi + 3;
        } // ENDDO
 
        if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
            $xcadena.= " DE";
 
        if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
            $xcadena.= " DE";
 
        // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
        if (trim($xaux) != "") {
            switch ($xz) {
                case 0:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN BILLON ";
                    else
                        $xcadena.= " BILLONES ";
                    break;
                case 1:
                    if (trim(substr($XAUX, $xz * 6, 6)) == "1")
                        $xcadena.= "UN MILLON ";
                    else
                        $xcadena.= " MILLONES ";
                    break;
                case 2:
                    if ($xcifra < 1) {
                        $xcadena = "CERO PESOS $xdecimales/100 M.N.";
                    }
                    if ($xcifra >= 1 && $xcifra < 2) {
                        $xcadena = "UN PESO $xdecimales/100 M.N. ";
                    }
                    if ($xcifra >= 2) {
                        $xcadena.= " PESOS $xdecimales/100 M.N. "; //
                    }
                    break;
            } // endswitch ($xz)
        } // ENDIF (trim($xaux) != "")
        // ------------------      en este caso, para México se usa esta leyenda     ----------------
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
        $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
        $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
        $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
    } // ENDFOR ($xz)
    return trim($xcadena);
}
 function subfijo($xx)
{ // esta función regresa un subfijo para la cifra
    $xx = trim($xx);
    $xstrlen = strlen($xx);
    if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
        $xsub = "";
    //
    if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
        $xsub = "MIL";
    //
    return $xsub;
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
//$dimensiones=array (215.9,355.6);
// create new PDF document
//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, $dimensiones, true, 'UTF-8', false);
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
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

$html =<<<EOD
<p align="center"><strong>DIRECCION DE PENSIONES.</strong><br />
    <strong>DEL MUNICIPIO DE OAXACA DE JUÁREZ OAX..</strong><br />
    <strong>SOLICITUD DE PRESTAMO</strong></p>
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
//-------------------------------------------------------------------------------------------------------------------------
$pdf->SetFont("dejavusans", "", 10);
$tb0 = <<<EOD
<table width="731" border="0">
  <tr>
    <td width="270">&nbsp;</td>
    <td width="106">&nbsp;</td>
    <td width="43">&nbsp;</td>
    <td width="127"> DE SOLICITUD:</td>
    <td width="163">$VarSolicitante</td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb0, true, false, false, false, '');
//-------------------------------------------------------------------------------------------------------------------------border="0"
$tb2 = <<<EOD
<table width="686"  align="center" border="0">
  <tr>
    <td colspan="4"><div align="left"></div></td>
  </tr>
  <tr>
    <td width="179"><div align="left">  Solicitante:</div></td>
    <td colspan="3"><div align="left"><span class="Estilo3">$VarSolicitud - $Solicitud </span></div></td>
  </tr>
  <tr>
    <td><div align="left">  Fecha de Ingreso:</div></td>
    <td width="141"><div align="left"><span class="Estilo3"> $VarFechadeIngreso </span></div></td>
    <td width="103"><p align="center">Antigüedad: </p>    </td>
    <td width="253"><div align="left"><span class="Estilo3">$VarAntiguedad ($AntLetra)</span></div></td>
  </tr>
  <tr>
    <td><div align="left"> </div></td>
    <td><div align="left"></div></td>
    <td><div align="right"></div></td>
    <td><div align="left"></div></td>
  </tr>
  <tr>
    <td><div align="left">  Sindicato:</div></td>
    <td><div align="left"><span class="Estilo3">$VarSindicato</span></div></td>
    <td><div align="left"></div></td>
    <td><div align="left"></div></td>
  </tr>
  <tr>
    <td colspan="4"> <div align="left">  ____________________________________________________________________________________________________  </div></td>
  </tr>
  <tr>
    <td><div align="left"> Aval:</div></td>
    <td colspan="3"><div align="left"><span class="Estilo3">$Aval1 - $VarAval1</span></div></td>
  </tr>
  <tr>
    <td><div align="left"></div></td>
    <td><div align="left"></div></td>
    <td><div align="left"></div></td>
    <td><div align="left"></div></td>
  </tr>
  <tr>
    <td><div align="left"> Aval2:</div></td>
    <td colspan="3"><div align="left"><span class="Estilo3">$Aval2 - $VarAval2</span></div></td>
  </tr>
  <tr>
    <td colspan="4"><div align="left"></div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="left"> Fecha de firma de los Avales: $nuevafecha </div></td>
    <td colspan="2"><div align="left"> </div></td>
  </tr>
  <tr>
    <td><div align="left"></div></td>
    <td><div align="left"></div></td>
    <td><div align="left"></div></td>
    <td><div align="left"></div></td>
  </tr>
  <tr>
    <td><div align="left"> Plazo de pago:</div></td>
    <td><div align="left">$meses Meses</div></td>
    <td><div align="center">Monto:</div></td>
    <td><div align="left">$ $VarImporte</div></td>
  </tr>
  <tr>
    <td colspan="4"><div align="left">  ____________________________________________________________________________________________________  </div></td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb2, true, false, false, false, '');
$pdf->SetFont("dejavusans", "", 9);
$tb02 = <<<EOD
  <table width="682" border="0" align="CENTER">
    <tr>
      <td width="336"><div align="center">REPRESENTANTE SINDICAL</div></td>
      <td width="336"><div align="center">DIRECTORA DE PENSIONES</div></td>
    </tr>
    <tr>
      <td><p align="left">&nbsp;</p>
      <p align="CENTER">____________________________________________________</p></td>
      <td><p align="CENTER">&nbsp;</p>
      <p align="left">____________________________________________________</p></td>
    </tr>
    <tr>
      <td><div align="center">$VarRepreSindicato</div></td>
      <td><div align="LEFT">$VarDirector</div></td>
    </tr>
  </table>
EOD;
$pdf->writeHTML($tb02, true, false, false, false, '');

$pdf->Rect(10,55, 190, 90, 'D');
$pdf->SetDrawColor(0);
//---------------------------------------------------------------------------------------------
$pdf->lastPage();
// add a page
$pdf->SetFont("dejavusans", "", 10);
$pdf->AddPage();
$html1 = <<<EOD
<p align="center"><strong>DIRECCION DE PENSIONES.</strong><br />
    <strong>DEL MUNICIPIO DE OAXACA DE JUÁREZ OAX..</strong><br />
    la 1a PRIVADA DE TRUJANO Nº 121</p>
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html1, 0, 1, 0, true, '', true);

$tb3 = <<<EOD
<table width="647" border="0" align="center">
  <tr>
    <td colspan="2"><div align="center">$VarFirmAvales</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">PAGARE UNICO</div></td>
  </tr>
  <tr>
    <td width="534"><p align="right">Numero:</p></td>
    <td width="103">$VarSolicitante</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">Bueno Por : $ $VarImporte</div></td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb3, true, false, true, false, '');

$pdf->SetFont("dejavusans", "", 8.5);
$tb4 = <<<EOD
<table width="647" border="0" align="center">
  <tr>
    <td colspan="2"><p align="left">Debo (hemo) y Pagare (mos)  incondicionalmente por este pagare mercantil, en esta plaza (o en cualquier  otro lugar a elección del acreedor), a la orden de la DIRECCIÓN DE PENSIONES  DEL MUNICIPIO DE OAXACA DE JUÁREZ, OAX., la cantidad de $ $VarImporte ($VarIimporteLetra)  valor recibido a mi entera satisfacción.</p></td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb4, true, false, true, false, '');

$pdf->SetFont("dejavusans", "", 11);
$tb5 = <<<EOD
<table width="647" border="0" align="center">
  <tr>
    <td><div align="center">S U S C R I P T O R</div></td>
  </tr>
  <tr>
    <td><p align="center">&nbsp;</p>
    <p align="center">                              ____________________________________________________________________</p></td>
  </tr>
  <tr>
    <td><div align="center">$VarSolicitud - $Solicitud</div></td>
  </tr>
 </table>
EOD;
$pdf->writeHTML($tb5, true, false, true, false, '');
$tb6 = <<<EOD
<table width="682" border="0" align="center">
  <tr>
    <td width="342"><div align="center">AVAL1</div></td>
    <td width="336"><div align="center">AVAL2</div></td>
  </tr>

</table>
EOD;
$pdf->writeHTML($tb6, true, false, true, false, '');
$pdf->SetFont("dejavusans", "", 8);
$tb7 = <<<EOD
<table width="666" border="0" align="center">
  <tr>
    <td width="336"><p align="center">________________________________________</p>
    </td>
    <td width="320"><p align="center">________________________________________</p>
    </td>
  </tr>
  <tr>
    <td><div align="center">$Aval1 - $VarAval1</div></td>
    <td><div align="center">$Aval2 - $VarAval2</div></td>
  </tr>  
  <tr>
    <td colspan="2"><div align="center">
      <p align="justify">Este pagaré si no fuera pagado a su vencimiento causará INTERESES  a razón 1.00% MENSUAL por todo el tiempo que dure insoluto y está sujeto a COBRO  ANTICIPADO del saldo al no ser con PUNTUALIDAD cualquier abono especifico en  las CONDICIONES DEL CONTRATO.</p>
    </div>      </td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb7, true, false, true, false, '');

//ºººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººº SEGUNDO PAGARE ºººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººº
$pdf->SetFont("dejavusans", "", 10);
$html2 = <<<EOD
<p align="center"><strong>DIRECCION DE PENSIONES.</strong><br />
    <strong>DEL MUNICIPIO DE OAXACA DE JUÁREZ OAX..</strong><br />
    la 1a PRIVADA DE TRUJANO Nº 121</p>
EOD;
$pdf->writeHTMLCell(0, 0, '', '', $html2, 0, 1, 0, true, '', true);
$tb8 = <<<EOD
<table width="647" border="0" align="center">
  <tr>
    <td colspan="2"><div align="center">$VarFirmAvales</div></td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">PAGARE UNICO</div></td>
  </tr>
  <tr>
    <td width="534"><p align="right">Numero:</p></td>
    <td width="103">$VarSolicitante</td>
  </tr>
  <tr>
    <td colspan="2"><div align="center">Bueno Por : $ $VarImporte</div></td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb8, true, false, true, false, '');
$pdf->SetFont("dejavusans", "", 8.5);
$tb9 = <<<EOD
<table width="647" border="0" align="center">
  <tr>
    <td colspan="2"><p align="left">Debo (hemo) y Pagare (mos)  incondicionalmente por este pagare mercantil, en esta plaza (o en cualquier  otro lugar a elección del acreedor), a la orden de la DIRECCIÓN DE PENSIONES  DEL MUNICIPIO DE OAXACA DE JUÁREZ, OAX., la cantidad de $ $VarImporte ($VarIimporteLetra)  valor recibido a mi entera satisfacción.</p></td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb9, true, false, true, false, '');

$pdf->SetFont("dejavusans", "", 11);
$tb10 = <<<EOD
<table width="647" border="0" align="center">
  <tr>
    <td><div align="center">S U S C R I P T O R</div></td>
  </tr>
  <tr>
    <td><p align="center">&nbsp;</p>
    <p align="center">                              ____________________________________________________________________</p></td>
  </tr>
  <tr>
    <td><div align="center">$VarSolicitud - $Solicitud</div></td>
  </tr>
 </table>
EOD;
$pdf->writeHTML($tb10, true, false, true, false, '');

$tb11 = <<<EOD
<table width="682" border="0" align="center">
  <tr>
    <td width="342"><div align="center">AVAL1</div></td>
    <td width="336"><div align="center">AVAL2</div></td>
  </tr>

</table>
EOD;
$pdf->writeHTML($tb11, true, false, true, false, '');
$pdf->SetFont("dejavusans", "", 8);
$tb12 = <<<EOD
<table width="666" border="0" align="center">
  <tr>
    <td width="336"><p align="center">________________________________________</p>
    </td>
    <td width="320"><p align="center">________________________________________</p>
    </td>
  </tr>
  <tr>
    <td><div align="center">$Aval1 - $VarAval1</div></td>
    <td><div align="center">$Aval2 - $VarAval2</div></td>
  </tr>  
  <tr>
    <td colspan="2"><div align="center">
      <p align="justify">Este pagaré si no fuera pagado a su vencimiento causará INTERESES  a razón 1.00% MENSUAL por todo el tiempo que dure insoluto y está sujeto a COBRO  ANTICIPADO del saldo al no ser con PUNTUALIDAD cualquier abono especifico en  las CONDICIONES DEL CONTRATO.</p>
    </div>      </td>
  </tr>
</table>
EOD;
$pdf->writeHTML($tb12, true, false, false, false, '');
//ºººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººººº
//                   tamaño
//          x         x  y
$pdf->Rect(10, 40, 190, 90, 'D');
$pdf->SetDrawColor(0);

$pdf->Rect(10, 165, 190, 90, 'D');
$pdf->SetDrawColor(0);
//$pdf->RoundedRect(5, 255, 40, 30, 3.50, '1111', 'DF');
//Close and output PDF document
$pdf->Output('solicitud.pdf', 'I');