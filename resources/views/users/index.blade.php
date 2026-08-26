<x-layout>
    <x-slot:title>
        Profile
    </x-slot:title>
    <div class="max-w-full py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 shadow-md rounded-md">
                <div class="flex justify-center">
                    <div class="shadow-md rounded-md p-2 text-center">
                        <h1 class="m-2 text-xl font-semibold">{{ $user->name }}</h1>
                        <div class="divider"></div>
                        <div class="shadow-md rounded-full m-2">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}'s avatar"
                                    class="rounded-full" width="200" height="200" />
                            @else
                                <img src="https://img.daisyui.com/images/profile/demo/superperson@192.webp" alt="{{ $user->name }}'s avatar"
                                    class="rounded-full" width="200" height="200" />
                            @endif
                        </div>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>