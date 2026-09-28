<x-layout>
    <x-slot:title>
        Our team
    </x-slot:title>
    <div class="mt-2 flex flex-col gap-8">
        @foreach ($team_groups as $team_group)
            <section class="flex flex-col items-center gap-4">
                @if ($team_group['group']?->is_admin)
                    <div class="aura aura-rainbow m-2">
                        <span class="badge badge-xl shadow">{{ $team_group['group']?->name }}</span>
                    </div>
                @elseif ($team_group['group']?->is_mod)
                    <div class="aura aura-silver m-2">
                        <span class="badge badge-xl shadow">{{ $team_group['group']?->name }}</span>
                    </div>
                @elseif ($team_group['group']?->is_premium)
                    <div class="aura aura-gold m-2">
                        <span class="badge badge-xl shadow">{{ $team_group['group']?->name }}</span>
                    </div>
                @else
                    <div class="m-2">
                        <span class="badge badge-xl shadow">{{ $team_group['group']?->name }}</span>
                    </div>
                @endif
                <div class="sm:flex max-sm:flex max-sm:flex-col gap-4 bg-base-200 rounded-box shadow-md p-4">
                    @foreach ($team_group['members'] as $team_member)
                        <div class="card bg-base-100 w-64 max-w-full shadow-md items-center mt-1 mb-1 p-2">
                            <figure>
                                <a href="/users/{{ $team_member->id }}"><img
                                        src="{{ $team_member->avatar ? asset('storage/' . $team_member->avatar) : 'https://img.daisyui.com/images/profile/demo/superperson@192.webp' }}"
                                        alt="{{ $team_member->name }}'s avatar" class="rounded-xl shadow-md w-63 h-63 object-cover" /></a>
                            </figure>
                            <div class="card-body flex flex-col items-center">
                                <a class="link-hover" href="/users/{{ $team_member->id }}"><span
                                        class="card-title text-md font-bold">{{ $team_member->name }}</span></a>
                                @if ($team_member->group?->is_admin)
                                    <div class="aura aura-rainbow m-2">
                                        <span class="badge badge-xl shadow-md">{{ $team_member->group?->name }}</span>
                                    </div>
                                @elseif ($team_member->group?->is_mod)
                                    <div class="aura aura-silver m-2">
                                        <span class="badge badge-xl shadow-md">{{ $team_member->group?->name }}</span>
                                    </div>
                                @elseif ($team_member->group?->is_premium)
                                    <div class="aura aura-gold m-2">
                                        <span class="badge badge-xl shadow-md">{{ $team_member->group?->name }}</span>
                                    </div>
                                @else
                                    <div class="m-2">
                                        <span class="badge badge-xl shadow-md">{{ $team_group->group?->name }}</span>
                                    </div>
                                @endif
                                <div class="m-2">
                                    <p class="rounded-box m-2 p-2">
                                        {{ $team_member->bio ? $team_member->bio : 'This user has not filled the biography yet.'}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-layout>