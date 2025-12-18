<div id="hotspot-user-edit-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:true] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content" wire:ignore>
            <form id="hotspot-user-edit-form" wire:submit="save">
                
                <div class="modal-header border-b border-base-content/10 py-3">
                    <h3 class="font-semibold">Hotspot User</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <input type="hidden" value="" name="item.id" wire:model="item.id"/>
                    <div class="mb-3">
                        <label class="label-text">Server</label>
                        <select class="select select-sm" name="item.server" wire:model="item.server">
                            <option value="all" selected>All</option>
                            @foreach($servers as $server)
                            <option value="{{ $server['name'] }}">{{ $server['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-form.input name="item.name" label="Username" :classes="__('mb-3')"/>
                    <x-form.input name="item.password" label="Password" type="password" :classes="__('mb-3')" />

                    <div class="mb-3">
                        <label class="label-text">Profile</label>
                        <select class="select select-sm" name="item.profile" wire:model="item.profile">
                            @foreach($profiles as $profile)
                            <option value="{{$profile['name']}}">{{$profile['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form.input name="item.comment" label="Comment" value="" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#hotspot-user-edit-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>