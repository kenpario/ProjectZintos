<x-layout>
    <x-slot:title>
        Two-Factor Authentication
    </x-slot:title>

    <div class="min-h-full w-full">
        <form method="POST" action="{{ route('two-factor.login') }}" class="mx-auto w-full max-w-md">
            @csrf
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Two-Factor Authentication</legend>

                <p class="mb-4 text-sm">Enter the code from your authenticator app or use a recovery code.</p>

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
                <div class="divider p-2">OR</div>
                <label class="floating-label mb-6">
                    <input type="text" name="recovery_code" autocomplete="off" placeholder="Recovery code"
                        class="w-full input input-bordered @error('recovery_code') input-error @enderror">
                    <span>Recovery code (optional)</span>
                </label>
                @error('recovery_code')
                    <div class="label -mt-4 mb-2">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror

                <button type="submit" class="btn bg-base-300 shadow-md w-full">Verify and continue</button>
            </fieldset>
        </form>
    </div>
</x-layout>