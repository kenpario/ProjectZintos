<x-layout>
    <x-slot:title>
        Dashboard
    </x-slot:title>
    <div class="flex flex-row gap-4 max-lg:flex-col">
        <div class="w-1/3 max-lg:w-full rounded-box p-1" id="posts">
            <ul class="list bg-base-100 rounded-box shadow-md">
                <li class="p-4 pb-2 text-s font-semibold opacity-90 tracking-wide bg-base-300 rounded-md shadow-md">
                    Statistics</li>
                <li class="list-row">
                    <div class="flex flex-col gap-1">
                        <div class="opacity-60 text-xs font-semibold">{{ $all_posts->count() }} Posts</div>
                        <div class="opacity-60 text-xs font-semibold">{{ $users->count() }} Members</div>
                        <div class="opacity-60 text-xs font-semibold">Latest Member: <a class="link-hover"
                                href="/users/{{ $users->sortByDesc('created_at')->first()?->id }}">{{ $users->sortByDesc('created_at')->first()?->name }}</a>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="list bg-base-100 rounded-box shadow-md mt-2">

                <li class="p-4 pb-2 text-s font-semibold opacity-90 tracking-wide bg-base-300 rounded-md shadow-md">
                    Latest
                    Posts</li>
                @forelse ($latest_posts as $latest_post)
                    <x-dashboard_latest_posts :latest_post="$latest_post" :post_likes="$post_likes"
                        :post_views="$post_views" />
                @empty
                    <li class="list-row">
                        <div class="flex justify-center bg-base-100 rounded-box shadow-md p-0 sm:gap-4 sm:p-4 gap-2">
                            <div class="text-s font-semibold opacity-60">
                                Nothing here.
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>

            <ul class="list bg-base-100 rounded-box shadow-md mt-2">

                <li class="p-4 pb-2 text-s font-semibold opacity-90 tracking-wide bg-base-300 rounded-md shadow-md">Hot
                    Topics</li>
                @forelse ($hot_topics as $hot_topic)
                    <x-dashboard_hot_topics :hot_topic="$hot_topic" :post_likes="$post_likes" :post_views="$post_views" />
                @empty
                    <li class="list-row">
                        <div class="flex justify-center bg-base-100 rounded-box shadow-md p-0 sm:gap-4 sm:p-4 gap-2">
                            <div class="text-s font-semibold opacity-60">
                                Nothing here.
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
        <div id="summary" class="flex-1 min-w-0 p-1">
            <div class="aura aura-dual w-full">
                <div class="hero min-h-screen rounded-box shadow-xl overflow-hidden"
                    style="background-image: url('{{ asset('storage/assets/img/items/background.gif') }}');">
                    <div class="hero-overlay"></div>
                    <div class="hero-content text-neutral-content text-center">
                        <div class="max-w-md">
                            <h1 class="mb-5 text-5xl font-bold"><span class="text-rotate text-7xl">
                                    <span class="justify-items-center">
                                        <span>Project</span>
                                        <span>Zintos</span>
                                    </span>
                                </span></h1>
                            <p class="mb-5">
                                Welcome to our community! This is a space for curious minds to connect, share ideas, ask
                                questions,
                                and learn from one another. Whether you're a seasoned expert or just getting started,
                                you'll find a
                                welcoming place to dive into discussions, get honest feedback, and build genuine
                                connections with
                                people who share your interests. Jump in, introduce yourself, and let's grow this
                                community
                                together.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-2 mb-2">
                @foreach($post_categories as $post_category)
                    <details class="collapse collapse-arrow bg-base-200 border border-base-300 mb-2"
                        name="{{ $post_category->name }}" open>
                        <summary class="collapse-title font-semibold bg-base-300 rounded-box shadow-md">
                            <div class="flex items-center justify-between gap-2">
                                <span>
                                    {{ Str::limit($post_category->name, 30) }} --
                                    {{ Str::limit($post_category->description, 30) }}
                                </span>
                                @if(Auth::user()->group?->is_admin)
                                    <x-categories_editdelete :category="$post_category" />
                                @endif
                            </div>
                        </summary>
                        <div class="collapse-content text-sm mt-2 sm:p-4">
                            <ul class="list gap-2 mx-2 mb-2">
                                @foreach($all_posts->where('post_category_id', $post_category->id) as $post)
                                    <x-dashboard_pinned :post="$post" :post_likes="$post_likes" :post_views="$post_views" />
                                @endforeach
                            </ul>
                            <ul class="list gap-2 mx-2">
                                @forelse($all_posts->where('post_category_id', $post_category->id) as $post)
                                    <x-dashboard_categories :post="$post" :post_likes="$post_likes" :post_views="$post_views" />
                                @empty
                                    <li class="w-full gap-2">
                                        <div class="flex justify-center bg-base-100 rounded-box shadow-md gap-4 p-4">
                                            <div class="text-s font-semibold opacity-60">
                                                Nothing here.
                                            </div>
                                        </div>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</x-layout>