<div id="pppoe-user-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <form id="pppoe-user-form">
                <div class="modal-header border-b border-base-content/10 py-2 items-center">
                    <h3 class="font-semibold">PPPoE User</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <x-form.input name="id" class="hidden"/>
                    <x-form.input name="service" value="pppoe" class="hidden"/>
                    <x-form.input name="name" label="Name" placeholder="Enter profile name" class="mb-3"/>
                    <x-form.input name="password" type="password" id="password" label="Password" placeholder="******" class="mb-3"/>
                    <span class="label-text">Profile</span>
                    <select id="profile" name="profile" class="select select-sm mb-3">
                        @foreach($profiles as $profile)
                        <option value="{{$profile['name']}}">{{$profile['name']}}</option>
                        @endforeach
                    </select>
                    <x-form.input name="local-address" label="Local address" placeholder="0.0.0.0" class="mb-3"/>
                    <x-form.input name="remote-address" label="Remote address" placeholder="0.0.0.0"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#pppoe-user-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>