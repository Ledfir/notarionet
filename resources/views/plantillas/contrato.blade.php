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
			
			
			.header {
				
				width: 100%;
				text-align: right;
				position: fixed;
				top: 0px;
			}
			
			/* .pagenum:before {
				content: counter(page);
			} */

				
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

		@if($inputs['cancel'] == 1)
			<div >
				<table style="background-color:red;height:50px;">
					<tr>
						<th style="border: 0px solid red;text-align:center">Cancelado</th>
						<th style="border: 0px solid red;text-align:center">Cancelado</th>
						<th style="border: 0px solid red;text-align:center">Cancelado</th>
					</tr>
				</table>	
			</div>
		@endif

		<div class="img-container" style="text-align: center;">		
			<img src="{{$inputs['user']['imageUrl']}}" alt="" class="imagen_logo" width="200px" >
		</div>
			
		@if($inputs['contract']['contracts_id'] == 0 || $inputs['contract']['contracts_id'] == 999999999 || $inputs['contract']['contracts_id'] == 25 || $inputs['contract']['contracts_id'] == 30 || $inputs['contract']['contracts_id'] == 31 || $inputs['contract']['contracts_id'] == 32 || $inputs['contract']['contracts_id'] == 33 || $inputs['contract']['contracts_id'] == 34 || $inputs['contract']['contracts_id'] == 35)
			{!! $inputs['contract']['body'] !!}
		@else
			
			<h2 style="text-align:center;">{{$inputs['contract']['title']}}</h2>

			<div>
				{!! $inputs['contract']['header_format'] !!}
			</div>
			<div>
				{!! $inputs['contract']['medio_format'] !!}
			</div>
		
		

			

			<!-- @foreach($inputs['contract']['clauses'] as $detail)
				<div>{{$detail['title']}} {!! $detail['response_data'] !!}</div>
			@endforeach -->

		
			<div>
				{!! $inputs['contract']['inferior_format'] !!}
			</div>
		@endif

		<!-- <table style="text-align:center;width: 100%;">
			<tr>
		        <td width="50%" style="text-align:center;"><img src="{{$inputs['user']['signature_user_imageUrl']}}" width="150px"></td>
				@foreach($inputs['users_contras'] as $contra)
					<td width="50%" style="text-align:center;">
						@if($contra['date_signature_user'] != null)
							<img src="{{$contra['signature_contra_imageUrl']}}" width="150px">
						@endif
					</td>
				@endforeach
		    </tr>
		    <tr>
		        <th width="50%">{{$inputs['user']['name']}}</th>
				@foreach($inputs['users_contras'] as $contra)
		        	<th width="50%">{{$contra['name']}}</th>
				@endforeach
		    </tr>
		    

		</table>-->
		<br><br> 

		@if(sizeof($inputs['images']) > 0)
			<table style="text-align:center;width: 100%;color: gray;">
				@foreach ($inputs['images']->chunk(2) as $collection)
					<tr>
						@foreach ($collection as $value)
							<td width="50%">
								<img src="{{$value['imageUrl']}}" width="150px"><br>
								<p>{{ $value['description'] }}</p>
							</td>
						@endforeach

					</tr>
				@endforeach
				
			</table>
		@endif
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
		        			<td>Fecha y hora de firma. {{$inputs['user']['name']}}</td>
		        			<td>{{$inputs['contract']['date_signature_user']}}</td>
		        		</tr>
						@foreach($inputs['users_contras'] as $contra)
							<tr>
								<td>Fecha y hora de firma de {{$contra['name']}} {{$contra['lastname']}}</td>
								<td>{{$contra['date_signature_user']}}</td>
							</tr>
						@endforeach
		        		<tr>
		        			<td>Fecha y hora de creacion</td>
		        			<td>{{$inputs['contract']['created']}}</td>
		        		</tr>
		        		<tr>
		        			<td>Sello de tiempo</td>
		        			<td>{{$inputs['contract']['stamp']}}</td>
		        		</tr>
		        		<tr>
		        			<td>Certificado digital</td>
		        			<td>{{$inputs['contract']['certificate']}}</td>
		        		</tr>
		        	</table>
		        	
		        </td> -->
		    </tr>
		   

		</table> 

    
		
</body>

</html>
