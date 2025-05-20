@extends('app')

@section('content')
<div class="flex items-center gap-3 mb-4">
    <h2 class="font-bold me-auto">Add Router</h2>
    <a href="/routers" class="btn btn-sm btn-text btn-error">
        <span class="icon-[mdi-light--cancel] size-4"></span>
        Cancel
    </a>
    <button class="btn btn-sm btn-icon btn-primary" id="router-save">
        <span class="icon-[mdi-light--content-save] size-4"></span>
        Save
    </button>
</div>

@if($errors->any())
<div class="mb-3">
    <x-alert type="error" message="{{$errors->first()}}" icon="tabler--alert-circle"/>
</div>
@endif

<x-form.router router-id="0"/>
@endsection

@section('js')
<script>
window.addEventListener('load', function() {
    $('#router-save').on('click', function() {
        $('#router-form').submit();
    })
})
</script>
@endsection
