<div id="hotspot-user-profile-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <form id="hotspot-user-profile-form">
                <div class="modal-header border-b border-base-content/10 pb-0 pt-2 items-center">
                    <h3 class="font-semibold">Hotspot User Profile</h3>
                    <nav class="tabs tabs-lifted -mb-[1px]" aria-label="Tabs" role="tablist" aria-orientation="horizontal">
                        <button type="button" class="tab active-tab:tab-active active" id="tab-general" data-tab="#general" aria-controls="general" role="tab" aria-selected="true">
                            General
                        </button>
                        <button type="button" class="tab active-tab:tab-active" id="tab-detail" data-tab="#detail" aria-controls="tabs-basic-filled-2" role="tab" aria-selected="false">
                            Detail
                        </button>
                    </nav>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    
                    <div id="general" role="tabpanel" aria-labelledby="tab-general">
                        <x-form.input name="id" placeholder="0" label="" class="hidden"/>
                        <x-form.input name="name" placeholder="Profile Name" label="Name" class="mb-3"/>
                        <div class="mb-3">
                            <label class="label-text">Address Pool</label>
                            <select id="ippool" name="ippool" class="select select-sm">
                                <option value="none">none</option>
                                @foreach($pools as $pool)
                                <option value="{{$pool['name']}}">{{$pool['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-form.input name="sharedusers" value="1" placeholder="1" label="Shared Users" class="mb-3"/>
                        <x-form.input name="ratelimit" placeholder="1M/3M" label="Rate Limit" class="mb-3"/>
                        <div>
                            <label class="label-text">Parent Queue</label>
                            <select id="parentqueue" name="parentqueue" class="select select-sm">
                                <option>none</option>
                                @foreach($queues as $queue)
                                <option value="{{$queue['name']}}">{{$queue['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="hidden" id="detail" role="tabpanel" aria-labelledby="tab-detail">
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <label class="label-text">Expire Mode</label>
                                <select id="expmode" name="expmode" class="select select-sm">
                                    <option value="">none</option>
                                    <option value="rem">Remove</option>
                                    <option value="ntf">Notice</option>
                                    <option value="remc">Remove & Record</option>
                                    <option value="ntfc">Notice & Record</option>
                                </select>
                            </div>
                            <x-form.input name="validity" placeholder="1d" label="Validity"/>
                        </div>
                        <x-form.input name="price" value="0" placeholder="0" label="Price" class="mb-3"/>
                        <x-form.input name="sprice" value="0" placeholder="0" label="Selling Price" class="mb-3"/>
                        <div class="mb-3">
                            <label class="label-text">User Lock</label>
                            <select id="ulock" name="ulock" class="select select-sm">
                                <option value="Disable">Disable</option>
                                <option value="Enable">Enable</option>
                            </select>
                        </div>
                        <div>
                            <label class="label-text">Server Lock</label>
                            <select id="slock" name="slock" class="select select-sm">
                                <option value="Disable">Disable</option>
                                <option value="Enable">Enable</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#hotspot-user-profile-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>