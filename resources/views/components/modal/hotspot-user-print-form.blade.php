<div id="hotspot-user-print-form-modal" class="overlay modal modal-middle overlay-open:opacity-100 overlay-open:duration-300 hidden [--overlay-backdrop:false] bg-black/50" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-sm overlay-open:opacity-100 overlay-open:duration-300">
        <div class="modal-content">
            <form id="hotspot-user-print-form" method="get" action="/hotspot/users/print">
                @csrf
                <div class="modal-header border-b border-base-content/10 py-3">
                    <h3 class="font-semibold">Print Hotspot Users</h3>
                </div>
                <div class="modal-body overflow-visible pt-3">
                    <div class="mb-3">
                        <span class="label-text">Comment</span>
                        <div class="input input-sm pe-0">
                            <span class="icon-[tabler--ticket] text-base-content/80 size-5 my-auto"></span>
                            <select name="comment" class="select select-sm">
                                <option value="">Select</option>
                                @foreach($comments as $i => $val)
                                <option value="{{$val}}">{{$val}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <span class="label-text">Color Scheme</span>
                        <div class="input input-sm pe-0">
                            <span class="icon-[tabler--square-filled] size-5 my-auto color" style="color: var(--color-red-500)"></span>
                            <select name="color" class="select select-sm">
                                <option value="red-500">Red</option>
                                <option value="orange-500">Orange</option>
                                <option value="amber-500">Amber</option>
                                <option value="yellow-500">Yellow</option>
                                <option value="lime-500">Lime</option>
                                <option value="green-500">Green</option>
                                <option value="emerald-500">Emerald</option>
                                <option value="teal-500">Teal</option>
                                <option value="cyan-500">Cyan</option>
                                <option value="sky-500">Sky</option>
                                <option value="blue-500">Blue</option>
                                <option value="indigo-500">Indigo</option>
                                <option value="violet-500">Violet</option>
                                <option value="purple-500">Purple</option>
                                <option value="fuchsia-500">Fuchsia</option>
                                <option value="pink-500">pink</option>
                                <option value="rose-500">Rose</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-soft" data-overlay="#hotspot-user-print-form-modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-primary btn-submit">Print</button>
                </div>
            </form>
        </div>
    </div>
</div>