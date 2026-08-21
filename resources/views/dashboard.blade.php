<x-layout>
    <x-slot:title>
        Dashboard
    </x-slot:title>
    <div class="flex flex-row gap-4 max-lg:flex-col">
        <div class="w-1/3 max-lg:w-full" id="posts">
            <ul class="list bg-base-100 rounded-box shadow-md">

                <li class="p-4 pb-2 text-s opacity-90 tracking-wide bg-base-300 rounded">Latest Posts</li>
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

                <li class="p-4 pb-2 text-s opacity-90 tracking-wide bg-base-300 rounded">Hot Topics</li>
                @forelse ($hot_topics as $hot_topic)
                    <x-dashboard_hot_topics :hot_topic="$hot_topic" />
                @empty
                    <li class="list-row">
                        <div>
                            <div>Nothing here.</div>
                            <div class="text-xs uppercase font-semibold opacity-60 ">Nothing here as well.</div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        <div id="summary" class="flex-1 min-w-0">
            <div class="aura aura-dual w-full">
                <div class="hero min-h-screen rounded-box shadow-xl overflow-hidden"
                    style="background-image: url('/storage/media/background_gif.gif');">
                    <div class="hero-overlay"></div>
                    <div class="hero-content text-neutral-content text-center">
                        <div class="max-w-md">
                            <h1 class="mb-5 text-5xl font-bold"><span class="text-rotate text-7xl">
                                    <span class="justify-items-center">
                                        <span>Project</span>
                                        <span>Zintos</span>
                                        <span>Newest</span>
                                        <span>Forum</span>
                                    </span>
                                </span></h1>
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
                @foreach($post_categories as $post_category)
                    <details class="collapse collapse-arrow bg-base-200 border border-base-300 mb-2"
                        name="{{ $post_category->name }}" open>
                        <summary class="collapse-title font-semibold bg-base-300 rounded">{{ Str::limit($post_category->name, 30) }} --
                            {{ Str::limit($post_category->description, 30) }}
                        </summary>
                        @forelse($all_posts->where('post_category_id', $post_category->id) as $post)
                            <x-dashboard_categories :post="$post" />
                        @empty
                            <div class="collapse-content text-sm">
                                <div class="max-lg:w-full" id="posts">

                                    <ul class="list bg-base-100 rounded-box shadow-md mt-2">
                                        <li class="list-row">
                                            <div>
                                                <div>Nothing here.</div>
                                                <div class="text-xs uppercase font-semibold opacity-60">
                                                    Nothing here as well.
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforelse
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</x-layout>