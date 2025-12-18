<div id="pppoe-user-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content" wire:ignore>
            <form id="pppoe-user-form" wire:submit="save">
                <div class="modal-header border-b border-base-content/10 py-2 items-center">
                    <h3 class="font-semibold">PPPoE User</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <x-form.input name="item.id" type="hidden"/>
                    <x-form.input name="item.service" value="pppoe" type="hidden"/>
                    <x-form.input name="item.name" label="Name" placeholder="Enter profile name" :classes="__('mb-3')"/>
                    <x-form.input name="item.password" type="password" label="Password" placeholder="******" :classes="__('mb-3')"/>
                    <span class="label-text">Profile</span>
                    <select name="item.profile" class="select select-sm mb-3" wire:model="item.profile">
                        @foreach($profiles as $profile)
                        <option value="{{$profile['name']}}">{{$profile['name']}}</option>
                        @endforeach
                    </select>
                    <x-form.input name="item.local_address" label="Local address" placeholder="0.0.0.0" :classes="__('mb-3')"/>
                    <x-form.input name="item.remote_address" label="Remote address" placeholder="0.0.0.0" :classes="__('mb-3')"/>
                    <span class="label-text">Disabled</span>
                    <select name="item.disabled" class="select select-sm mb-3" wire:model="item.disabled">
                        <option value="yes">yes</option>
                        <option value="no" selected>no</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#pppoe-user-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>