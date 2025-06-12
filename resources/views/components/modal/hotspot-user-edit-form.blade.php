<div id="hotspot-user-edit-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <form id="hotspot-user-edit-form" method="post">
                @csrf
                <div class="modal-header border-b border-base-content/10 py-3">
                    <h3 class="font-semibold">Edit Hotspot User</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <input type="hidden" name="id" />
                    <div class="mb-3">
                        <label class="label-text">Server</label>
                        <select name="server" class="select select-sm">
                            <option value="">All</option>
                            @foreach($servers as $server)
                            <option value="{{ $server['name'] }}">{{ $server['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form.input name="name" label="Username" class="mb-3"/>
                    <x-form.input name="password" label="Password" type="password" id="password" class="mb-3" />
                    <div class="mb-3">
                        <label class="label-text">Profile</label>
                        <select name="profile" class="select select-sm">
                            @foreach($profiles as $profile)
                            <option value="{{$profile['name']}}">{{$profile['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form.input name="comment" label="Comment" value="" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#hotspot-user-edit-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>