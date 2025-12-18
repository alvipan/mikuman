<div class="flex items-center justify-center h-screen p-6">
    <div class="card w-[360px]">
        <div class="card-header flex items-center justify-center">
            <span class="icon-[tabler--medal] size-6"></span>
            <h1 class="font-bold">Mikuman</h3>
        </div>
        <h3 class="divider">Connect to Mikrotik</h3>
        <div class="card-body">
            <form wire:submit="login">
                <div class="mb-3">
                    <label class="label-text" for="host">Address</label>
                    <div class="input">
                        <span class="icon-[tabler--router] text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                        <input type="text" placeholder="192.168.88.1" class="grow" id="host" wire:model="host"/>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="label-text" for="user">Username</label>
                    <div class="input">
                        <span class="icon-[tabler--user] text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                        <input type="text" placeholder="mikuman" class="grow" id="user" wire:model="user"/>
                    </div>
                </div>

                <div class="w-full mb-4">
                    <label class="label-text" for="pass">Password</label>
                    <div class="input">
                        <span class="icon-[tabler--lock] text-base-content/80 my-auto me-3 size-5 shrink-0"></span>
                        <input type="password" placeholder="*******" class="grow" id="pass" autocomplete="new-password" wire:model="pass"/>
                    </div>
                </div>
                <button type="submit" class="btn btn-submit btn-primary w-full">Connect</button>
            </form>
        </div>
    </div>
</div>