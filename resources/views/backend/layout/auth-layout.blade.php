
<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>@yield('pageTitle')</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="{{asset('backend')}}/vendors/images/apple-touch-icon.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="32x32"
			href="{{asset('backend')}}/vendors/images/favicon-32x32.png"
		/>
		<link
			rel="icon"
			type="image/png"
			sizes="16x16"
			href="{{asset('backend')}}/vendors/images/favicon-16x16.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="{{asset('backend')}}/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="{{asset('backend')}}/vendors/styles/icon-font.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="{{asset('backend')}}/vendors/styles/style.css" />

		@stack('stylesheets')
	</head>
	<body class="login-page">
		<div class="login-header box-shadow">
			<div
				class="container-fluid d-flex justify-content-between align-items-center"
			>
				<div class="brand-logo">
					<a href="login.html">
						<img src="{{asset('backend')}}/vendors/images/deskapp-logo.svg" alt="" />
					</a>
				</div>
				<div class="login-menu">
					
				</div>
			</div>
		</div>
		<div
			class="login-wrap d-flex align-items-center flex-wrap justify-content-center"
		>
			<div class="container">
				<div class="row align-items-center">
					<div class="col-md-6 col-lg-7">
						<img src="{{asset('backend')}}/vendors/images/login-page-img.png" alt="" />
					</div>
					<div class="col-md-6 col-lg-5">
						@yield('content')
					</div>
				</div>
			</div>
		</div>

		<!-- js -->
		<script src="{{asset('backend')}}/vendors/scripts/core.js"></script>
		<script src="{{asset('backend')}}/vendors/scripts/script.min.js"></script>
		<script src="{{asset('backend')}}/vendors/scripts/process.js"></script>
		<script src="{{asset('backend')}}/vendors/scripts/layout-settings.js"></script>
        @stack('scripts')

	</body>
</html>
