<x-layout>
    <x-slot:title>
        {{ $user->name }}'s Profile
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="sm:flex max-sm:flex-col p-4 sm:p-8 shadow-md rounded-md">
                <div class="flex justify-center">
                    <div class="flex flex-col items-center shadow-md rounded-md p-2 text-center">
                        <h1 class="m-2 text-xl font-semibold">{{ $user->name }}</h1>
                        <div class="divider"></div>
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                            alt="{{ $user->name }}'s avatar" class="rounded-full shadow-md m-2" width="200"
                            height="200" />
                        @if ($user->group?->is_admin)
                            <div class="aura aura-rainbow m-2">
                                <span class="badge badge-xl shadow">{{ $user->group?->name }}</span>
                            </div>
                        @elseif ($user->group?->is_mod)
                            <div class="aura aura-silver m-2">
                                <span class="badge badge-xl shadow">{{ $user->group?->name }}</span>
                            </div>
                        @elseif ($user->group?->is_premium)
                            <div class="aura aura-gold m-2">
                                <span class="badge badge-xl shadow">{{ $user->group?->name }}</span>
                            </div>
                        @else
                            <div class="m-2">
                                <span class="badge badge-xl shadow">{{ $user->group?->name }}</span>
                            </div>
                        @endif
                        <div class="m-2">
                            <p class="shadow">
                                {{ $user->bio ? $user->bio : 'This user has not filled the biography yet.'}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="divider lg:divider-horizontal"></div>
                <div class="w-full">
                    <div class="collapse-title font-semibold bg-base-300 rounded"> {{ $user->name }}'s Activity
                    </div>
                    <ul class="list bg-base-100 rounded-box shadow-md mt-2">
                        <li class="list-row">
                            <div>
                                <div class="text-xs uppercase font-semibold opacity-60">{{ $user->name }} has no recent
                                    activity</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-layout>