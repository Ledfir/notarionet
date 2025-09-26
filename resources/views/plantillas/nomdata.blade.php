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
			margin-top: 2%;
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
			.header,
			
			.header {
				top: 0px;
			}
			
			/* .pagenum:before {
				content: counter(page);
			} */

				
	</style>
</head>
<body>
		
		
		<table style="text-align:center;width: 100%;color: gray;">

			<tr>
		        <th style="text-align:center;">Número de constancia</th>
                <td style="text-align:center;">{{ $inputs['number'] }} </td>
            </tr>
            <tr>
		        <th style="text-align:center;">Autoridad de la NOM 151</th>
                <td style="text-align:center;">{{ $inputs['caName'] }} </td>
            </tr>
            <tr>
		        <th style="text-align:center;">Prestador de servicio de la NOM 151</th>
                <td style="text-align:center;">{{ $inputs['nomName'] }} </td>
            </tr>
            <tr>
		        <th style="text-align:center;">Hash del documento firmado</th>
                <td style="text-align:center;">{{ $inputs['hash'] }} </td>
            </tr>
            <tr>
		        <th style="text-align:center;">Algoritmo hash</th>
                <td style="text-align:center;">{{ $inputs['hashAlg'] }} </td>
            </tr>
            <tr>
		        <th style="text-align:center;">Fecha de expedicion</th>
                <td style="text-align:center;">{{ $inputs['expeditionDate'] }} </td>
            </tr>
		   

		</table> 

		
</body>

</html>
