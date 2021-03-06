<?php
require_once dirname(__FILE__).'/../../pxp/lib/lib_reporte/ReportePDF.php';
//require_once(dirname(__FILE__) . '/../../lib/tcpdf/tcpdf_barcodes_2d.php');
class RComparacionBySPDF extends  ReportePDF{

    function Header() {
        $this->Ln(5);
        $url_imagen = dirname(__FILE__) . '/../../pxp/lib/images/Logo-BoA.png';

        $f_actual = date_format(date_create($this->datos[0]["fecha_solicitud"]), 'd/m/Y');
        $nro_cite_cobs = $this->datos[0]["nro_cobs"];

        $html = <<<EOF
		<style>
		table, th, td {		
   			font-family: "Calibri";
   			font-size: 9pt;	
		}
		
		</style>
		<body>
		<table border="1" cellpadding="2" cellspacing = "0">
        	<tr>
            	<th style="width: 20%;vertical-align:middle;" align="center" rowspan="2"><img src="$url_imagen" ></th>
            	<th style="width: 60%;vertical-align:middle;" align="center" rowspan="2"><h4>PROCESO DE CONTRATACIÓN MEDIANTE<br>
        	                                                        COMPARACIÓN  DE OFERTA DE BIENES Y SERVICIOS<br>
        	                                                        (Decreto Supremo N° 26688) Version I</h4></th>
            	<th style="width: 20%;" align="center" colspan="2"><div style="padding:10px 10px 10px 10px;"  >$nro_cite_cobs</div></th>
        	</tr>
        	<tr>
        	    <td align="center" valign="middle"> 
        	        <div style="vertical-align:middle;">FECHA:</div>
        	    </td>
        	    <td align="center" valign="middle">
        	        $f_actual
        	    </td>
        	</tr>
        </table>
EOF;

        $this->writeHTML ($html);
        
        /*$tbl = '<table border="1">
                <tr>
                <td style="width: 30%"><img src="'.$url_imagen.'" ></td>
                <td style="width: 40%">
                  DOCUMENTO DE CONTRATACIÓN DEL EXTERIOR
                </td>
                <td style="width:30%;">
                    <table cellspacing="0" cellpadding="1" border="1">
                    <tr>
                        <td style="font-family: Calibri; font-size: 9px;"><b> R-GG-2017</b></td>
                    </tr>
                    <tr>
                        <td align="center" >
                            OB.DAB.DCE.A.153.2017
                            <br>
                        </td>
                     </tr>
                     <tr>
                        <td>FECHA:</td>
                        <td>12/06/2017</td>
                    </tr>

                    </table>
                </td>
                </tr>
                </table>

            ';
        $this->Ln(5);
        $this->writeHTML($tbl, true, false, false, false, '');*/
    }

    function setDatos($datos) {

        $this->datos = $datos;
        //var_dump( $this->datos);exit;
    }

