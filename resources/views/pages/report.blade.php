@extends('app')

@section('content')
<div id="report-table" class="flex flex-col h-[calc(100vh-4rem)]">
    <div class="flex max-md:grid max-md:grid-cols-2 items-center gap-3 px-4 md:px-6 py-4">
        <h2 class="font-bold me-auto md:flex-1">Sales Report</h2>
        <div class="max-md:grid max-md:grid-cols-2 gap-3">
            <button class="btn btn-sm btn-primary btn-soft justify-start">
                <span class="icon-[tabler--ticket] size-4 me-1"></span>
                <span class="shrink-0" id="voucher">0</span>
            </button>
            <button class="btn btn-sm btn-warning btn-soft justify-start">
                <span class="currency">{{$router->currency}}</span>
                <span class="shrink-0" id="income">0</span>
            </button>
        </div>
        <div class="input input-sm md:w-auto">
            <span class="icon-[tabler--search] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
            <input type="search" class="grow" placeholder="Search..." id="table-input-search" data-datatable-search="" />
        </div>
    </div>
    <div class="flex flex-col flex-1 px-4 md:px-6 pb-6">
        <div class="bg-base-100 flex flex-col flex-1 rounded-md shadow-base-300/20 shadow-sm">
            <div class="flex px-5 py-3 gap-2">
                <div class="input input-sm w-auto ps-1 me-auto">
                    <select class="select select-sm" id="page-entities" data-datatable-page-entities>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                    <span class="label-text my-auto">/page</span>
                </div>
                <div class="input input-sm md:w-60">
                    <span class="icon-[tabler--calendar] text-base-content/80 my-auto me-3 size-4 shrink-0"></span>
                    <input type="search" class="grow" id="date-range" />
                </div>
            </div>
            <div class="flex-grow h-90 overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="relative">
                        <table class="table min-w-full">
                            <thead class="bg-base-100 sticky top-0 z-5 shadow">
                                <tr class="text-xs">
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>User</th>
                                    <th>Profile</th>
                                    <th>IP Address</th>
                                    <th>MAC Address</th>
                                    <th>Comment</th>
                                    <th>Price</th>
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
                    <button type="button" class="btn btn-text btn-square btn-sm" data-datatable-paging-prev="">
                        <span aria-hidden="true">«</span>
                        <span class="sr-only">Previous</span>
                    </button>
                    <div class="flex items-center space-x-1 [&>.active]:text-bg-soft-primary" data-datatable-paging-pages=""></div>
                    <button type="button" class="btn btn-text btn-square btn-sm" data-datatable-paging-next="">
                        <span class="sr-only">Next</span>
                        <span aria-hidden="true">»</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@vite(['resources/js/pages/report.js'])
@endsection