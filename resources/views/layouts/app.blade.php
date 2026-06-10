<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FiberOptic Monitor') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-body">
    <div class="app-shell">
        @include('partials.sidebar')

        <div class="main-pane">
            @include('partials.header')

            <main class="content-area">
                @yield('content')
            </main>
        </div>
    </div>
    @auth
        @if(auth()->user()->role === 'admin')
            <span id="unreadUrl" data-unread-url="{{ route('notifications.unreadCount') }}" hidden></span>
            @push('scripts')
                <script>
                    const unreadUrlEl = document.getElementById('unreadUrl');
                    const unreadUrl = unreadUrlEl?.dataset.unreadUrl;
                    if (!unreadUrl) {
                        return;
                    }

                    setInterval(function() {
                        fetch(unreadUrl)
                            .then(r => r.json())
                            .then(data => {
                                const badge = document.getElementById('notifBadge');
                                if (data.count > 0) {
                                    if (badge) {
                                        badge.textContent = data.count > 99
                                            ? '99+'
                                            : data.count;
                                        badge.style.display = 'flex';
                                    }
                                } else {
                                    if (badge) badge.style.display = 'none';
                                }
                            })
                            .catch(() => {});
                    }, 30000);
                </script>
            @endpush
        @endif
    @endauth
    @stack('scripts')
</body>
</html>
