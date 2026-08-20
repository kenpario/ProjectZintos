<x-layout>
    <x-slot:title>
        Add Category
    </x-slot:title>
    <form method="POST" action="/categories">
        @csrf
        <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
            <legend class="fieldset-legend">Login</legend>

            <label class="label">Email</label>
            <input type="email" class="input" placeholder="Email" />

            <label class="label">Password</label>
            <input type="password" class="input" placeholder="Password" />

            <button type="submit" class="btn btn-neutral mt-4">Add Category</button>
        </fieldset>
    </form>
</x-layout>