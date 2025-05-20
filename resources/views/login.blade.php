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
            <x-alert type="error" message="{{$errors->first()}}" icon="tabler--alert-circle"/>
            @endif
            <form action="" method="post">
                @csrf
                <div class="mb-3">
                    <label class="label-text" for="username">Userame</label>
                    <div class="input">
                        <span class="icon-[tabler--user] text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                        <input type="text" placeholder="mikuman" class="grow" name="username" id="username"/>
                    </div>
                </div>
                <div class="w-full mb-4">
                    <label class="label-text" for="password">Password</label>
                    <div class="input">
                        <span class="icon-[tabler--lock] text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                        <input type="password" placeholder="*******" class="grow" name="password" id="password" autocomplete="new-password"/>
                    </div>
                </div>
                <button class="btn btn-primary w-full">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection