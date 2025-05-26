@extends('app')

@section('content')
<div class="flex items-center justify-center h-screen p-6">
    <div class="card w-[360px]">
        <div class="card-header flex items-center justify-center">
            <span class="icon-[tabler--medal] size-6"></span>
            <h1 class="font-bold">Mikuman</h3>
        </div>
        <h3 class="divider">Connect to Mikrotik</h3>
        <div class="card-body">
            <nav class="tabs tabs-sm tabs-bordered" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                <button type="button" class="tab active-tab:tab-active active w-full" id="tabs-login-item" data-tab="#tabs-login" aria-controls="tabs-login" role="tab" aria-selected="true">
                    Log-in
                </button>
                <button type="button" class="tab active-tab:tab-active w-full" id="tabs-saved-item" data-tab="#tabs-saved" aria-controls="tabs-saved" role="tab" aria-selected="false">
                    Saved
                </button>
            </nav>

            <div class="h-[320px] overflow-y-auto">

                <div id="tabs-login" role="tabpanel" aria-labelledby="tabs-login-item">
                    <form action="" method="post" id="connect">
                        @csrf
                        <div class="mb-3">
                            <label class="label-text" for="host">Address</label>
                            <div class="input">
                                <span class="icon-[tabler--router] text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                                <input type="text" placeholder="192.168.88.1" class="grow" name="host" id="host"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="label-text" for="user">Username</label>
                            <div class="input">
                                <span class="icon-[tabler--user] text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                                <input type="text" placeholder="mikuman" class="grow" name="user" id="user"/>
                            </div>
                        </div>
                        <div class="w-full mb-4">
                            <label class="label-text" for="pass">Password</label>
                            <div class="input">
                                <span class="icon-[tabler--lock] text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                                <input type="password" placeholder="*******" class="grow" name="pass" id="pass" autocomplete="new-password"/>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 mb-3">
                            <input type="checkbox" class="checkbox checkbox-primary" name="save" id="defaultCheckbox1" />
                            <label class="label-text text-base" for="defaultCheckbox1">Save router</label>
                        </div>
                        <button type="submit" class="btn btn-submit btn-primary w-full">Connect</button>
                    </form>
                </div>

                <div id="tabs-saved" role="tabpanel" class="hidden" aria-labelledby="tabs-saved-item">
                    @forelse($routers as $router)
                    <div class="flex items-center gap-2 mt-2">
                        <div class="avatar avatar-placeholder me-2">
                            <div class="size-12 bg-base-200/60 rounded-md">
                                <spn class="icon-[tabler--router] size-7"></span>
                            </div>
                        </div>
                        <div class="text-sm me-auto">
                            <h3 class="font-semibold">{{$router->host}}</h3>
                            <span class="text-secondary">{{$router->user}}</span>
                        </div>
                        <button class="btn btn-sm btn-square btn-primary btn-connect" data-id="{{$router->id}}">
                            <span class="icon-[tabler--plug-connected] size-4"></span>
                        </button>
                        <button class="btn btn-sm btn-square btn-error btn-delete" data-id="{{$router->id}}">
                            <span class="icon-[tabler--trash] size-4"></span>
                        </button>
                    </div>
                    @empty
                    <div class="md:col-span-3 text-center flex items-center justify-center h-[276px]">
                        <div class="text-center">
                            <span class="icon-[tabler--router-off] size-16 text-secondary"></span>
                            <p class="mb-4">No routers saved yet.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<div id="confirm-action" class="overlay modal overlay-open:opacity-100 overlay-open:duration-300 modal-middle hidden" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Dialog Title</h3>
                <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3" aria-label="Close" data-overlay="#confirm-action">
                    <span class="icon-[tabler--x] size-4"></span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft btn-secondary" data-overlay="#confirm-action">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@vite(['resources/js/login.js'])
@endsection