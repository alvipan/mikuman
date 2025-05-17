@extends('app')

@section('content')
<div class="flex items-center justify-center h-screen">
    <div class="card w-[360px]">
        <div class="card-header text-center">
            <span class="icon-[tabler--comet] size-20 text-primary"></span>
            <h3>Login to Mikuman</h3>
        </div>
        <div class="card-body">
            @if($errors->any())
            <div class="alert alert-soft alert-error p-2">{{ $errors->first() }}</div>
            @endif
            <form action="" method="post">
                @csrf
                <div class="w-full mb-3">
                    <label class="label-text" for="username">Userame</label>
                    <input type="text" placeholder="mikuman" class="input" name="username" id="username" />
                </div>
                <div class="w-full mb-4">
                    <label class="label-text" for="password">Password</label>
                    <input type="password" placeholder="*******" class="input" name="password" id="password" />
                </div>
                <button class="btn btn-primary w-full">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection