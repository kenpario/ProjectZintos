<x-layout>
    <x-slot:title>
        Zintos
    </x-slot:title>
    <div class="flex flex-row gap-4 max-lg:flex-col">
        <div class="w-1/3 max-lg:w-full" id="posts">
            <x-dashboard_posts />
        </div>
        <div id="summary" class="w-full max-lg:w-full">
            <div class="hero min-h-screen rounded-box"
                style="background-image: url(https://img.daisyui.com/images/stock/photo-1507358522600-9f71e620c44e.webp);">
                <div class="hero-overlay"></div>
                <div class="hero-content text-neutral-content text-center">
                    <div class="max-w-md">
                        <h1 class="mb-5 text-5xl font-bold">Hello there</h1>
                        <p class="mb-5">
                            Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi exercitationem
                            quasi. In deleniti eaque aut repudiandae et a id nisi.
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-2">
                <details class="collapse collapse-arrow bg-base-100 border border-base-300" name="my-accordion-det-1"
                    open>
                    <summary class="collapse-title font-semibold">How do I create an account?</summary>
                    <div class="collapse-content text-sm">Click the "Sign Up" button in the top right corner and follow
                        the
                        registration process.</div>
                </details>
                <details class="collapse collapse-arrow bg-base-100 border border-base-300" name="my-accordion-det-2"
                    open>
                    <summary class="collapse-title font-semibold">I forgot my password. What should I do?</summary>
                    <div class="collapse-content text-sm">Click on "Forgot Password" on the login page and follow the
                        instructions sent to your email.</div>
                </details>
                <details class="collapse collapse-arrow bg-base-100 border border-base-300" name="my-accordion-det-3"
                    open>
                    <summary class="collapse-title font-semibold">How do I update my profile information?</summary>
                    <div class="collapse-content text-sm">Go to "My Account" settings and select "Edit Profile" to make
                        changes.</div>
                </details>
            </div>
        </div>
    </div>
</x-layout>