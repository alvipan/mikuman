<!DOCTYPE html>
<html data-theme="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>Mikuman - Mikrotik User Manager</title>
        <link rel="icon" type="image/x-icon" href="/favicon.svg" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @yield('js')
    </head>
    <body class="relative flex min-h-screen flex-col antialiased">
        <div class="p-2 w-full text-xs">
            @foreach($users as $user)
            <div class="relative overflow-hidden inline-block border border-{{$color}} border-dashed rounded-1 shadow-none w-[156px] text-xs">
                
                <div class="flex items-center ps-1">
                    <h2 class="hotspot-name me-auto font-bold text-[11px]">{{$router->name}}</h2>
                    <h3 class="flex items-top font-bold text-{{$color}} me-1">
                        <span class="text-[10px] mt-1">{{$router->currency}}</span>
                        <span class="text-lg">{{ number_format(explode(',', $profile[0]['on-login'])[2]) }}</span>
                    </h3>
                </div>
                <div class="grid grid-cols-2 m-1">
                    <div class="grid grid-cols-1 gap-1">
                        <button class="btn p-0 btn-outline btn-xs border-dashed w-full font-bold">
                            <span class="icon-[tabler--user] size-3 text-black"></span>
                            <span class="font-mono">{{$user['name']}}</span>
                        </button>
                        @if($user['name'] != $user['password'])
                        <button class="btn p-0 btn-outline btn-xs border-dashed w-full font-bold">
                            <span class="icon-[tabler--lock] size-3 text-black"></span>
                            <span class="font-mono">{{$user['password']}}</span>
                        </button>
                        @endif
                    </div>
                    <div>
                        @if($qrcode)
                        <img class="-mb-6 bg-white ms-auto w-12.5 p-0.5" src="{{ 'data:image/png;base64,'.DNS2D::getBarcodePNG('http://'.$router['host'].'/login?user='.$user['name'].'&password='.$user['password'], 'QRCODE') }}" alt="barcode"/>
                        @endif
                    </div>
                </div>
                <div class="bg-{{$color}} text-white px-1 py-[3.5px] font-bold text-[10px]">
                    <span class="text-end font-bold text-[10px] uppercase pe-1">{{explode(',', $profile[0]['on-login'])[3]}}</span>
                </div>
                <div class="w-35 h-35 absolute top-0 -right-15 rotate-25 bg-{{$color}}/10 z-0"></div>
            </div>
            @endforeach
        </div>
        <script>
            window.print()
            window.close()
        </script>
    </body>
</html>
