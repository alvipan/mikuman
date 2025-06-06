@extends('app')

@section('content')
<div id="users-table" class="flex flex-col h-[calc(100vh-4rem)]">
    <div class="flex items-center max-md:grid max-md:grid-cols-2 gap-3 px-4 md:px-6 py-4">
        <h2 class="font-bold me-auto">Hotspot Users</h2>
        <div class="flex gap-2 justify-end">
            <div class="tooltip [--placement:bottom]">
                <button type="button" class="tooltip-toggle btn btn-square btn-soft btn-primary btn-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="middle-center-modal" data-overlay="#profile-form-modal">
                    <span class="icon-[tabler--user-plus] size-5"></span>
                </button>
                <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                    <span class="tooltip-body tooltip-primary">Create user</span>
                </span>
            </div>
            <div class="tooltip [--placement:bottom]">
                <button type="button" class="tooltip-toggle btn btn-square btn-soft btn-primary btn-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="middle-center-modal" data-overlay="#profile-form-modal">
                    <span class="icon-[tabler--users-plus] size-5"></span>
                </button>
                <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                    <span class="tooltip-body tooltip-primary">Generate users</span>
                </span>
            </div>
            <div class="tooltip [--placement:bottom]">
                <button type="button" class="tooltip-toggle btn btn-square btn-soft btn-primary btn-sm" aria-haspopup="dialog" aria-expanded="false" aria-controls="middle-center-modal" data-overlay="#profile-form-modal">
                    <span class="icon-[tabler--printer] size-5"></span>
                </button>
                <span class="tooltip-content tooltip-shown:opacity-100 tooltip-shown:visible" role="tooltip">
                    <span class="tooltip-body tooltip-primary">Print</span>
                </span>
            </div>
        </div>
        <div class="input input-sm max-md:col-span-2 sm:max-w-50">
            <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
            <label class="sr-only" for="table-input-search"></label>
            <input type="search" class="grow" placeholder="Search..." id="table-input-search" data-datatable-search="" />
        </div>
    </div>
    <div class="flex-1 px-4 md:px-6 pb-4 md:pb-6 overflow-y-auto">
            <div class="bg-base-100 flex flex-col rounded-md shadow-base-300/20 shadow-sm">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden">
                            <table class="table min-w-full">
                                <thead>
                                    <tr class="text-xs">
                                        <th>Server</th>
                                        <th>Name</th>
                                        <th>Profile</th>
                                        <th>Uptime</th>
                                        <th>Bytes In</th>
                                        <th>Bytes Out</th>
                                        <th>Comment</th>
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
@vite(['resources/js/pages/hotspot-users.js'])
@endsection