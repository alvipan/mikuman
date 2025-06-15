@extends('app')

@section('content')

<div class="card md:max-w-md m-auto bg-transparent shadow-none">
    <figure>
        <img src="{{ Storage::temporaryUrl('img/development.webp', now()->addMinutes(5)); }}"/>
    </figure>
    <div class="card-body text-center">
        <h2 class="text-2xl font-bold text-primary">Under Development</h2>
        <p>We are building it. Wait until the latest update.</p>
    </div>
</div>
@endsection
