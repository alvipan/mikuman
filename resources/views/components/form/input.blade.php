@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'label' => '',
    'placeholder' => ''
])

<div class="{{ $classes }}">
    @if(!empty($label))
    <span class="label-text">{{$label}}</span>
    @endif

    @if($type == 'hidden')
    <input type="hidden" name="{{$name}}" value="{{$value}}" wire:model="{{$name}}"/>
    @elseif($type == 'text')
    <input class="input input-sm" name="{{$name}}" value="{{$value}}" placeholder="{{$placeholder}}" wire:model="{{$name}}"/>
    @elseif($type == 'number')

    @elseif($type == 'password')
    <div class="input input-sm">
        <input name="{{$name}}" type="password" placeholder="{{$placeholder}}" value="{{$value}}" id="{{$name}}" wire:model="{{$name}}" autocomplete="new-password"/>
        <button type="button" data-toggle-password='{ "target": "#{{$name}}" }' class="block cursor-pointer" aria-label="password toggle" >
            <span class="icon-[tabler--eye] text-base-content/80 password-active:block hidden size-5 shrink-0"></span>
            <span class="icon-[tabler--eye-off] text-base-content/80 password-active:hidden block size-5 shrink-0"></span>
        </button>
    </div>
    @endif
</div>