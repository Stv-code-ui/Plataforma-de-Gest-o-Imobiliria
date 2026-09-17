@extends('layouts.app')

@section('title')
{{ $title }}
@endsection

@section('content')
<div class="grid gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-start">
	<section class="pt-4 lg:pt-12">
		<p class="mb-4 text-sm font-semibold uppercase tracking-[0.22em] text-amber-400">Arrendamento sem complicações</p>
		<h1 class="max-w-3xl text-4xl font-bold tracking-tight text-white sm:text-6xl">Encontre um lugar para chamar de <span class="text-amber-400">casa.</span></h1>
		<p class="mt-6 max-w-xl text-lg leading-8 text-slate-300">Explore imóveis selecionados e envie uma mensagem diretamente para a equipa. Este formulário testa a comunicação entre o frontend e o backend.</p>

		<div class="mt-10 grid gap-4 sm:grid-cols-2">
		@foreach ($properties as $property)
			<article class="rounded-2xl border border-white/10 bg-white/[0.06] p-5 shadow-2xl shadow-slate-950/20">
				<div class="mb-5 flex h-32 items-end rounded-xl bg-gradient-to-br from-amber-300 via-orange-400 to-rose-500 p-4">
					<span class="rounded-full bg-slate-950/70 px-3 py-1 text-xs font-medium text-white">Destaque</span>
				</div>
				<h2 class="text-lg font-semibold text-white">{{ $property['name'] }}</h2>
				<p class="mt-1 text-sm text-slate-400">{{ $property['location'] }}</p>
				<p class="mt-4 font-semibold text-amber-300">{{ $property['price'] }}</p>
			</article>
		@endforeach
		</div>
	</section>

	<section class="rounded-3xl border border-white/10 bg-white/[0.07] p-6 shadow-2xl shadow-black/20 sm:p-8">
		<h2 class="text-2xl font-semibold text-white">Fale connosco</h2>
		<p class="mt-2 text-sm leading-6 text-slate-400">Os dados abaixo são enviados para `POST /contact` e processados pelo backend.</p>

		@if ($success !== '')
			<div class="mt-6 rounded-xl border border-emerald-400/30 bg-emerald-400/10 p-4 text-sm text-emerald-200">{{ $success }}</div>
		@endif
		@if ($errors !== [])
			<div class="mt-6 rounded-xl border border-rose-400/30 bg-rose-400/10 p-4 text-sm text-rose-200">
			@foreach ($errors as $error)
				<p>{{ $error }}</p>
			@endforeach
			</div>
		@endif

		<form class="mt-6 space-y-5" method="POST" action="/contact">
			<div>
				<label class="mb-2 block text-sm font-medium text-slate-200" for="name">Nome</label>
				<input class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20" id="name" name="name" value="{{ $formData['name'] ?? '' }}" placeholder="O seu nome" required>
			</div>
			<div>
				<label class="mb-2 block text-sm font-medium text-slate-200" for="email">Email</label>
				<input class="w-full rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20" id="email" name="email" type="email" value="{{ $formData['email'] ?? '' }}" placeholder="nome@exemplo.com" required>
			</div>
			<div>
				<label class="mb-2 block text-sm font-medium text-slate-200" for="message">Mensagem</label>
				<textarea class="min-h-32 w-full resize-y rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-amber-400 focus:ring-2 focus:ring-amber-400/20" id="message" name="message" placeholder="Que imóvel gostaria de conhecer?" required>{{ $formData['message'] ?? '' }}</textarea>
			</div>
			<button class="w-full rounded-xl bg-amber-400 px-5 py-3 font-semibold text-slate-950 transition hover:bg-amber-300 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-2 focus:ring-offset-slate-950" type="submit">Enviar mensagem</button>
		</form>
	</section>
</div>
@endsection