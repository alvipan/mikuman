@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'url' => '',
    'placeholder' => ''
])

<div {{$attributes}}>
    @if(!empty($label))
    <label class="label-text">{{$label}}</label>
    @endif
    <select
        id="{{$id}}"
        name="{{$name}}"
        class="hidden"
        data-select='{
            "apiUrl": "{{$url}}",
            "apiFieldsMap": {
                "title":"name",
                "val":"name"
            },
            "placeholder": "{{$placeholder}}",
            "toggleTag": "<button type=\"button\" aria-expanded=\"false\"></button>",
            "toggleClasses": "advance-select-toggle advance-select-sm w-30 flex items-center select-disabled:pointer-events-none select-disabled:opacity-40",
            "dropdownClasses": "advance-select-menu max-h-50 overflow-y-auto",
            "optionClasses": "advance-select-option selected:select-active",
            "optionTemplate": "<div class=\"flex justify-between items-center w-full\"><span data-title></span><span class=\"icon-[tabler--check] shrink-0 size-4 text-primary hidden selected:block \"></span></div>",
            "extraMarkup": "<span class=\"icon-[tabler--caret-up-down] shrink-0 size-4 text-base-content absolute top-1/2 end-3 -translate-y-1/2 \"></span>"
        }'>
        <option>{{$placeholder}}</option>
        @if(isset($slot))
        {{$slot}}
        @endif
    </select>
</div>