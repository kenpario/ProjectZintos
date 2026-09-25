<x-layout>
    <x-slot:title>
        Analytics
    </x-slot:title>

    <div class="mx-auto max-w-7xl px-4 py-10 rounded-box shadow-md m-2 bg-base-200">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="mt-2 text-3xl font-bold text-base-content">Google</h1>
                <h1 class="mt-2 text-3xl font-bold text-base-content">{{ ucfirst($currentRange) }} Analytics</h1>
            </div>

            <div class="card w-full max-w-2xl bg-base-100 shadow-md">
                <div class="card-body p-4 w-full">
                    <form method="GET" action="{{ route('analytics') }}"
                        class="flex flex-col gap-3 sm:flex-row sm:items-end w-full">
                        @csrf
                        <label class="form-control w-full">
                            <span
                                class="label-text mb-1 text-xs font-medium uppercase tracking-wide text-base-content/70">Date
                                range</span>
                            <select name="date_range" id="date_range_select" class="select w-full border-0 shadow-md"
                                onchange="this.form.submit()">
                                @foreach ($dateRanges as $range)
                                    <option value="{{ $range }}" {{ $currentRange == $range ? 'selected' : '' }}>
                                        {{ ucfirst($range) }}
                                    </option>
                                @endforeach
                            </select>
                        </label>

                        @if ($currentRange == 'custom')
                            <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-end items-center">
                                <label class="form-control w-full">
                                    <span
                                        class="label-text mb-1 text-xs font-medium uppercase tracking-wide text-base-content/70">Start
                                        date</span>
                                    <input type="date" name="start_date" class="input w-full border-0 shadow-md"
                                        value="{{ $startDate }}" required>
                                </label>
                                <label class="form-control w-full">
                                    <span
                                        class="label-text mb-1 text-xs font-medium uppercase tracking-wide text-base-content/70">End
                                        date</span>
                                    <input type="date" name="end_date" class="input w-full border-0 shadow-md"
                                        value="{{ $endDate }}" required>
                                </label>
                                <button type="submit" class="btn bg-base-300 shadow-md">Apply</button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="hover hover-3d">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between font-bold">
                            <p class="text-sm text-base-content/70">Total page views</p>
                        </div>
                        <h2 class="mt-3 text-2xl font-bold">{{ number_format($total_visits) }}</h2>
                    </div>
                </div>
            </div>

            <div class="hover hover-3d">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between font-bold">
                            <p class="text-sm text-base-content/70">Total users</p>
                        </div>
                        <h2 class="mt-3 text-2xl font-bold">{{ number_format($total_users) }}</h2>
                    </div>
                </div>
            </div>

            <div class="hover hover-3d">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between font-bold">
                            <p class="text-sm text-base-content/70">New users</p>
                        </div>
                        <h2 class="mt-3 text-2xl font-bold">{{ number_format($new_users) }}</h2>
                    </div>
                </div>
            </div>

            <div class="hover hover-3d">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between font-bold">
                            <p class="text-sm text-base-content/70">Avg. session Time</p>
                        </div>
                        <h2 class="mt-3 text-2xl font-bold">
                            {{ Str::startsWith($avg_session_duration, '00:') ? substr($avg_session_duration, 3) : $avg_session_duration }}
                        </h2>
                    </div>
                </div>
            </div>

            <div class="hover hover-3d">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4">
                        <div class="flex items-center justify-between font-bold">
                            <p class="text-sm text-base-content/70">Bounce rate</p>
                        </div>
                        <h2 class="mt-3 text-2xl font-bold">{{ $bounce_rate }}%</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 space-y-6">
            <div class="card bg-base-100 shadow-md">
                <div class="card-body p-4 sm:p-6">
                    <h2 class="mb-4 text-xl font-semibold text-base-content">Visits trend</h2>
                    <div class="h-80">
                        {!! $charts['line']->container() !!}
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4 sm:p-6">
                        <h2 class="mb-4 text-xl font-semibold text-base-content">Top 10 most visited pages</h2>
                        <div class="h-80">
                            {!! $charts['bar']->container() !!}
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4 sm:p-6">
                        <h2 class="mb-4 text-xl font-semibold text-base-content">Most visited page links</h2>
                        <ul class="space-y-3">
                            @foreach ($top_pages as $page)
                                @php
                                    $url = $page['fullPageUrl'];

                                    if (!Str::startsWith($url, ['http://', 'https://'])) {
                                        $url = 'https://' . ltrim($url, '/');
                                    }
                                @endphp

                                <div class="hover hover-3d flex w-full">
                                    <li
                                        class="flex items-center justify-between gap-3 rounded-box bg-base-200/40 p-3 shadow-md w-full">
                                        <a href="{{ $url }}" target="_blank"
                                            class="link link-hover text-sm font-medium text-left">
                                            {{ $page['pageTitle'] }}
                                        </a>
                                        <span class="badge badge-ghost badge-sm">{{ $page['screenPageViews'] }} views</span>
                                    </li>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4 sm:p-6">
                        <h2 class="mb-4 text-xl font-semibold text-base-content">Visits by country</h2>
                        <div class="h-80">
                            {!! $charts['country_bar']->container() !!}
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4 sm:p-6">
                        <h2 class="mb-4 text-xl font-semibold text-base-content">Top devices</h2>
                        <div class="h-80">
                            {!! $charts['device_doughnut']->container() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4 sm:p-6">
                        <h2 class="mb-4 text-xl font-semibold text-base-content">Top referrers</h2>
                        <div class="h-80">
                            {!! $charts['referrer_pie']->container() !!}
                        </div>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-md">
                    <div class="card-body p-4 sm:p-6">
                        <h2 class="mb-4 text-xl font-semibold text-base-content">Top browsers</h2>
                        <div class="h-80">
                            {!! $charts['browser_bar']->container() !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 shadow-md">
                <div class="card-body p-4 sm:p-6">
                    <h2 class="mb-4 text-xl font-semibold text-base-content">Total vs. unique visitors</h2>
                    <div class="h-80">
                        {!! $charts['visits_users_trend_line']->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
        {!! $charts['line']->script() !!}
        {!! $charts['bar']->script() !!}
        {!! $charts['country_bar']->script() !!}
        {!! $charts['referrer_pie']->script() !!}
        {!! $charts['browser_bar']->script() !!}
        {!! $charts['device_doughnut']->script() !!}
        {!! $charts['visits_users_trend_line']->script() !!}

        <script>
            // Show/hide custom date fields based on selection
            document.getElementById('date_range_select').addEventListener('change', function () {
                if (this.value !== 'custom') {
                    this.form.submit();
                }
            });
        </script>
    @endpush

</x-layout>