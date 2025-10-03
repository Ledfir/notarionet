<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content ="{!!csrf_token()!!}" />
	<link rel="icon" type="image/png"   href="{{ asset('images/favicon.png') }}">
	<title>NOTARYNET - Firma y certifica documentos de forma digital</title>

	<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
	{{-- <link rel="stylesheet" type="text/css" href="public/extras/css/font-awesome/css/all.min.css"> --}}
	{{-- <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.3/css/all.css" type="text/css"> --}}

	{{-- FB sharing metadata --}}
  <meta name="robots" content="NOODP">
	<meta property="og:url"                content="https://notarionet.com/"/>
	<meta property="og:title"              content="NOTARYNET - Firma y certifica documentos de forma digital"/>
	<meta property="og:description"        content="NOTARYNET es una empresa creada por abogados y empresarios con mas de 30 a帽os de experiencia y con la intenci贸n de simplificar el proceso de la firma de contratos y documentos de todo tipo."/>
  {{-- <meta property="og:image"              content="https://xxx.xxx/public/images/social.jpg"/> --}}
  <meta name="description" content="NOTARYNET es una empresa creada por abogados y empresarios con mas de 30 a帽os de experiencia y con la intenci贸n de simplificar el proceso de la firma de contratos y documentos de todo tipo." />

	@include('shared.jsDir')
<style>
    @font-face {
        font-family: 'Helvetica';
        src: url('{{ asset('fonts/helvetica/Helvetica.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    @font-face {
        font-family: 'Helvetica';
        src: url('{{ asset('fonts/helvetica/Helvetica-Bold.ttf') }}') format('truetype');
        font-weight: bold;
        font-style: normal;
    }
    @font-face {
        font-family: 'Helvetica';
        src: url('{{ asset('fonts/helvetica/Helvetica-Italic.ttf') }}') format('truetype');
        font-weight: normal;
        font-style: italic;
    }
    body {
        font-family: 'Helvetica', sans-serif;
    }
</style>
</head>

<body>
	<div id="app">
		<vue-topprogress ref="loadingBar" color="#000000" :height="2"></vue-topprogress>
		<my-header></my-header>
		<router-view></router-view>
		<my-footer></my-footer>
	</div>
	<script src="https://js.stripe.com/v3/"></script>

	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/62864595b0d10b6f3e730d1c/1g3e7vk10';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
	</script>
	<!--End of Tawk.to Script-->

	<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>

	<script src="{{ asset('extras/js/SignaturePad.min.js') }}" type="text/javascript"></script>

	<!-- document.addEventListener('contextmenu', event => event.preventDefault()); -->
</body>

</html>
