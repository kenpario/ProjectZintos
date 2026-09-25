<x-layout>
    <x-slot:title>
        Forgot Password
    </x-slot:title>

    <div class="min-h-full w-full">
        <form method="POST" action="{{ route('password.email') }}" class="mx-auto w-full max-w-md"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Sending...';">
            @csrf
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Forgot Password</legend>

                <p class="mb-4 text-sm">Enter your email address and we will send you a password reset link.</p>

                @if (session('status'))
                    <div class="alert alert-success mb-4">{{ session('status') }}</div>
                @endif

                <label class="floating-label mb-2">
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="mail@example.com"
                        class="w-full input input-bordered @error('email') input-error @enderror" required autofocus>
                    <span>Email</span>
                </label>
                @error('email')
                    <div class="label -mt-4 mb-2">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror
                <div class="flex justify-center">
                    <x-turnstile />
                </div>
                <button type="submit" class="btn bg-base-300 shadow-md w-full">Email password reset link</button>
            </fieldset>
        </form>
    </div>
</x-layout>