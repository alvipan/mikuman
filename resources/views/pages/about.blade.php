@extends('app')

@section('content')
<div class="container mx-auto max-w-screen-2xl overflow-auto">
    <div class="container py-10 px-15">
        <div class="text-center py-5">
            <span class="icon-[tabler--comet] size-20 text-indigo-500"></span>
            <h1 class="text-4xl font-bold">Mikuman</h1>
            <span class="text-base-content/60">Mikrotik User Manager</span>
        </div>
        <div class="text-xl">
            <p class="mb-3">Hello,</p>
            <p class="mb-3">My name is Muhammad Irfan, I am a freelance developer from Indonesia and the author of the Mikuman project.</p>
            <p class="mb-3">You can contact me via:</p>
            <ul class="space-y-2 w-72">
                <li>
                    <a href="mailto:alvipan@gmail.com" target="_blank" class="flex items-center gap-2 py-1 hover:text-primary">
                        <span class="icon-[tabler--mail] size-6"></span>
                        alvipan@gmail.com
                    </a>
                    <a href="https://fb.me/alvipan93" target="_blank" class="flex items-center gap-2 py-1 hover:text-primary">
                        <span class="icon-[tabler--brand-facebook] size-6"></span>
                        alvipan93
                    </a>
                    <a href="https://linkedin.com/in/alvipan" target="_blank" class="flex items-center gap-2 py-1 hover:text-primary">
                        <span class="icon-[tabler--brand-linkedin] size-6"></span>
                        alvipan
                    </a>
                </li>
            </ul>
            
            <div class="divider divider-dashed my-5">
                <h2 class="text-xl font-bold">Mikuman Purpose</h2>
            </div>
            <p>The purpose of mikuman is to make it easier for you to monitor Mikrotik router users including Hotspot and PPPoE. Among them (creating, editing, deleting) users, setting limits, and calculating sales reports if you are an internet service provider.</p>
            
            <div class="divider divider-dashed my-5">
                <h2 class="text-xl font-bold">Open Source</h2>
            </div>
            <p class="mb-3">Mikuman is open source.</p>
            <p>Community contributions are welcome! Feel free to fork the repo and send Pull Requests for improvements, enhancements, or new features.</p>
            
            <div class="divider divider-dashed my-5">
                <h2 class="text-xl font-bold">Monetizing</h2>
            </div>
            <p class="mb-3">Mikuman is not monetized.</p>
            <p class="mb-3">Mikuman is available for free to you without any charges. However, we accept donation support for development, whatever amount you want to give.</p>
            <a href="https://sociabuzz.com/alvipan/tribe" target="_blank" class="btn btn-primary">
                Support Mikuman
                <span class="icon-[tabler--arrow-right] size-5"></span>
            </a>

            <div class="divider divider-dashed my-5">
                <h2 class="text-xl font-bold">Contact Information</h2>
            </div>
            <p class="mb-3">You can contact me via:</p>
            <ul class="space-y-2 w-72 mb-5">
                <li>
                    <a href="mailto:alvipan@gmail.com" target="_blank" class="flex items-center gap-2 py-1 hover:text-primary">
                        <span class="icon-[tabler--mail] size-6"></span>
                        alvipan@gmail.com
                    </a>
                    <a href="https://fb.me/alvipan93" target="_blank" class="flex items-center gap-2 py-1 hover:text-primary">
                        <span class="icon-[tabler--brand-facebook] size-6"></span>
                        alvipan93
                    </a>
                    <a href="https://linkedin.com/in/alvipan" target="_blank" class="flex items-center gap-2 py-1 hover:text-primary">
                        <span class="icon-[tabler--brand-linkedin] size-6"></span>
                        alvipan
                    </a>
                </li>
            </ul>
            <p>Best Regards,</p>
            <p>Muhammad Irfan at Jakarta - Indonesia</p>
            <div class="text-end text-secondary text-md">
                Last updated: 18/06/2025 16:32
            </div>
        </div>
    </div>
</div>
@endsection