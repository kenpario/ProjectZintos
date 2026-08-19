@props(['hot_topic'])

<li class="list-row">
    <div><img class="size-10 rounded-box" alt="Tailwind CSS list item"
            src="https://img.daisyui.com/images/profile/demo/1@94.webp" /></div>
    <div>
        <div>{{ $hot_topic->title }}</div>
        <div class="text-xs uppercase font-semibold opacity-60">{{ $hot_topic->message }}</div>
    </div>
    <button class="btn btn-square btn-ghost"> {{ $hot_topic->likes }}
        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                <path d="M6 3L20 12 6 21 6 3z"></path>
            </g>
        </svg>
    </button>
    <button class="btn btn-square btn-ghost"> {{ $hot_topic->views }}
        <svg class="size-[1.2em]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <g stroke-linejoin="round" stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor">
                <path
                    d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z">
                </path>
            </g>
        </svg>
    </button>
</li>