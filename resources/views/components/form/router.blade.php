<form method="post" id="router-form">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold">Router</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="label-text" for="host">Host</label>
                    <input type="text" placeholder="192.168.88.1" value="{{ (old('host')) ? old('host') : (isset($router->host) ? $router->host : '') }}" class="input" name="host" id="host" />
                </div>
                <div class="mb-3">
                    <label class="label-text" for="user">User</label>
                    <input type="text" placeholder="admin" value="{{ (old('user')) ? old('user') : (isset($router->user) ? $router->user : '') }}" class="input" name="user" id="user" />
                </div>
                <div>
                    <label class="label-text" for="pass">Pass</label>
                    <div class="input">
                        <input type="password" placeholder="*****" value="{{ (old('pass')) ? old('pass') : (isset($router->pass) ? $router->pass : '') }}" name="pass" id="pass" autocomplete="{{isset($router->id) ? '' : 'new-password'}}" />
                        <button type="button" data-toggle-password='{ "target": "#pass" }' class="block cursor-pointer" aria-label="password toggle" >
                            <span class="icon-[tabler--eye-off] text-base-content/80 password-active:block hidden size-5 shrink-0"></span>
                            <span class="icon-[tabler--eye] text-base-content/80 password-active:hidden block size-5 shrink-0"></span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <p class="text-xs">Enter Host and Credentials to connect to your router.</p>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="font-semibold">Hotspot</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="label-text" for="name">Name</label>
                    <input type="text" placeholder="Mikuman.NET" value="{{ (old('name')) ? old('name') : (isset($router->name) ? $router->name : '') }}" class="input" name="name" id="name" />
                </div>
                <div class="mb-3">
                    <label class="label-text" for="currency">Currency</label>
                    <input type="text" placeholder="Rp" value="{{ (old('currency')) ? old('currency') : (isset($router->currency) ? $router->currency : '') }}" class="input" name="currency" id="currency" />
                </div>
                <div>
                    <label class="label-text" for="phone">Phone</label>
                    <input type="text" placeholder="081234567890" value="{{ (old('phone')) ? old('phone') : (isset($router->phone) ? $router->phone : '') }}" class="input" name="phone" id="phone" />
                </div>
            </div>
            <div class="card-footer">
                <p class="text-xs">This information will be displayed on the hotspot voucher.</p>
            </div>
        </div>
    </div>
</form>