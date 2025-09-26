<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<style>
		.imagen_logo{

		}



		.img-container{
			width: 100%;
			text-align: center;
		}

		.title{
			color: black;
			font-size: 12px;
			font-family: Arial,Helvetica Neue,Helvetica,sans-serif;
			font-weight: bold;
		}
		.cuerpo{
			font-size: 12px;
			text-align: justify;
		}

		.pie{
			font-size: 12px;
			font-family: Arial,Helvetica Neue,Helvetica,sans-serif; 
			text-align: left;
		}

		body{
			
			font-family: Arial,Helvetica Neue,Helvetica,sans-serif; 
			font-size: 12px;
			background-color: white;
		}


        .img-producto{
            max-width: 100px;
            max-height: 100px;
            width: 100%;
            height: 100%;
        }

		.derecha{
			text-align: right;
		}

		.text-center {
			text-align: center;
		}

		.hr_1{
			border-top: 1px solid #8c8b8b;
		}
		.hr_2 {
			border-top: 1px dashed #8c8b8b;
		}

		.hr_2 {
			background-color: #fff;
			border-top: 2px dotted #8c8b8b;
		}
		h1{
		  text-align:center;
		}
		table {
			  font-family: arial, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			}

			td, th {
			  border: 1px solid #dddddd;
			  text-align: left;
			  padding: 8px;
			}
		/*@page {
                margin: 0cm 0cm;
            }*/
			.header {
				
				width: 100%;
				text-align: right;
				position: fixed;
				top: 0px;
			}
			
				
	</style>
</head>
<body>
	
		<div class="header">
			<script type="text/php">
				if ( isset($pdf) ) {
					$font = $fontMetrics->getFont("Arial","7");
					$pdf->page_text(10, 10, " {PAGE_NUM} - {PAGE_COUNT}", $font, 6, array(0,0,0));
				}
			</script> 
		</div>
		<div class="img-container" style="text-align: center;">		
			<img src="{{$inputs['user']['imageUrl']}}" alt="" class="imagen_logo" width="100px" >
		</div>
		<br>
		<p style="text-align:center">RESPALDO JURIDICO: La imagen mostrada a continuación es certificada por sello de tiempo y certificado digital de NOTARIONET y cumple con las normas NOM151 SCFI 2016 de la Secretaría de economía, el servicio de emisión de certificados digitales DOF 20/06/2011, el servicio de emisión de sellos digitales de tiempo de DOF 14/12/2011 y el servicio de digitalización de documentos en soporte físico como tercero legalmente autorizado TLA DOF 03/12/2019</p>
		<br><br><br>
		@if($inputs['type'] == 'image')
			<div class="img-container" style="text-align: center;">	
				<img src="{{$inputs['imageUrl']}}" alt="" class="imagen_logo" width="250px" >
			</div>
		@else
			<div style="text-align: center;">	
				{!! $inputs['body'] !!}
			</div>
		@endif
		<br><br><br><br>
		<table style="text-align:center;width: 100%;color: gray;">

			<tr>
		        <td width="40%" style="text-align:center;">Escanea este QR para validar la legalidad y vigencia de este documento <br>
		        	<img  src="data:image/png;base64, {!! base64_encode($inputs['qr']) !!}"><br>
		        	<b>NN000{{ $inputs['order_id'] }}</b>
		        	
		        </td>
		        <!-- <td width="60%" style="text-align:center;">
		        	<table>
		        		<tr><th colspan="2">Documento certificado por NOTARIONET&reg;</th></tr>
						
		        		<tr>
		        			<td>Fecha y hora de creacion</td>
		        			<td>{{$inputs['created']}}</td>
		        		</tr>
		        		<tr>
		        			<td>Sello de tiempo</td>
		        			<td>{{$inputs['stamp']}}</td>
		        		</tr>
		        		<tr>
		        			<td>Certificado digital</td>
		        			<td>{{$inputs['certificate']}}</td>
		        		</tr>
		        	</table>
		        	
		        </td> -->
		    </tr>
		   

		</table>

    
		
</body>

</html>
