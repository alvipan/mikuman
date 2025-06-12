<div id="hotspot-user-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <form id="hotspot-user-form" method="post" action="/hotspot/users/generate">
                @csrf
                <div class="modal-header border-b border-base-content/10 py-3">
                    <h3 class="font-semibold">Generate Hotspot User</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <x-form.input class="mb-3" name="quantity" label="Quantity" placeholder="1" value="1" />
                    <div class="mb-3">
                        <label class="label-text">Server</label>
                        <select name="server" class="select select-sm">
                            <option value="all">All</option>
                            @foreach($servers as $server)
                            <option value="{{ $server['name'] }}">{{ $server['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-text">Credential Type</label>
                        <select id="ct" name="credential" class="select select-sm">
                            <option value="unp">Username & Password</option>
                            <option value="uip">Username = Password</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-text">Credential Length</label>
                        <select id="cl" name="length" class="select select-sm">
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="label-text">Profile</label>
                        <select id="profile" name="profile" class="select select-sm">
                            @foreach($profiles as $profile)
                            <option value="{{$profile['name']}}">{{$profile['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-form.input name="comment" label="Comment" value="" />
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#hotspot-user-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Generate</button>
                </div>
            </form>
        </div>
    </div>
</div>