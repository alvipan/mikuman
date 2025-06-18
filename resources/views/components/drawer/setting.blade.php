<div id="overlay-setting" class="overlay overlay-open:translate-x-0 drawer drawer-end hidden max-w-72" role="dialog" tabindex="-1">
    <form id="form-setting">
        <div class="drawer-header">
            <h3 class="drawer-title">Settings</h3>
            <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3" aria-label="Close" data-overlay="#overlay-setting">
                <span class="icon-[tabler--x] size-5"></span>
            </button>
        </div>
        <div class="drawer-body">
            <div class="">
                <label class="label-text" for="setting-theme">Theme</label>
                <select name="theme" class="select select-sm" id="setting-theme">
                    <option value="light" {{$router->theme == 'light' ? 'selected' : ''}}>Light</option>
                    <option value="dark" {{$router->theme == 'dark' ? 'selected' : ''}}>Dark (default)</option>
                    <option value="black" {{$router->theme == 'black' ? 'selected' : ''}}>Black</option>
                    <option value="corporate" {{$router->theme == 'corporate' ? 'selected' : ''}}>Corporate</option>
                    <option value="ghibli">Ghibli</option>
                    <option value="gourmet">Gourmet</option>
                    <option value="luxury">Luxury</option>
                    <option value="mintlify">Mintlify</option>
                    <option value="shadcn">Shadcn</option>
                    <option value="slack">Slack</option>
                    <option value="soft">Soft</option>
                    <option value="valorant">Valorant</option>
                </select>
                <span class="helper-text">Define interface for live traffic monitor.</span>
            </div>
            <div class="">
                <label class="label-text" for="setting-name">Name</label>
                <input type="text" name="name" class="input input-sm" id="setting-name" value="{{$router->name}}" />
                <span class="helper-text">The name will be displayed when you print the hotspot users.</span>
            </div>

            <div class="">
                <label class="label-text" for="setting-currency">Currency</label>
                <select name="currency" class="select select-sm" id="setting-currency">
                    <option value="Rp" {{$router->currency == 'Rp' ? 'selected' : ''}}>Rp (default)</option>
                    <option value="$" {{$router->currency == '$' ? 'selected' : ''}}>$</option>
                    <option value="£" {{$router->currency == '£' ? 'selected' : ''}}>£</option>
                    <option value="€" {{$router->currency == '€' ? 'selected' : ''}}>€</option>
                    <option value="¥" {{$router->currency == '¥' ? 'selected' : ''}}>¥</option>
                    <option value="₽" {{$router->currency == '₽' ? 'selected' : ''}}>₽</option>
                </select>
                <span class="helper-text">Specify the currency for sales reports.</span>
            </div>

            <div class="">
                <label class="label-text" for="setting-interface">Interface</label>
                <select name="interface" class="select select-sm" id="setting-interface">
                    <option>Select</option>
                    @foreach($interfaces as $interface)
                    <option value="{{$interface}}" {{$interface == $router->interface ? 'selected' : ''}}>
                        {{$interface}}
                    </option>
                    @endforeach
                </select>
                <span class="helper-text">Define interface for live traffic monitor.</span>
            </div>
        </div>
        <div class="drawer-footer">
            <button type="button" class="btn btn-sm btn-soft btn-secondary" data-overlay="#overlay-setting">Close</button>
            <button type="submit" class="btn btn-sm btn-primary btn-submit">Save changes</button>
        </div>
    </form>
</div>