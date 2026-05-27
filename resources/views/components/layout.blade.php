<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flyonui@latest/dist/js/flyonui.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @guest
        {{ $slot }}
    @endguest

    @auth
        <div class="bg-base-200 flex min-h-screen flex-col">
            <!-- ---------- MAIN SIDEBAR ---------- -->
            <aside id="layout-toggle"
                class="overlay overlay-open:translate-x-0 drawer drawer-start inset-y-0 start-0 hidden h-full [--auto-close:lg] sm:w-75 lg:z-50 lg:block lg:translate-x-0 lg:shadow-none"
                aria-label="Sidebar" tabindex="-1">
                <div class="drawer-body border-base-content/20 h-full border-e p-0">
                    <div class="flex h-full max-h-full flex-col">
                        <button type="button" class="btn btn-text btn-circle btn-sm absolute end-3 top-3 lg:hidden"
                            aria-label="Close" data-overlay="#layout-toggle">
                            <span class="icon-[tabler--x] size-5"></span>
                        </button>
                        <div
                            class="text-base-content border-base-content/20 flex flex-col items-center gap-4 border-b px-4 py-6">
                            <div class="avatar">
                                <div class="size-17 rounded-full">
                                    <img src="{{ Auth::user()->profile_image
                                        ? Storage::disk('r2')->temporaryUrl(Auth::user()->profile_image, now()->addDays(7))
                                        : 'https://cdn.flyonui.com/fy-assets/avatar/avatar-5.png' }}"
                                        alt="User profile avatar for authenticated user" />
                                </div>
                            </div>
                            <div class="text-center">
                                <h3 class="text-base-content text-lg font-semibold">{{ Auth::user()->name }}</h3>
                                <p class="text-base-content/80">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="h-full overflow-y-auto">
                            <!-- Menu -->
                            <ul class="menu gap-1 px-4">
                                <x-sidebar-link href="{{ route('dashboard') }}" :isActive="request()->routeIs('dashboard')">
                                    <span class="icon-[tabler--dashboard] size-4.5"></span>
                                    Dashboard
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('account.index') }}" :isActive="request()->routeIs('account.*')">
                                    <span class="icon-[tabler--user] size-4.5"></span>
                                    Account
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('users.index') }}" :isActive="request()->routeIs('users.index') || request()->routeIs('users.*')">
                                    <span class="icon-[tabler--users-group] size-4.5"></span>
                                    Users
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('projects.index') }}" :isActive="request()->routeIs('projects.index') || request()->routeIs('projects.*')">
                                    <span class="icon-[tabler--bulb] size-4.5"></span>
                                    Projects
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('tasks.index') }}" :isActive="request()->routeIs('tasks.index') || request()->routeIs('tasks.*')">
                                    <span class="icon-[tabler--subtask] size-4.5"></span>
                                    Tasks
                                </x-sidebar-link>

                                <x-sidebar-link href="{{ route('logout') }}" :isActive="request()->routeIs('logout')">
                                    <span class="icon-[tabler--logout] size-4.5 text-error"></span>
                                    <span class="text-error">
                                        Logout
                                    </span>
                                </x-sidebar-link>
                            </ul>
                        </div>

                        <div class="mt-auto flex items-center gap-3 p-4">
                            <img src="https://cdn.flyonui.com/fy-assets/logo/logo.png" class="size-8" alt="PMS">
                            <div>
                                <span class="text-base-content block text-xl font-bold">PMS</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <!-- ---------- END MAIN SIDEBAR ---------- -->
            <div class="flex grow flex-col lg:ps-75">
                <!-- ---------- MAIN CONTENT ---------- -->
                <main class="mx-auto w-full flex-1 py-5 px-6">
                    <div class="mb-2">
                        <h2 class="text-lg font-bold text-base-content">{{ $title ?? '' }}</h2>
                    </div>
                    {{ $slot }}
                </main>
                <!-- ---------- END MAIN CONTENT ---------- -->
            </div>
        </div>

    @endauth
</body>

</html>
