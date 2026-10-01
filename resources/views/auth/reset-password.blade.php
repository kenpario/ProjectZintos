<x-layout>
    <x-slot:title>
        Reset Password
    </x-slot:title>

    <div class="min-h-full w-full">
        <form method="POST" action="{{ route('password.update') }}" class="mx-auto w-full max-w-md"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Resetting...';">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Reset Password</legend>

                <label class="floating-label mb-6">
                    <input type="email" name="email" value="{{ old('email', $request->email) }}"
                        placeholder="mail@example.com"
                        class="w-full input input-bordered @error('email') input-error @enderror" required autofocus>
                    <span>Email</span>
                </label>
                @error('email')
                    <div class="label -mt-4 mb-2">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror

                <label class="floating-label mb-6">
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full input input-bordered @error('password') input-error @enderror" required>
                    <span>New password</span>
                </label>
                @error('password')
                    <div class="label -mt-4 mb-2">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror

                <label class="floating-label mb-6">
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        class="w-full input input-bordered" required>
                    <span>Confirm new password</span>
                </label>
                <div class="flex justify-center">
                    <x-turnstile />
                </div>
                @error('cf-turnstile-response')
                    <div class="label">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <button type="submit" class="btn bg-base-300 shadow-md w-full">Reset password</button>
            </fieldset>
        </form>
    </div>
</x-layout>