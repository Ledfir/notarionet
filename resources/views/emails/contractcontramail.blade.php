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
			font-family: Arial, Helvetica, sans-serif; 
			font-size: 12px;
			background-color: white;
			color: black;
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

		.tabla {
			font-family: Arial,Helvetica Neue,Helvetica,sans-serif; 
			border: 1px solid black;
			margin-top: 20px;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
			border-color: #DFC2C2;
		}
		
		th,td{
			font-family: Arial,Helvetica Neue,Helvetica,sans-serif; 
			
		}
		.tabla tbody tr:nth-child(odd) {background-color: #f2f2f2;}

		.tabla tfoot tr:nth-child(odd) {background-color: #f2f2f2;}

		.tabla tbody tr td{
			text-align: center;
		}

		.tabla thead th {
			padding: 15px;
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
	</style>
</head>
<body>
	<div class="img-container" style="text-align: center;">		
		<img src="https://notarionet.com/public/images/logo.png" alt="" class="imagen_logo" width="300px" >
	</div>
	<hr class="hr_1">
		
		Hola, <b>{{ $inputs['user_contra']['name'] }}</b>

		<b>Has sido invitado a firmar un contrato {{ $inputs['contract']['title'] }} de forma segura y valida por NOTARIONET. <br><br>

		@if($inputs['user_contra']['status'] == 'pendiente')
			Debes de registrar tus datos en nuestro sistema para completar la firma digital. Accede aqui y sigue el proceso;  https://notarionet.com/#/registrarse/{{ $inputs['contract']['title_url'] }}-{{ $inputs['user_contra']['id'] }}<br><br>
		@else
			Accede a esta liga y sigue el proceso; https://notarionet.com/#/login<br><br>
		@endif

		<b>NOTARIONET</b>  es una plataforma en donde puedes firmar de manera segura y con validez legal todo tipo de contratos y acuerdos. Todo lo que se firma cuenta con sello de tiempo y certificado digital. Puedes usarlo tu tambi&#233;n para eficientar y asegurar los procesos de tu negocio, NOTARIONET puede funcionar para cualquier giro. 
		<br><br>
		Atentamente.<br>
		<b>NOTARIONET</b><br>
		
		Respaldo por la Ley Federal de Firma Electr&#243;nica, C&#243;digo de Comercio y Leyes Estatales de Medios Electr&#243;nicos


</body>
</html>