    function  generarReporte()
    {

        $this->AddPage();
        $this->SetMargins(17, 40, 15);
        $this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        $unidad_sol = $this->datos[0]["unidad_sol"];
        $gerencia = $this->datos[0]["gerencia"];
        $funcionario = $this->datos[0]["funcionario"];
        if($this->datos[0]["codigo_pres"] != 'borrador') {
            $funcionario_sol = $this->datos[0]["funcionario_sol"];
            $qr = $this->generarImagen($funcionario_sol);
            $fun_sol = explode('|', $funcionario_sol);
        }
        if($this->datos[0]["codigo_pres"] != 'vbgerencia') {
            $funcionario_adm = $this->datos[0]["funcionario_adm"];
            $qr2 = $this->generarImagen($funcionario_adm);
            $fun_admi = explode('|', $funcionario_adm);
        }
        if($this->datos[0]["codigo_pres"] != 'vbrpc') {
            $funcionario_presu = $this->datos[0]["funcionario_pres"];
            $qr3 =  $this->generarImagen($funcionario_presu);
            $fun_presu = explode('|', $funcionario_presu);
        }

        $nro_items = $this->datos[0]["nro_items"];
        $adjudicado = $this->datos[0]["adjudicado"];
        $motivo_solicitud = $this->datos[0]["motivo_solicitud"];
        $monto_ref = $this->datos[0]["monto_ref"];
        //$codigo = $this->datos[0]["codigo"];
        $nro_partes = explode(',',$this->datos[0]["nro_partes"]);

        $this->Ln(5);
        $tbl = '<table border="1">
                <tr style="font-size: 9pt; text-align: left;">
                    <td style="width:34%; padding: 2em;"><b>&nbsp;&nbsp;Unidad Solicitante:</b><br><span style="text-align: center;">'.$unidad_sol.'</span><br></td>
                    <td style="width:33%; padding: 2em;"><b>&nbsp;&nbsp;Gerencia de Area:</b><br><span style="text-align: center;">'.$gerencia.'</span></td>
                    <td style="width:33%; padding: 2em;"><b>&nbsp;&nbsp;Funcionario Solicitante:</b><br><span style="text-align: center;">'.$funcionario.'</span></td>
                </tr>
                <tr style="font-size: 9pt; text-align: left;">
                    <td style="width:34%; padding: 5px;"><b>&nbsp;&nbsp;Monto Referencial:</b><br><span style="text-align: center;"> $us&nbsp; '.$monto_ref.'</span><br></td>
                    <td style="width:33%; padding: 5px;"><b>&nbsp;&nbsp;Nro. de Items:</b><br><span style="text-align: center;">'.$nro_items.'</span></td>
                    <td style="width:33%; padding: 5px;"><b>&nbsp;&nbsp;Proveedor:</b><br><span style="text-align: center;">'.$adjudicado.'</span> </td>
                </tr>
                <tr style="font-size: 9pt; text-align: justify;">
                     <td colspan="3"><b>Nota:</b> El monto solicitado para el presente requerimiento, fue establecido por la unidad solicitante considerando criterios de economía y &nbsp;&nbsp;condiciones del mercado actual.<br></td>
                </tr>
                <tr style="font-size: 9pt;">
                    <td colspan="2"><b>Sello y firma de la Unidad de Almacenes de no EXISTENCIA(Cuando Corresponda)</b></td>
                    <td >&nbsp;<b>En caso de existir saldo indicar cantidad:</b><br><br></td>
                </tr>
                </table>
                ';

        $this->writeHTML($tbl, true, false, false, false, '');

        $tbl = '<table border="1">
                <tr style="font-size: 9pt;" >
                    <td style="width:100%; text-align: center; padding: 2em;" colspan="2"><b>INFORME TÉCNICO</b></td>
                </tr>
                <tr style="font-size: 9pt; text-align: left;" >
                    <td style="width:100%; padding: 5px;" colspan="2"><b>&nbsp;&nbsp;Causas que originaron la solicitud:</b><br><br>
                        '.$motivo_solicitud.'.<br><br>
                        NOTA.- SE ELIGIRA LA OPCIÓN MAS CONVENIENTE.<br>
                    </td>
                </tr>
                <tr style="font-size: 9pt; text-align: left;" >
                     <td colspan="2"><b>&nbsp;&nbsp;Descripción del Bien o Servicio (Incluir y detallar servicios adicionales como transporte):<br><br>
                       SEGÚN LISTA DE ESPECIFICACIONES TECNICAS:
                     </b>
                     <ol>';
                        foreach ($nro_partes as $partes){
                            $tbl.='<li>'.$partes.'</li>';
                        }
        $tbl.=      '</ol>
                     <br>
                     </td>
                </tr>
                <tr>
                    <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Jefe de Unidad de Abastecimientos:</b> </td>
                    <td style="font-family: Calibri; font-size: 9px; text-align: center;"><b> Gerencia Administrativa Financiera:</b> </td>
                </tr>
                <tr>
                    <td align="center" style="font-family: Calibri; font-size: 9px;"> 
                        <br><br>
                        <img  style="width: 95px; height: 95px;" src="' . $qr . '" alt="Logo">
                        <br>'.$fun_sol[0].' <b>Solicitante</b>
                    </td>
                    <td align="center" style="font-family: Calibri; font-size: 9px;">
                        <br><br>
                        <img  style="width: 95px; height: 95px;" src="' .$qr2. '" alt="Logo">
                        <br>'.$fun_admi[0].' <b>Vo.Bo</b>
                    </td>
                 </tr>
                 <tr>
                    <td style="font-family: Calibri; font-size: 9px;" colspan="2"><b> Autorización de inicio de proceso RPCE:</b><br></td>
                </tr>
                 <tr>
                    <td align="center" style="font-family: Calibri; font-size: 9px;" colspan="2">
                    <br><br> 
                    <img  style="width: 95px; height: 95px;" src="' . $qr3 . '" alt="Logo">
                     <br>'.$fun_presu[0].' <b>RPCE</b>
                    
                    </td>
                </tr>
                </table>
                ';

        $this->writeHTML($tbl, true, false, false, false, '');

        /*$cont = 1;
        foreach( $this->datos as $record){
            $tbl .='<tr style="font-size: 8pt;"><td style="width:3%;">'.$cont.'</td><td>'. $record["nro_parte"].'</td><td>'. $record["nro_parte_alterno"].'</td><td>'. $record["descripcion"].'</td><td style="text-align: center;">'. $record["cantidad_sol"].'</td><td style="text-align: center;">'. $record["codigo"].'</td><td style="text-align: center;">MIAMI</td><td style="text-align: center;">PROPUESTA</td></tr>';
            $cont++;
        }
        $tbl.='</table>';

        $this->Ln(19);
        $this->writeHTML($tbl);

        $this->SetFont('', 'B',9);
        $this->MultiCell(200, 5, "\n" . 'FAVOR ENVIAR SU COTIZACIÓN AL CORREO abastecimiento@boa.bo', 0, 'L', 0, '', '');*/




    }

    function generarImagen( $nac){
        $cadena_qr =  'Funcionario: '.$nac ;
        $barcodeobj = new TCPDF2DBarcode($cadena_qr, 'QRCODE,M');
        $png = $barcodeobj->getBarcodePngData($w = 8, $h = 8, $color = array(0, 0, 0));
        $im = imagecreatefromstring($png);
        if ($im !== false) {
            header('Content-Type: image/png');
            imagepng($im, dirname(__FILE__) . "/../../reportes_generados/" . $nac . ".png");
            imagedestroy($im);

        } else {
            echo 'A ocurrido un Error.';
        }
        $url_archivo = dirname(__FILE__) . "/../../reportes_generados/" . $nac . ".png"; //$this->objParam->getParametro('nombre_archivo')

        return $url_archivo;
    }

}
?>