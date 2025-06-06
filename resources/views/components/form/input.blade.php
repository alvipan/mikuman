<div {{$attributes}}>
    <span class="label-text">{{$label}}</span>
    @if($type == 'text')
    <input class="input input-sm" type="{{$type}}" name="{{$name}}" placeholder="{{$placeholder}}" />
    @endif
    @if($type == 'number')
    <div class="input input-sm pe-0 overflow-hidden" data-input-number>
        <input type="text" name="{{$name}}" value="{{$placeholder}}" aria-label="Horizontal stacked buttons" data-input-number-input />
        <span class="divide-base-content/25 border-base-content/25 flex items-center divide-x border-s" >
            <button type="button" class="flex size-9.5 items-center justify-center" aria-label="Increment button" data-input-number-decrement >
                <span class="icon-[tabler--minus] size-3.5 shrink-0"></span>
            </button>
            <button type="button" class="flex size-9.5 items-center justify-center" aria-label="Decrement button" data-input-number-increment >
                <span class="icon-[tabler--plus] size-3.5 shrink-0"></span>
            </button>
        </span>
    </div>
    @endif
</div>