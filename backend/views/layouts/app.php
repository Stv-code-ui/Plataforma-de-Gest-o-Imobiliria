<!doctype html>
<html lang="pt">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://cdn.tailwindcss.com"></script>
	<title>@yield('title')</title>
</head>

<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
	@include('partials.header')
	<main class="mx-auto max-w-7xl px-6 py-10 lg:px-8">
		@yield('content')
	</main>
</body>
</html>