<x-layout>
    <x-slot:title>
        Register
    </x-slot:title>

    <div class="min-h-full w-full">
        <form method="POST" action="/register" class="mx-auto w-full max-w-md" enctype="multipart/form-data"
            onsubmit="const button = this.querySelector('button[type=submit]'); button.disabled = true; button.classList.add('btn-disabled'); button.textContent = 'Registering...';">
            @csrf
            <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-full border p-6 shadow-md">
                <legend class="fieldset-legend">Register</legend>

                <label class="floating-label mb-6">
                    <input type="text" name="name" placeholder="John Doe" value="{{ old('name') }}"
                        class="w-full input input-bordered @error('name') input-error @enderror" required>
                    <span>Name</span>
                </label>
                @error('name')
                    <div class="label -mt-4 mb-2">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror

                <label class="floating-label mb-6">
                    <input type="email" name="email" placeholder="mail@example.com" value="{{ old('email') }}"
                        class="w-full input input-bordered @error('email') input-error @enderror" required>
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
                    <span>Password</span>
                </label>
                @error('password')
                    <div class="label -mt-4 mb-2">
                        <span class="label-text-alt text-error">{{ $message }}</span>
                    </div>
                @enderror

                <label class="floating-label mb-6">
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        class="w-full input input-bordered" required>
                    <span>Confirm Password</span>
                </label>

                <div class="form-control mt-8">
                    <button type="submit" class="btn btn-md skeleton w-full">
                        Register
                    </button>
                </div>
        </form>
        <div class="divider">OR</div>
        <a class="w-full btn bg-white text-black border-[#e5e5e5]" href="{{ route('login_google') }}">
            <svg aria-label="Google logo" width="16" height="16" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512">
                <g>
                    <path d="m0 0H512V512H0" fill="#fff"></path>
                    <path fill="#34a853" d="M153 292c30 82 118 95 171 60h62v48A192 192 0 0190 341">
                    </path>
                    <path fill="#4285f4" d="m386 400a140 175 0 0053-179H260v74h102q-7 37-38 57"></path>
                    <path fill="#fbbc02" d="m90 341a208 200 0 010-171l63 49q-12 37 0 73"></path>
                    <path fill="#ea4335" d="m153 219c22-69 116-109 179-50l55-54c-78-75-230-72-297 55">
                    </path>
                </g>
            </svg>
            Login with Google
        </a>
        <p class="text-center text-sm">
            Already have an account?
            <a href="{{ route('login') }}" class="link link-hover">Login</a>
        </p>
    </div>
</x-layout>