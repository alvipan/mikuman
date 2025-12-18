<div id="pppoe-profile-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content" wire:ignore>
            <form id="pppoe-profile-form" wire:submit="save">
                <div class="modal-header border-b border-base-content/10 pb-3 pt-2 items-center">
                    <h3 class="font-semibold">PPPoE Profile</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <input name="item.id" type="hidden" wire:model="item.id"/>
                    <x-form.input name="item.name" label="Name" placeholder="Enter profile name" class="mb-3"/>
                    <x-form.input name="item.rate_limit" label="Rate limit" placeholder="1M/3M" class="mb-3"/>
                    <label class="label-text">Parent Queue</label>
                    <select name="item.parent_queue" class="select select-sm mb-3" wire:model="item.parent_queue">
                        <option>none</option>
                        @foreach($queues as $queue)
                        <option value="{{$queue['name']}}">{{$queue['name']}}</option>
                        @endforeach
                    </select>
                    <span class="label-text">Only one</span>
                    <select name="item.only_one" class="select mb-3" wire:model="item.only_one">
                        <option value="default">Default</option>
                        <option value="no">No</option>
                        <option value="yes" selected>Yes</option>
                    </select>
                    <x-form.input name="item.dns_server" label="DNS Server" placeholder="8.8.8.8, 8.8.4.4"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#pppoe-profile-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>