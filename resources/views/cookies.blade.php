<x-layout>
    <x-slot:title>
        Cookies Policy
    </x-slot:title>
    <h2>How do we use cookies?</h2>
    <div class="overflow-x-auto">
        @foreach(Cookies::getCategories() as $category)
            <table class="table table-zebra">
                <caption>{{ $category->title }}</caption>
                <div class="divider"></div>
                <thead>
                    <tr>
                        <th>Cookie</th>
                        <th>Description</th>
                        <th>Duration</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->getCookies() as $cookie)
                        <tr>
                            <td>{{ $cookie->name }}</td>
                            <td>{{ $cookie->description }}</td>
                            <td>{{ \Carbon\Carbon::now()->diffForHumans(\Carbon\Carbon::now()->addMinutes($cookie->duration), true) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
        <div class="m-2">
            @cookieconsentbutton(action: 'reset', label: 'Reset cookies', attributes: ['id' => 'reset-button', 'class' => 'btn btn-neutral'])
        </div>
    </div>
</x-layout>