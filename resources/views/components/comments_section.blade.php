@props(['post_comments'])

<div class="gap-2">
    @foreach ($post_comments as $post_comment)
        <div class="p-2 shadow-md rounded-md">
            <div class="flex items-center">
                <div class="m-2">
                    <a href="/users/{{ $post_comment->user->id }}"><img
                            src="{{ $post_comment->user->avatar ? asset('storage/' . $post_comment->user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                            alt="{{ $post_comment->user->name }}'s avatar" class="size-10 rounded-box mt-2" /></a>
                </div>
                <div class="flex w-full">
                    <div class="flex flex-col text-xs font-semibold gap-2 w-full">
                        <div class="flex justify-between uppercase gap-2 opacity-60">
                            <a href="/users/{{ $post_comment->user->id }}"><span>By
                                    {{ $post_comment->user->name }}</span></a>
                            <span>Commented
                                {{ $post_comment->created_at->diffForHumans() }}
                                @if ($post_comment->updated_at->gt($post_comment->created_at->addSeconds(5)))
                                    <span class="text-xs uppercase font-semibold">-</span>
                                    <span class="text-xs uppercase font-semibold">Edited</span>
                                @endif</span>
                        </div>
                        <span>{{ $post_comment->message }}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>