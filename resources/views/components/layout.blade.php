<!DOCTYPE html>

<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - Zintos' : 'Zintos' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    <script>
        const systemTheme = window.matchMedia('(prefers-color-scheme: dark)');

        const applySystemTheme = ({ matches }) => {
            document.documentElement.dataset.theme = matches ? 'dark' : 'light';
        };

        applySystemTheme(systemTheme);
        systemTheme.addEventListener('change', applySystemTheme);
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col">
    <div>
        <div class="menu sm:hidden sm:menu-vertical w-screen max-w-none p-2 border border-base-300 fixed z-50 bg-base-200 text-base-content m-0 shadow-md rounded"
            id="my-mobilemenu" popover>
            <div>
                <button popovertarget="b1"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block h-7 w-7 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg></button>
                <div class="mt-11 w-full shadow-xl rounded gap-1" id="b1" popover>
                    <ul class="menu w-full">
                        <li><a href="{{ route('dashboard') }}" class="skeleton">Dashboard</a></li>
                        <li><a href="{{ route('categories') }}">Categories</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="megamenu max-sm:hidden max-sm:megamenu-vertical flex items-center gap-2 p-2 border border-base-300 fixed z-50 bg-base-200 text-base-content m-1 shadow"
            id="my-megamenu-1" popover>
            <span class="megamenu-active"></span>
            <a class="btn skeleton" href="{{ route('dashboard') }}">Dashboard</a>

            <button class="menu-trigger-categories" popovertarget="a1">Categories</button>
            <div id="a1" class="menu-popover-categories" popover>
                <ul class="menu gap-1 w-full">
                    <li><a href="{{ route('categories') }}">All</a></li>
                    @foreach ($category_names as $category_name)
                        <li><a href="{{ route('categories', ['category' => $category_name]) }}">{{ $category_name }}</a>
                        </li>
                    @endforeach
                    <li><a class="skeleton" href="{{ route('add_categories') }}">Add Category</a></li>
                </ul>
            </div>

            <button class="menu-trigger-ai" popovertarget="a2">AI</button>
            <div id="a2" class="menu-popover-ai" popover>
                <ul class="menu w-full">
                    <li><a>AI infrastructure</a></li>
                    <li><a>Image generation</a></li>
                    <li><a>MCP servers</a></li>
                </ul>
            </div>

            <button class="menu-trigger-cloud" popovertarget="a3">Cloud Solutions</button>
            <div id="a3" class="menu-popover-cloud" popover>
                <ul class="menu w-full">
                    <li><a>Cloud computing</a></li>
                    <li><a>Storage solutions</a></li>
                    <li><a>Database services</a></li>
                    <li><a>CDN performance</a></li>
                </ul>
            </div>
            <button popovertarget="a4" class="menu-trigger-member ms-auto order-last">
                <span class="badge shadow">Member</span>
                <div class="avatar">
                    <div class="w-8 rounded shadow">
                        <img alt="Tailwind-CSS-Avatar-component"
                            src="https://img.daisyui.com/images/profile/demo/batperson@192.webp" />
                    </div>
                </div>User
            </button>
            <div id="a4" class="menu-popover-member min-w-55" popover>
                <ul class="menu w-full">
                    <li><a>Profile</a></li>
                    <li><a>Settings</a></li>
                    <li><a>Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <main class="mt-18 mb-2 ml-2 mr-2 flex-1">
        {{ $slot }}
    </main>

    <footer class="footer footer-center bg-base-300 text-base-content p-4 rounded">
        <div>
            <p>Copyright ©{{  date('Y') }} - All right reserved by Project Zintos</p>
        </div>
    </footer>
</body>

</html>