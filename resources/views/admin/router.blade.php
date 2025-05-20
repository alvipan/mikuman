@extends('app')

@section('content')
<div class="flex items-center justify-between mb-4">
    <h2 class="font-bold">Router List</h2>
    <a href="/routers/add" class="btn btn-sm btn-icon btn-primary">
        <span class="icon-[tabler--plus] size-4"></span>
        Add
    </a>
</div>

@error('router')
<div class="mb-4">
    <div class="alert p-2 alert-soft alert-error flex items-center gap-3">
        <span class="icon-[tabler--alert-circle] size-4 ms-2"></span>
        {{ $message }}
    </div>
</div>
@enderror

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($routers as $router)
    <div class="card">
        <div class="card-body">
            <div class="flex items-center gap-4">
                <div class="avatar avatar-placeholder">
                    <div class="size-12 bg-base-200/60 text-base-content rounded-md">
                        <spn class="icon-[tabler--router] size-8"></span>
                    </div>
                </div>
                <div>
                    <h3 class="font-semibold">{{$router->name}}</h3>
                    <span class="text-sm text-secondary">{{$router->host}}</span>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div class="card-footer flex justify-end py-4 gap-2">
            <div class="tooltip">
                <button class="tooltip-toggle btn btn-xs btn-square btn-primary btn-connect" data-id="{{$router->id}}">
                    <span class="icon-[tabler--plug-connected] size-4"></span>
                </button>
                <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                    <span class="tooltip-body tooltip-primary">Connect</span>
                </span>
            </div>
            <div class="tooltip">
                <button class="tooltip-toggle btn btn-xs btn-square btn-info btn-edit" data-id="{{$router->id}}">
                    <span class="icon-[tabler--edit-circle] size-4"></span>
                </button>
                <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                    <span class="tooltip-body tooltip-info">Edit</span>
                </span>
            </div>
            <div class="tooltip">
                <button class="tooltip-toggle btn btn-xs btn-square btn-error btn-delete" data-id="{{$router->id}}">
                    <span class="icon-[tabler--trash] size-4"></span>
                </button>
                <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                    <span class="tooltip-body tooltip-error">Delete</span>
                </span>
            </div>
        </div>
    </div>
    @empty
    <div class="md:col-span-3 text-center flex items-center justify-center h-[calc(100vh-10rem)]">
        <div class="text-center">
            <span class="icon-[tabler--router-off] size-24 text-secondary"></span>
            <p class="mb-4">No routers added yet. Please add one.</p>
            <a href="/routers/add" class="btn btn-sm btn-outline btn-primary">Add Router</a>
        </div>
    </div>
    @endforelse
</div>
@endsection

@section('js')
@vite([
    'resources/js/admin/router.js',    
])
@endsection