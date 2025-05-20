<div class="alert alert-soft alert-{{$type}} py-2 flex items-center gap-2">
    @if(!empty($icon))
    <span class="icon-[{{$icon}}] size-5"></span>
    @endif
    {{$message}}
</div>