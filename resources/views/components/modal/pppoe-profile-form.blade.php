<div id="pppoe-profile-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <form id="pppoe-profile-form">
                <div class="modal-header border-b border-base-content/10 pb-0 pt-2 items-center">
                    <h3 class="font-semibold">PPPoE Profile</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <x-form.input name="id" class="hidden"/>
                    <x-form.input name="name" label="Name" placeholder="Enter profile name" class="mb-3"/>
                    <x-form.input name="rate-limit" label="Rate limit" placeholder="1M/3M" class="mb-3"/>
                    <label class="label-text">Parent Queue</label>
                    <select name="parent-queue" class="select select-sm mb-3">
                        <option>none</option>
                        @foreach($queues as $queue)
                        <option value="{{$queue['name']}}">{{$queue['name']}}</option>
                        @endforeach
                    </select>
                    <span class="label-text">Only one</span>
                    <select name="only-one" class="select mb-3">
                        <option value="default">Default</option>
                        <option value="no">No</option>
                        <option value="yes" selected>Yes</option>
                    </select>
                    <x-form.input name="dns-server" label="DNS Server" placeholder="8.8.8.8"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#pppoe-profile-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>