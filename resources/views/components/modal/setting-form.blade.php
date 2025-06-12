<div id="setting-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <form id="setting-form" action="/settings" methid="post">
                <div class="modal-header">
                    <h3 class="font-semibold">
                        Settings
                    </h3>
                </div>
                <div class="modal-body">
                    <x-form.input name="name" label="Router name" value="{{$router->name}}" class="mb-3"/>
                    <x-form.input name="dns" label="DNS name" value="{{$router->dns}}" class="mb-3"/>
                    <x-form.input name="currency" label="Currency" value="{{$router->currency}}" class="mb-3"/>
                    <x-form.input name="phone" label="Phone" value="{{$router->phone}}"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#setting-form-modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>