@extends('app')

@section('content')
<div  id="profile-table">
    <div class="flex items-center gap-2 bg-base-200 min-h-9 mb-3">
        <h2 class="font-bold me-auto">Hotspot Profile</h2>
        <div class="input input-sm max-w-50">
            <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
            <label class="sr-only" for="table-input-search"></label>
            <input type="search" class="grow" placeholder="Search..." id="table-input-search" data-datatable-search="" />
        </div>
        <button type="button" class="btn btn-square btn-outline btn-primary btn-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="middle-center-modal" data-overlay="#profile-form-modal">
            <span class="icon-[tabler--plus] size-4"></span>
        </button>
    </div>
    <div class="card">
        <div class="bg-base-100 flex flex-col rounded-md shadow-base-300/20 shadow-sm">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="table min-w-full">
                            <thead>
                                <tr class="text-xs">
                                    <th>Name</th>
                                    <th>Rate Limit</th>
                                    <th>Validity</th>
                                    <th>Price</th>
                                    <th>Shared</th>
                                    <th>User Lock</th>
                                    <th>Server Lock</th>
                                </tr>
                            </thead>
                            <tbody>
                    
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="border-base-content/25 flex items-center justify-between gap-3 border-t p-3 max-md:flex-wrap max-md:justify-center">
                <div class="text-sm text-base-content/80" data-datatable-info="">
                    Showing
                    <span data-datatable-info-from=""></span>
                    to
                    <span data-datatable-info-to=""></span>
                    of
                    <span data-datatable-info-length=""></span>
                    items
                </div>
                <div class="flex hidden items-center space-x-1" data-datatable-paging="">
                    <button type="button" class="btn btn-text btn-circle btn-sm" data-datatable-paging-prev="">
                        <span aria-hidden="true">«</span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <div class="flex items-center space-x-1 [&>.active]:text-bg-soft-primary" data-datatable-paging-pages=""></div>
                    <button type="button" class="btn btn-text btn-circle btn-sm" data-datatable-paging-next="">
                        <span class="sr-only">Next</span>
                        <span aria-hidden="true">»</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal.create-hotspot-user-profile />
@endsection

@section('js')
@vite(['resources/js/pages/hotspot-profiles.js'])
@endsection
