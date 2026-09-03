<x-layout>
    <x-slot:title>
        Posts Moderation
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 shadow-md rounded-md">
                <div class="font-semibold bg-base-300 rounded-box p-4">
                    <span>Posts waiting for approval</span>
                </div>
                <div class="w-full">
                    @foreach ($all_unapproved_posts as $post)
                        <div class="shadow-md rounded-box p-2 mt-2">
                            <div class="flex justify-between gap-2 m-2">
                                <span class="text-xs uppercase font-semibold opacity-60"><a
                                        href="/categories/{{ $post->category->id }}/posts"> Submitted for
                                        {{ $post->category?->name }}</a>
                                </span>
                                <span class="text-xs uppercase font-semibold opacity-60"> Submitted
                                    {{ $post->created_at->diffForHumans() }}
                                    @if ($post->updated_at->gt($post->created_at->addSeconds(5)))
                                        <span class="text-xs uppercase font-semibold">-</span>
                                        <span class="text-xs uppercase font-semibold">Edited</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center">
                                <div class="m-2">
                                    <a href="/users/{{ $post->user->id }}"><img
                                            src="{{ $post->user->avatar ? asset('storage/' . $post->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                            alt="{{ $post->user->name }}'s avatar" class="size-10 rounded-box mt-2" /></a>
                                </div>
                                <div class="flex flex-col m-2 w-full gap-2">
                                    <div><a href="/posts/{{ $post->id }}">{{ Str::limit($post->title, 30) }}</a></div>
                                    <div class="text-xs opacity-60 font-semibold">
                                        <a href="/posts/{{ $post->id }}">{{ Str::limit($post->message, 50) }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 flex justify-center">
                    {{ $all_unapproved_posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layout>