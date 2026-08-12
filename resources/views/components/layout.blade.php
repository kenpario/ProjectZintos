<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - Forum' : 'Forum' }}</title>
    <link rel="preconnect" href="<https://fonts.bunny.net>">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div>
        <div class="menu sm:hidden p-2 border border-base-300 fixed bg-white m-1 shadow w-100 rounded h-12" id="my-mobilemenu"
            popover>
            <div>
                <button popovertarget="b1"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        class="inline-block h-7 w-7 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg></button>
                <div class="mt-14 m-2 shadow rounded" id="b1" popover>
                    <ul class="menu w-98">
                        <li><a>Enterprise</a></li>
                        <li><a>CRM software</a></li>
                        <li><a>Security</a></li>
                        <li><a>Consulting</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="megamenu max-sm:hidden max-sm:megamenu-vertical p-2 border border-base-300 fixed bg-white m-1 shadow"
            id="my-megamenu-1" popover>
            <span class="megamenu-active"></span>
            <button popovertarget="a1">Services</button>
            <div id="a1" popover>
                <ul class="menu">
                    <li><a>Enterprise</a></li>
                    <li><a>CRM software</a></li>
                    <li><a>Security</a></li>
                    <li><a>Consulting</a></li>
                </ul>
            </div>

            <button popovertarget="a2">AI</button>
            <div id="a2" popover>
                <ul class="menu">
                    <li><a>AI infrastructure</a></li>
                    <li><a>Image generation</a></li>
                    <li><a>MCP servers</a></li>
                </ul>
            </div>

            <button popovertarget="a3">Cloud Solutions</button>
            <div id="a3" popover>
                <ul class="menu">
                    <li><a>Cloud computing</a></li>
                    <li><a>Storage solutions</a></li>
                    <li><a>Database services</a></li>
                    <li><a>CDN performance</a></li>
                </ul>
            </div>
            <button popovertarget="a4" class="ms-auto order-last">
                <span class="badge shadow">Member</span>
                <div class="avatar">
                    <div class="w-8 rounded shadow">
                        <img alt="Tailwind-CSS-Avatar-component"
                            src="https://img.daisyui.com/images/profile/demo/batperson@192.webp" />
                    </div>
                </div>User
            </button>
            <div id="a4" class="w-55" popover>
                <ul class="menu">
                    <li><a>Profile</a></li>
                    <li><a>Settings</a></li>
                    <li><a>Log Out</a></li>
                </ul>
            </div>
        </div>
    </div>

    <main class="mt-15 mb-2 ml-2 mr-2">
        {{ $slot }}
    </main>

    <footer class="footer footer-center bg-base-300 text-base-content p-4 rounded">
        <div>
            <p>Copyright ©{{  date('Y') }} - All right reserved by Project Zintos</p>
        </div>
    </footer>
</body>

</html>