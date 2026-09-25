<x-layout>
    <x-slot:title>
        Edit {{ $user->name }}'s Profile
    </x-slot:title>
    <div class="min-h-full w-full">
        <div class="mx-auto w-full max-w-md">
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Edit {{ $user->name }}'s Profile</legend>
                <form method="POST" action="/users/{{ $user->id }}" enctype="multipart/form-data"
                    onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Updating...';">
                    @csrf
                    @method('PUT')
                    <label class="label" for="avatar">Avatar</label>
                    @if ($user->avatar)
                        <div class="mb-2 flex justify-center">
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}'s avatar"
                                class="w-[75px] h-[75px] object-cover rounded-full shadow-md m-2" />
                        </div>
                    @else
                        <div class="mb-2 flex justify-center">
                            <div class="w-20">
                                <img src="https://img.daisyui.com/images/profile/demo/superperson@192.webp"
                                    alt="{{ $user->name }}'s avatar"
                                    class="w-[75px] h-[75px] object-cover rounded-full shadow-md m-2" />
                            </div>
                        </div>
                    @endif
                    <input id="avatar" type="file" class="file-input w-full" name="avatar"
                        accept="image/jpeg,image/png,image/gif, image/jpg" />
                    @error('avatar')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror

                    <label class="label">Name</label>
                    <input type="text" class="input h-12 w-full max-w-full" placeholder="Name" name="name"
                        value="{{ old('name', $user->name) }}" />
                    @error('name')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                    @if (Auth::user()->group?->is_admin && !$user->group?->is_admin)
                        <label class="label">Group</label>
                        <select class="select w-full" name="group_id">
                            <option value="">Select a Group</option>
                            @foreach ($groups as $group)
                                <option value="{{ $group->id }}" @selected(old('group_id', $user->group_id) == $group->id)>
                                    {{ $group->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('group_id')
                            <div class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </div>
                        @enderror
                    @endif
                    <label class="label">Email</label>
                    <input type="text" placeholder="Email" class="input mt-2" disabled value="{{ $user->email }}" />
                    <label class="label">Biography</label>
                    <textarea class="textarea h-32 w-full max-w-full" placeholder="Biography"
                        name="bio">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                    <div class="flex flex-col w-full gap-2">
                        <button type="submit" class="btn bg-base-300 shadow-md mt-4">Update</button>
                        <input type="hidden" name="back_url" value="{{ old('back_url', $backUrl) }}">
                        <a class="btn" href="{{ old('back_url', $backUrl) }}">Back</a>
                    </div>
                </form>
                <form method="POST" action="/users/{{ $user->id }}" class="mt-2"
                    onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Deleting...';">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete your account?')"
                        class="btn btn-error w-full">Delete
                    </button>
                </form>

                @if (Auth::id() === $user->id && Auth::user()->password !== NULL)
                    <div class="divider">Security</div>
                    <section class="space-y-4" aria-labelledby="two-factor-heading">
                        <label class="label" for="2fa">Two-factor</label>

                        @if ($user->hasEnabledTwoFactorAuthentication())
                            <p class="text-sm mb-2">Two-factor authentication is enabled.</p>

                            @if (in_array(session('status'), ['two-factor-authentication-confirmed', 'recovery-codes-generated'], true))
                                <div class="rounded-box border m-2 p-4">
                                    <h3 class="font-semibold">Save your recovery codes</h3>
                                    <p class="mt-1 text-sm">Use one of these codes if you lose access to your authenticator app.
                                        Each code can only be used once.</p>
                                    <div class="mt-3 grid grid-cols-2 gap-2 font-mono text-sm">
                                        @foreach ($user->recoveryCodes() as $recoveryCode)
                                            <code class="rounded bg-base-100 p-2 text-center">{{ $recoveryCode }}</code>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('two-factor.regenerate-recovery-codes') }}"
                                onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Generating...';">
                                @csrf
                                <button type="submit" class="btn bg-base-300 w-full">Generate new recovery codes</button>
                            </form>

                            <form method="POST" action="{{ route('two-factor.disable') }}"
                                onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Disabling...';">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-error w-full mt-2">Disable two-factor
                                    authentication</button>
                            </form>
                        @else
                            <p class="text-sm">Protect your account with an authenticator app.</p>

                            @if (session('status') === 'two-factor-authentication-enabled' && $user->two_factor_secret)
                                <div class="space-y-3 m-2">
                                    <div class="mx-auto w-fit rounded bg-white p-2" aria-label="Two-factor authentication QR code">
                                        {!! $user->twoFactorQrCodeSvg() !!}
                                    </div>
                                    <form method="POST" action="{{ route('two-factor.confirm') }}"
                                        onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Confirming...';">
                                        @csrf
                                        <div class="flex flex-col gap-1 justify-center items-center">
                                            <span>Authenticator Code</span>
                                            <label class="mb-2 otp">
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                                <span></span>
                                                <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code"
                                                    placeholder="123456" maxlength="6" class="@error('code') input-error @enderror">
                                            </label>
                                            @error('code')
                                                <div class="label -mt-4 mb-2">
                                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                                </div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn bg-base-300 shadow-md mt-3 w-full">Confirm two-factor
                                            authentication</button>
                                    </form>
                                </div>
                            @else
                                <form method="POST" action="{{ route('two-factor.enable') }}"
                                    onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled');">
                                    @csrf
                                    <button type="submit" class="btn bg-base-300 shadow-md w-full">Set up two-factor
                                        authentication</button>
                                </form>
                            @endif
                        @endif
                    </section>
                @endif
            </fieldset>
        </div>
    </div>
</x-layout>