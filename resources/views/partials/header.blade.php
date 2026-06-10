<header class="app-header">
    <div class="header-left">
        <p class="greeting">Selamat datang, {{ Auth::user()->name }}! 👋</p>
        <h1 class="page-title">@yield('title', 'Dashboard')</h1>
    </div>
    <div class="header-right">
        @if(Auth::user()->role === 'admin')
            @php
                $unreadCount = \App\Models\Notification::where(
                    'is_read',
                    false
                )->count();
            @endphp

            <a href="{{ route('notifications.index') }}"
               id="notifBell"
               style="position: relative;
                      width: 40px; height: 40px;
                      background: var(--color-surface-2);
                      border-radius: 10px;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                      text-decoration: none;
                      transition: all 0.2s ease;"
               onmouseover="this.style.background='var(--color-primary-soft)'"
               onmouseout="this.style.background='var(--color-surface-2)'">

                <svg width="18" height="18" viewBox="0 0 24 24"
                     fill="none" stroke="var(--color-text-muted)"
                     stroke-width="2" stroke-linecap="round">
                    <path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 01-3.46 0"/>
                </svg>

                @if($unreadCount > 0)
                    <span id="notifBadge"
                          style="position: absolute;
                                 top: -4px; right: -4px;
                                 background: var(--color-danger);
                                 color: white;
                                 font-size: 10px;
                                 font-weight: 700;
                                 min-width: 18px;
                                 height: 18px;
                                 border-radius: 20px;
                                 display: flex;
                                 align-items: center;
                                 justify-content: center;
                                 padding: 0 4px;
                                 border: 2px solid white;">
                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                    </span>
                @endif
            </a>
        @endif
        <div class="user-avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="user-info">
            <span class="user-name">{{ Auth::user()->name }}</span>
            <span class="user-role">{{ ucfirst(Auth::user()->role) }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn-header">Logout</button>
        </form>
    </div>
</header>
