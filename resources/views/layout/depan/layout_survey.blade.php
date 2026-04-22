<!DOCTYPE html>
<html lang="en">
	<head>

		<!-- Basic -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<title>DEN</title>	

		<meta name="keywords" content="dewan energi nasional, den" />
		<meta name="description" content="website dewan energi nasional">
		<meta name="author" content="">

		<!-- Favicon -->
		<link rel="shortcut icon" href="{{ asset('theme/img/logo/favicon.png') }}" type="image/x-icon" />
		<link rel="apple-touch-icon" href="{{ asset('theme/img/logo/favicon.png') }}">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0, shrink-to-fit=no">

		<!-- Web Fonts  -->
		<link id="googleFonts" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800%7CShadows+Into+Light&display=swap" rel="stylesheet" type="text/css">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="{{ asset('theme/vendor/bootstrap/css/bootstrap.min.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/vendor/fontawesome-free/css/all.min.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/vendor/animate/animate.compat.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/vendor/simple-line-icons/css/simple-line-icons.min.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/vendor/owl.carousel/assets/owl.carousel.min.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/vendor/owl.carousel/assets/owl.theme.default.min.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/vendor/magnific-popup/magnific-popup.min.css') }}">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="{{ asset('theme/css/theme.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/css/theme-elements.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/css/theme-blog.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/css/theme-shop.css') }}">

		<!-- Revolution Slider CSS -->
		<link rel="stylesheet" href="{{ asset('theme/vendor/rs-plugin/css/settings.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/vendor/rs-plugin/css/layers.css') }}">
		<link rel="stylesheet" href="{{ asset('theme/vendor/rs-plugin/css/navigation.css') }}">

		<!-- Skin CSS -->
		<link id="skinCSS" rel="stylesheet" href="{{ asset('theme/css/skins/skin-green.css') }}">

		<!-- Theme Custom CSS -->
		<link rel="stylesheet" href="{{ asset('theme/css/custom.css') }}">

		{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script> --}}
		<script src="{{ asset('theme/js/jquery3.7.0.min.js') }}"></script>
		<!-- Head Libs -->
		{{-- <script src="{{ asset('theme/vendor/modernizr/modernizr.min.js"') }}"></script> --}}

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-MPT736Y8NK"></script>
	<script>
  		window.dataLayer = window.dataLayer || [];
  		function gtag(){dataLayer.push(arguments);}
 		gtag('js', new Date());
  		gtag('config', 'G-MPT736Y8NK');
	</script>
	
	</head>
	<body data-plugin-page-transition>
		<div class="body">
			
            @include('layout.depan.header_survey')

            @yield('content')

			@include('layout.depan.footer')
		</div>

		<!-- Vendor -->
		<script src="{{ asset('theme/vendor/plugins/js/plugins.min.js') }}"></script>

		<!-- Theme Base, Components and Settings -->
		<script src="{{ asset('theme/js/theme.js') }}"></script>

		<!-- Revolution Slider Scripts -->
		<script src="{{ asset('theme/vendor/rs-plugin/js/jquery.themepunch.tools.min.js') }}"></script>
		<script src="{{ asset('theme/vendor/rs-plugin/js/jquery.themepunch.revolution.min.js') }}"></script>

		<!-- Theme Custom -->
		<script src="{{ asset('theme/js/custom.js') }}"></script>

		<!-- Theme Initialization Files -->
		<script src="{{ asset('theme/js/theme.init.js') }}"></script>

		<script src="{{ asset('theme/js/examples/examples.lightboxes.js') }}"></script>

	</body>
</html>
