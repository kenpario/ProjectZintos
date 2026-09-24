<x-layout>
    <x-slot:title>
        Confirm Password
    </x-slot:title>

    <div class="min-h-full w-full">
        <form method="POST" action="{{ route('password.confirm.store') }}" class="mx-auto w-full max-w-md"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Confirming...';">
            @csrf
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Confirm Password</legend>

                <p class="mb-4 text-sm">Please confirm your password before continuing.</p>

                <label class="floating-label mb-6">
                    <input type="password" name="password" autocomplete="current-password" placeholder="••••••••"
                        class="w-full input input-bordered @error('password') input-error @enderror" required>
                    <span>Password</span>
                </label>
                @error('password')
                    <div class="label -mt-4 mb-2">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror

                <button type="submit" class="btn bg-base-300 shadow-md w-full">Confirm password</button>
            </fieldset>
        </form>
    </div>
</x-layout>