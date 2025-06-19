<div id="expire-monitor-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="font-semibold">Expire Monitor</h3>
            </div>
            <form id="expire-monitor-form">
                <div class="modal-body">
                    <div id="mikuman-expire-monitor">
                        @if($mikuman > 0)
                        <div class="alert alert-soft alert-success py-1 px-2">Mikuman expire monitor already running.</div>
                        @else
                        <div class="alert alert-soft alert-error py-1 px-2">Mikuman expire monitor not found or disabled. Click setup button to set it up.</div>
                        @endif
                    </div>
                    <div id="mikhmon-expire-monitor" @class(['mt-3', 'hidden' => $mikhmon <= 0])>
                        <span class="label-text">Mikuman does not need Mikhmon's expire monitor. Give action to Mikhmon Expire Monitor.</span>
                        <select class="select select-sm" name="mikhmon">
                            <option value="">Keep using it</option>
                            <option value="disable">Disable</option>
                            <option value="remove">Remove</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#expire-monitor-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-error btn-submit">Setup</button>
                </div>
            </form>
        </div>
    </div>
</div>