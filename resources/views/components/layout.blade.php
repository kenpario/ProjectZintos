<!DOCTYPE html>

<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - Zintos' : 'Zintos' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/assets/img/favicon/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/assets/img/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/assets/img/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{ asset('storage/assets/img/favicon/site.webmanifest')}}">
    <script>
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)');

        const applySystemTheme = ({ matches }) => {
            document.documentElement.dataset.theme = matches ? 'dark' : 'light';
        };

        applySystemTheme(systemTheme);
        systemTheme.addEventListener('change', applySystemTheme);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @cookieconsentscripts
</head>

<body class="min-h-screen flex flex-col">
    <nav>
        <div class="flex justify-end">
            <button class="btn sm:hidden fixed z-50 m-2 justify-start" popovertarget="mobile_megamenu"
                aria-label="Open navigation menu">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    class="inline-block h-7 w-7 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                    </path>
                </svg>
            </button>
            <div class="mt-11 w-full rounded sm:hidden fixed z-40 border border-base-300 bg-base-200 p-2 text-base-content shadow-md"
                id="mobile_megamenu" popover>
                @auth
                    <ul class="menu w-full">
                        <li><a href="{{ route('dashboard') }}" class="skeleton">Dashboard</a></li>
                        <li>
                            <details>
                                <summary>Categories</summary>
                                <ul>
                                    <li><a href="{{ route('categories') }}">All</a></li>
                                    @foreach ($category_names as $category_name)
                                        <li><a
                                                href="{{ route('categories', ['category' => $category_name]) }}">{{ $category_name }}</a>
                                        </li>
                                    @endforeach
                                    @if(Auth::user()->group?->is_admin)
                                        <li><a class="skeleton" href="{{ route('add_categories') }}">Add Category</a></li>
                                    @endif
                                </ul>
                            </details>
                        </li>
                        <li>
                            <details>
                                <summary>{{ Auth::user()->name }}</summary>
                                <ul>
                                    <li><a href="{{ route('user_profile', ['user' => Auth::user()]) }}">Profile</a></li>
                                    <li><a href="{{ route('edit_user_profile', ['user' => Auth::user()]) }}">Edit
                                            Information</a></li>
                                </ul>
                            </details>
                        </li>
                        @if(Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)
                            <li>
                                <details>
                                    <summary>Moderation</summary>
                                    <ul>
                                        <li><a href="{{ route('mod_posts')}}">Posts Moderation</a></li>
                                        <li><a href="{{ route('mod_comments')}}">Comments Moderation</a></li>
                                    </ul>
                                </details>
                            </li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="flex w-full"
                                onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Logging Out...';">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-error w-full">Logout</button>
                            </form>
                        </li>
                    </ul>
                @else
                    <a href="/" class="btn skeleton w-full">Home</a>
                    <a href="{{ route('register') }}" class="btn skeleton w-full">Register</a>
                    <a href="{{ route('login') }}" class="btn skeleton w-full">Login</a>
                @endauth
            </div>
        </div>
        <div class="megamenu max-sm:hidden max-sm:megamenu-vertical flex items-center gap-2 p-2 border border-base-300 fixed z-50 bg-base-200 text-base-content m-1 shadow"
            id="megamenu" popover>
            @auth
                <span class="megamenu-active"></span>
                <a class="btn skeleton" href="{{ route('dashboard') }}">Dashboard</a>

                <button popovertarget="categories_menu">Categories</button>
                <div id="categories_menu" popover>
                    <ul class="menu gap-1 w-full">
                        <li><a href="{{ route('categories') }}">All</a></li>
                        @foreach ($category_names as $category_name)
                            <li><a href="{{ route('categories', ['category' => $category_name]) }}">{{ $category_name }}</a>
                            </li>
                        @endforeach
                        @if(Auth::user()->group?->is_admin)
                            <li><a class="skeleton" href="{{ route('add_categories') }}">Add Category</a></li>
                        @endif
                    </ul>
                </div>

                <button popovertarget="a2">AI</button>
                <div id="a2" popover>
                    <ul class="menu w-full">
                        <li><a>AI infrastructure</a></li>
                        <li><a>Image generation</a></li>
                        <li><a>MCP servers</a></li>
                    </ul>
                </div>

                <button popovertarget="user_menu" class="ms-auto order-last">
                    @if (Auth::user()->group?->is_admin)
                        <div class="aura aura-rainbow">
                            <span class="badge shadow">{{ Auth::user()->group?->name }}</span>
                        </div>
                    @elseif(Auth::user()->group?->is_mod)
                        <div class="aura aura-silver">
                            <span class="badge shadow">{{ Auth::user()->group?->name }}</span>
                        </div>
                    @elseif (Auth::user()->group?->is_premium)
                        <div class="aura aura-gold">
                            <span class="badge shadow">{{ Auth::user()->group?->name }}</span>
                        </div>
                    @else
                        <span class="badge shadow">{{ Auth::user()->group?->name }}</span>
                    @endif
                    <div class="avatar">
                        <div class="w-8 rounded shadow">
                            <img alt="{{ Auth::user()->name }}'s avatar"
                                src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}" />
                        </div>
                    </div>{{ Auth::user()->name}}
                </button>

                <div id="user_menu" class="m-1 w-full" popover>
                    <ul class="menu w-full">
                        <li><a href="{{ route('user_profile', ['user' => Auth::user()]) }}">Profile</a></li>
                        <li><a href="{{ route('edit_user_profile', ['user' => Auth::user()]) }}">Edit Information</a></li>
                        @if(Auth::user()->group?->is_admin || Auth::user()->group?->is_mod)
                            <li>
                                <details>
                                    <summary>Moderation</summary>
                                    <ul>
                                        <li><a href="{{ route('mod_posts')}}">Posts Moderation</a></li>
                                        <li><a href="{{ route('mod_comments')}}">Comments Moderation</a></li>
                                    </ul>
                                </details>
                            </li>
                        @endif
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="inline"
                                onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Logging Out...';">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-error w-full">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
            @guest
                <div class="flex justify-between w-full">
                    <div id="home">
                        <a href="/" class="btn skeleton">Home</a>
                    </div>
                    <div id="register_login">
                        <a href="{{ route('register') }}" class="btn skeleton">Register</a>
                        <a href="{{ route('login') }}" class="btn skeleton">Login</a>
                    </div>
                </div>
            @endguest
        </div>
    </nav>
    @if (session('success'))
        <div class="fixed z-40 toast toast-bottom toast-right">
            <div class="alert alert-success animate-fade-out">
                <svg xmlns="<http://www.w3.org/2000/svg>" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="fixed z-40 toast toast-bottom toast-right">
            <div class="alert alert-error animate-fade-out">
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
    <main class="mt-18 mb-2 ml-2 mr-2 flex-1">
        {{ $slot }}
    </main>
    @cookieconsentview

    <footer class="footer footer-center relative z-10 bg-base-300 text-base-content p-4 rounded">
        <div class="flex flex-col">
            <div>
                <p>Copyright ©2026 - {{  date('Y') }} - All right reserved by Project Zintos</p>
            </div>
            <div class="flex gap-2 justify-center">
                <a class="link link-hover" href="/cookies">Cookie Policy</a>
                <a class="link link-hover" href="/privacy">Privacy Policy</a>
                <a class="link link-hover" href="/terms">Terms of Use</a>
            </div>
        </div>
    </footer>
</body>

</html>