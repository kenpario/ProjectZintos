<x-layout>
    <x-slot:title>
        Verify Email
    </x-slot:title>

    <div class="min-h-full w-full">
        <div class="mx-auto w-full max-w-md">
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Verify your email</legend>

                <p class="mb-4 text-sm">Please verify your email address using the link we sent you.</p>

                @if (session('status') === 'verification-link-sent')
                    <div class="alert alert-success mb-4">A new verification link has been sent.</div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn bg-base-300 shadow-md w-full">Resend verification email</button>
                </form>
            </fieldset>
        </div>
    </div>
</x-layout>
