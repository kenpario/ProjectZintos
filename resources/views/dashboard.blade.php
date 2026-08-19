<x-layout>
    <x-slot:title>
        Zintos
    </x-slot:title>
    <div class="flex flex-row gap-4 max-lg:flex-col">
        <div class="w-1/3 max-lg:w-full" id="posts">
            <ul class="list bg-base-100 rounded-box shadow-md">

                <li class="p-4 pb-2 text-s opacity-90 tracking-wide">Latest Posts</li>
                @forelse ($latest_posts as $latest_post)
                    <x-dashboard_latest_posts :latest_post="$latest_post" />
                @empty
                    <li class="list-row">
                        <div>
                            <div>Nothing here.</div>
                            <div class="text-xs uppercase font-semibold opacity-60">Nothing here as well.</div>
                        </div>
                    </li>
                @endforelse
            </ul>

            <ul class="list bg-base-100 rounded-box shadow-md mt-2">

                <li class="p-4 pb-2 text-s opacity-90 tracking-wide">Hot Topics</li>
                @forelse ($hot_topics as $hot_topic)
                    <x-dashboard_hot_topics :hot_topic="$hot_topic" />
                @empty
                    <li class="list-row">
                        <div>
                            <div>Nothing here.</div>
                            <div class="text-xs uppercase font-semibold opacity-60">Nothing here as well.</div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        <div id="summary" class="w-full max-lg:w-full">
            <div class="aura aura-dual min-w-352 max-sm:min-w-50">
                <div class="hero min-h-screen rounded-box shadow-xl overflow-hidden"
                    style="background-image: url('/storage/media/background_gif.gif');">
                    <div class="hero-overlay"></div>
                    <div class="hero-content text-neutral-content text-center">
                        <div class="max-w-md">
                            <h1 class="mb-5 text-5xl font-bold">Hello there</h1>
                            <p class="mb-5">
                                Provident cupiditate voluptatem et in. Quaerat fugiat ut assumenda excepturi
                                exercitationem
                                quasi. In deleniti eaque aut repudiandae et a id nisi.
                            </p>
                        </div>
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