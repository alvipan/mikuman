<div id="hotspot-user-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <form id="hotspot-user-form" wire:submit="generate">
                
                <div class="modal-header border-b border-base-content/10 py-3">
                    <h3 class="font-semibold">Generate Hotspot User</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <x-form.input :classes="__('mb-3')" name="quantity" label="Quantity" placeholder="1" value="1" />
                    <div class="mb-3">
                        <label class="label-text">Server</label>
                        <select name="server" class="select select-sm" wire:model="server">
                            <option value="all" selected>All</option>
                            @foreach($servers as $server)
                            <option value="{{ $server['name'] }}">{{ $server['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-text">Credential Type</label>
                        <select class="select select-sm" wire:model="credentialType">
                            <option value="unp" selected>Username & Password</option>
                            <option value="uip">Username = Password</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-text">Credential Length</label>
                        <select class="select select-sm" wire:model="credentialLength">
                            <option value="4" selected>4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-text">Profile</label>
                        <select class="select select-sm" wire:model="profile">
                            @foreach($profiles as $profile)
                            <option value="{{$profile['name']}}">{{$profile['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form.input name="comment" label="Comment" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#hotspot-user-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>