<div id="hotspot-user-profile-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1" wire:key="profile-form">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content" wire:ignore>
            <form id="hotspot-user-profile-form" wire:submit="saveProfile">
                <div class="modal-header border-b border-base-content/10 pb-0 pt-2 items-center">
                    <h3 class="font-semibold">Hotspot User Profile</h3>
                    <nav class="tabs tabs-lifted -mb-[1px]" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                        <button type="button" class="tab active-tab:tab-active active" id="tab-general" data-tab="#general" aria-controls="general" role="tab" aria-selected="true">
                            General
                        </button>
                        <button type="button" class="tab active-tab:tab-active" id="tab-detail" data-tab="#detail" aria-controls="detail" role="tab" aria-selected="false">
                            Detail
                        </button>
                    </nav>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    
                    <div id="general" role="tabpanel" aria-labelledby="tab-general">
                        <input type="hidden" name="id" id="id" wire:model="id"/>

                        <div class="mb-3">
                            <label class="label-text" for="name">Name</label>
                            <input class="input input-sm" placeholder="Profile name" id="name" wire:model="name"/>
                        </div>

                        <div class="mb-3">
                            <label class="label-text">Address Pool</label>
                            <select id="ippool" name="ippool" class="select select-sm" wire:model="ippool">
                                <option value="none">none</option>
                                @foreach($pools as $pool)
                                <option value="{{$pool['name']}}" wire:key="{{$pool['name']}}">{{$pool['name']}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="label-text" for="sharedusers">Shared Users</label>
                            <input class="input input-sm" placeholder="1" id="sharedusers" wire:model="sharedusers"/>
                        </div>

                        <div class="mb-3">
                            <label class="label-text" for="ratelimit">Rate Limit</label>
                            <input class="input input-sm" placeholder="1M/3M" id="ratelimit" wire:model="ratelimit"/>
                        </div>

                        <div>
                            <label class="label-text">Parent Queue</label>
                            <select id="parentqueue" name="parentqueue" class="select select-sm" wire:model="parentqueue">
                                <option>none</option>
                                @foreach($queues as $queue)
                                <option value="{{$queue['name']}}" wire:key="{{$queue['name']}}">{{$queue['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="hidden" id="detail" role="tabpanel" aria-labelledby="tab-detail">
                        <div class="grid grid-cols-2 gap-3">
                            <div class="mb-3">
                                <label class="label-text">Expire Mode</label>
                                <select class="select select-sm" id="expmode" name="expmode" wire:model="expmode">
                                    <option>none</option>
                                    <option value="rem">Remove</option>
                                    <option value="ntf">Notice</option>
                                    <option value="remc">Remove & Record</option>
                                    <option value="ntfc">Notice & Record</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="label-text" for="validity">Validity</label>
                                <input class="input input-sm" placeholder="1d" id="validity" wire:model="validity"/>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="label-text" for="price">Price</label>
                            <input class="input input-sm" placeholder="0" id="price" wire:model="price"/>
                        </div>

                        <div class="mb-3">
                            <label class="label-text" for="sprice">Selling Price</label>
                            <input class="input input-sm" placeholder="0" id="sprice" wire:model="sprice"/>
                        </div>
                        
                        <div class="mb-3">
                            <label class="label-text">User Lock</label>
                            <select id="ulock" name="ulock" class="select select-sm" wire:model="ulock">
                                <option value="Disable">Disable</option>
                                <option value="Enable">Enable</option>
                            </select>
                        </div>
                        <div>
                            <label class="label-text">Server Lock</label>
                            <select id="slock" name="slock" class="select select-sm" wire:model="slock">
                                <option value="Disable">Disable</option>
                                <option value="Enable">Enable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#hotspot-user-profile-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary" wire:loading.class="disabled">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>