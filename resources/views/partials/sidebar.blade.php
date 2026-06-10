@php $role = auth()->user()->role; @endphp

<aside id="sidebar"
       style="width: 240px;
              min-height: 100vh;
        height: 100vh;
              background: #ffffff;
              border-right: 1px solid var(--color-border);
              display: flex;
              flex-direction: column;
              position: fixed;
              top: 0; left: 0;
              z-index: 100;
        transition: all 0.3s ease;
        overflow: hidden;">

    {{-- LOGO --}}
    <div style="padding: 20px 20px 16px;
                border-bottom: 1px solid var(--color-border);
                display: flex;
                align-items: center;
                justify-content: center;">
        <img src="{{ asset('images/logo.png') }}"
             alt="Logo"
             style="height: 60px;
                    object-fit: contain;">
    </div>

    {{-- NAVIGATION --}}
    <nav style="flex: 1;
                min-height: 0;
                padding: 12px 12px 16px;
                overflow-y: auto;">

        {{-- Section: Main --}}
        <div style="margin-bottom: 4px;">
            <p style="font-size: 10px;
                      font-weight: 700;
                      color: var(--color-text-muted);
                      text-transform: uppercase;
                      letter-spacing: 0.08em;
                      padding: 0 10px;
                      margin-bottom: 6px;">
                Main
            </p>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               style="display: flex;
                      align-items: center;
                      gap: 10px;
                      padding: 10px 12px;
                      border-radius: 10px;
                      margin-bottom: 2px;
                      text-decoration: none;
                      font-size: 13px;
                      font-weight: 500;
                      transition: all 0.2s ease;
                      {{ request()->routeIs('dashboard') 
                          ? 'background: var(--color-primary); color: #ffffff;' 
                          : 'color: var(--color-text-muted);' }}"
               onmouseover="if(!this.style.background.includes('e11d48')) {
                   this.style.background='var(--color-surface-2)';
                   this.style.color='var(--color-text)';}"
               onmouseout="if(!this.style.background.includes('e11d48')) {
                   this.style.background='transparent';
                   this.style.color='var(--color-text-muted)';}">
                <span style="width: 32px; height: 32px;
                             border-radius: 8px;
                             display: flex;
                             align-items: center;
                             justify-content: center;
                             background: {{ request()->routeIs('dashboard') 
                                 ? 'rgba(255,255,255,0.2)' 
                                 : 'var(--color-surface-2)' }};
                             flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none"
                         stroke="{{ request()->routeIs('dashboard') 
                             ? '#ffffff' : 'var(--color-text-muted)' }}"
                         stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                    </svg>
                </span>
                Dashboard
            </a>
        </div>

        {{-- Section: Data --}}
        <div style="margin-bottom: 4px; margin-top: 16px;">
            <p style="font-size: 10px;
                      font-weight: 700;
                      color: var(--color-text-muted);
                      text-transform: uppercase;
                      letter-spacing: 0.08em;
                      padding: 0 10px;
                      margin-bottom: 6px;">
                Data
            </p>

            @if($role === 'admin')
            {{-- Customer --}}
            <a href="{{ route('customers.index') }}"
               style="display: flex;
                      align-items: center;
                      gap: 10px;
                      padding: 10px 12px;
                      border-radius: 10px;
                      margin-bottom: 2px;
                      text-decoration: none;
                      font-size: 13px;
                      font-weight: 500;
                      transition: all 0.2s ease;
                      {{ request()->routeIs('customers.*') 
                          ? 'background: var(--color-primary); color: #ffffff;' 
                          : 'color: var(--color-text-muted);' }}"
               onmouseover="if(!this.style.background.includes('e11d48')) {
                   this.style.background='var(--color-surface-2)';
                   this.style.color='var(--color-text)';}"
               onmouseout="if(!this.style.background.includes('e11d48')) {
                   this.style.background='transparent';
                   this.style.color='var(--color-text-muted)';}">
                <span style="width: 32px; height: 32px;
                             border-radius: 8px;
                             display: flex;
                             align-items: center;
                             justify-content: center;
                             background: {{ request()->routeIs('customers.*') 
                                 ? 'rgba(255,255,255,0.2)' 
                                 : 'var(--color-surface-2)' }};
                             flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none"
                         stroke="{{ request()->routeIs('customers.*') 
                             ? '#ffffff' : 'var(--color-text-muted)' }}"
                         stroke-width="2" stroke-linecap="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 00-3-3.87"/>
                        <path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </span>
                Customer
                {{-- Badge jumlah --}}
                @php $totalCustomer = \App\Models\Customer::count(); @endphp
                @if($totalCustomer > 0)
                    <span style="margin-left: auto;
                                 background: {{ request()->routeIs('customers.*') 
                                     ? 'rgba(255,255,255,0.25)' 
                                     : 'var(--color-primary-soft)' }};
                                 color: {{ request()->routeIs('customers.*') 
                                     ? '#ffffff' 
                                     : 'var(--color-primary)' }};
                                 font-size: 11px;
                                 font-weight: 700;
                                 padding: 2px 7px;
                                 border-radius: 20px;">
                        {{ $totalCustomer }}
                    </span>
                @endif
            </a>
            @endif

            @if($role === 'admin')
            {{-- OLT --}}
            <a href="{{ route('olts.index') }}"
               style="display: flex;
                      align-items: center;
                      gap: 10px;
                      padding: 10px 12px;
                      border-radius: 10px;
                      margin-bottom: 2px;
                      text-decoration: none;
                      font-size: 13px;
                      font-weight: 500;
                      transition: all 0.2s ease;
                      {{ request()->routeIs('olts.*') 
                          ? 'background: var(--color-primary); color: #ffffff;' 
                          : 'color: var(--color-text-muted);' }}"
               onmouseover="if(!this.style.background.includes('e11d48')) {
                   this.style.background='var(--color-surface-2)';
                   this.style.color='var(--color-text)';}"
               onmouseout="if(!this.style.background.includes('e11d48')) {
                   this.style.background='transparent';
                   this.style.color='var(--color-text-muted)';}">
                <span style="width: 32px; height: 32px;
                             border-radius: 8px;
                             display: flex;
                             align-items: center;
                             justify-content: center;
                             background: {{ request()->routeIs('olts.*') 
                                 ? 'rgba(255,255,255,0.2)' 
                                 : 'var(--color-surface-2)' }};
                             flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none"
                         stroke="{{ request()->routeIs('olts.*') 
                             ? '#ffffff' : 'var(--color-text-muted)' }}"
                         stroke-width="2" stroke-linecap="round">
                        <rect x="2" y="2" width="20" height="8" rx="2"/>
                        <rect x="2" y="14" width="20" height="8" rx="2"/>
                        <line x1="6" y1="6" x2="6.01" y2="6"/>
                        <line x1="6" y1="18" x2="6.01" y2="18"/>
                    </svg>
                </span>
                OLT
                @php $oltCount = \App\Models\Olt::count(); @endphp
                @if($oltCount > 0)
                    <span style="margin-left: auto;
                                 background: {{ request()->routeIs('olts.*') 
                                     ? 'rgba(255,255,255,0.25)' 
                                     : 'var(--color-primary-soft)' }};
                                 color: {{ request()->routeIs('olts.*') 
                                     ? '#ffffff' 
                                     : 'var(--color-primary)' }};
                                 font-size: 11px;
                                 font-weight: 700;
                                 padding: 2px 7px;
                                 border-radius: 20px;">
                        {{ $oltCount }}
                    </span>
                @endif
            </a>
            @endif

        </div>

        {{-- Section: Analisis (admin only) --}}
        <div style="margin-bottom: 4px; margin-top: 16px;">
            <p style="font-size: 10px;
                      font-weight: 700;
                      color: var(--color-text-muted);
                      text-transform: uppercase;
                      letter-spacing: 0.08em;
                      padding: 0 10px;
                      margin-bottom: 6px;">
                Analisis
            </p>

            {{-- Monitoring --}}
            <a href="{{ route('monitoring') }}"
               style="display: flex;
                      align-items: center;
                      gap: 10px;
                      padding: 10px 12px;
                      border-radius: 10px;
                      margin-bottom: 2px;
                      text-decoration: none;
                      font-size: 13px;
                      font-weight: 500;
                      transition: all 0.2s ease;
                      {{ request()->routeIs('monitoring') 
                          ? 'background: var(--color-primary); color: #ffffff;' 
                          : 'color: var(--color-text-muted);' }}"
               onmouseover="if(!this.style.background.includes('e11d48')) {
                   this.style.background='var(--color-surface-2)';
                   this.style.color='var(--color-text)';}"
               onmouseout="if(!this.style.background.includes('e11d48')) {
                   this.style.background='transparent';
                   this.style.color='var(--color-text-muted)';}">
                <span style="width: 32px; height: 32px;
                             border-radius: 8px;
                             display: flex;
                             align-items: center;
                             justify-content: center;
                             background: {{ request()->routeIs('monitoring') 
                                 ? 'rgba(255,255,255,0.2)' 
                                 : 'var(--color-surface-2)' }};
                             flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none"
                         stroke="{{ request()->routeIs('monitoring') 
                             ? '#ffffff' : 'var(--color-text-muted)' }}"
                         stroke-width="2" stroke-linecap="round">
                        <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
                    </svg>
                </span>
                Monitoring
            </a>

            {{-- Prediksi --}}
            <a href="{{ route('prediction') }}"
               style="display: flex;
                      align-items: center;
                      gap: 10px;
                      padding: 10px 12px;
                      border-radius: 10px;
                      margin-bottom: 2px;
                      text-decoration: none;
                      font-size: 13px;
                      font-weight: 500;
                      transition: all 0.2s ease;
                      {{ request()->routeIs('prediction') 
                          ? 'background: var(--color-primary); color: #ffffff;' 
                          : 'color: var(--color-text-muted);' }}"
               onmouseover="if(!this.style.background.includes('e11d48')) {
                   this.style.background='var(--color-surface-2)';
                   this.style.color='var(--color-text)';}"
               onmouseout="if(!this.style.background.includes('e11d48')) {
                   this.style.background='transparent';
                   this.style.color='var(--color-text-muted)';}">
                <span style="width: 32px; height: 32px;
                             border-radius: 8px;
                             display: flex;
                             align-items: center;
                             justify-content: center;
                             background: {{ request()->routeIs('prediction') 
                                 ? 'rgba(255,255,255,0.2)' 
                                 : 'var(--color-surface-2)' }};
                             flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none"
                         stroke="{{ request()->routeIs('prediction') 
                             ? '#ffffff' : 'var(--color-text-muted)' }}"
                         stroke-width="2" stroke-linecap="round">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                        <line x1="2" y1="20" x2="22" y2="20"/>
                    </svg>
                </span>
                Prediksi
            </a>

            @if($role === 'admin' || $role === 'manajer')
            {{-- Evaluation --}}
            <a href="{{ route('evaluation') }}"
               style="display: flex;
                      align-items: center;
                      gap: 10px;
                      padding: 10px 12px;
                      border-radius: 10px;
                      margin-bottom: 2px;
                      text-decoration: none;
                      font-size: 13px;
                      font-weight: 500;
                      transition: all 0.2s ease;
                      {{ request()->routeIs('evaluation') 
                          ? 'background: var(--color-primary); color: #ffffff;' 
                          : 'color: var(--color-text-muted);' }}"
               onmouseover="if(!this.style.background.includes('e11d48')) {
                   this.style.background='var(--color-surface-2)';
                   this.style.color='var(--color-text)';}"
               onmouseout="if(!this.style.background.includes('e11d48')) {
                   this.style.background='transparent';
                   this.style.color='var(--color-text-muted)';}">
                <span style="width: 32px; height: 32px;
                             border-radius: 8px;
                             display: flex;
                             align-items: center;
                             justify-content: center;
                             background: {{ request()->routeIs('evaluation') 
                                 ? 'rgba(255,255,255,0.2)' 
                                 : 'var(--color-surface-2)' }};
                             flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none"
                         stroke="{{ request()->routeIs('evaluation') 
                             ? '#ffffff' : 'var(--color-text-muted)' }}"
                         stroke-width="2" stroke-linecap="round">
                        <polyline points="9 11 12 14 22 4"/>
                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 
                                 0 01-2-2V5a2 2 0 012-2h11"/>
                    </svg>
                </span>
                Evaluation
            </a>

            @endif

        </div>

        {{-- Section: Laporan --}}
        <div style="margin-bottom: 4px; margin-top: 16px;">
            <p style="font-size: 10px;
                      font-weight: 700;
                      color: var(--color-text-muted);
                      text-transform: uppercase;
                      letter-spacing: 0.08em;
                      padding: 0 10px;
                      margin-bottom: 6px;">
                Laporan
            </p>

            {{-- Reports --}}
            <a href="{{ route('reports') }}"
               style="display: flex;
                      align-items: center;
                      gap: 10px;
                      padding: 10px 12px;
                      border-radius: 10px;
                      margin-bottom: 2px;
                      text-decoration: none;
                      font-size: 13px;
                      font-weight: 500;
                      transition: all 0.2s ease;
                      {{ request()->routeIs('reports') 
                          ? 'background: var(--color-primary); color: #ffffff;' 
                          : 'color: var(--color-text-muted);' }}"
               onmouseover="if(!this.style.background.includes('e11d48')) {
                   this.style.background='var(--color-surface-2)';
                   this.style.color='var(--color-text)';}"
               onmouseout="if(!this.style.background.includes('e11d48')) {
                   this.style.background='transparent';
                   this.style.color='var(--color-text-muted)';}">
                <span style="width: 32px; height: 32px;
                             border-radius: 8px;
                             display: flex;
                             align-items: center;
                             justify-content: center;
                             background: {{ request()->routeIs('reports') 
                                 ? 'rgba(255,255,255,0.2)' 
                                 : 'var(--color-surface-2)' }};
                             flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none"
                         stroke="{{ request()->routeIs('reports') 
                             ? '#ffffff' : 'var(--color-text-muted)' }}"
                         stroke-width="2" stroke-linecap="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 
                                 0 002 2h12a2 2 0 002-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </span>
                Laporan
            </a>

        </div>

        {{-- Section: Pengaturan (admin only) --}}
        @if($role === 'admin')
        <div style="margin-top: 16px;">
            <p style="font-size: 10px;
                      font-weight: 700;
                      color: var(--color-text-muted);
                      text-transform: uppercase;
                      letter-spacing: 0.08em;
                      padding: 0 10px;
                      margin-bottom: 6px;">
                Pengaturan
            </p>

            {{-- Kelola User --}}
            <a href="{{ route('users.index') }}"
               style="display: flex;
                      align-items: center;
                      gap: 10px;
                      padding: 10px 12px;
                      border-radius: 10px;
                      margin-bottom: 2px;
                      text-decoration: none;
                      font-size: 13px;
                      font-weight: 500;
                      transition: all 0.2s ease;
                      {{ request()->routeIs('users.*') 
                          ? 'background: var(--color-primary); color: #ffffff;' 
                          : 'color: var(--color-text-muted);' }}"
               onmouseover="if(!this.style.background.includes('e11d48')) {
                   this.style.background='var(--color-surface-2)';
                   this.style.color='var(--color-text)';}"
               onmouseout="if(!this.style.background.includes('e11d48')) {
                   this.style.background='transparent';
                   this.style.color='var(--color-text-muted)';}">
                <span style="width: 32px; height: 32px;
                             border-radius: 8px;
                             display: flex;
                             align-items: center;
                             justify-content: center;
                             background: {{ request()->routeIs('users.*') 
                                 ? 'rgba(255,255,255,0.2)' 
                                 : 'var(--color-surface-2)' }};
                             flex-shrink: 0;">
                    <svg width="16" height="16" viewBox="0 0 24 24"
                         fill="none"
                         stroke="{{ request()->routeIs('users.*') 
                             ? '#ffffff' : 'var(--color-text-muted)' }}"
                         stroke-width="2" stroke-linecap="round">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                Kelola User
            </a>

        </div>
        @endif

    </nav>

    {{-- USER PROFILE DI BAWAH --}}
    <div style="padding: 12px;
                border-top: 1px solid var(--color-border);
                background: var(--color-surface-2);">

        <div style="font-size: 10px;
                    font-weight: 700;
                    color: var(--color-text-muted);
                    text-transform: uppercase;
                    letter-spacing: 0.08em;
                    padding: 0 8px 6px;">
            Profil
        </div>

        {{-- Profile link --}}
        <a href="{{ route('profile.index') }}"
           style="display: flex;
                  align-items: center;
                  gap: 10px;
                  padding: 10px 12px;
                  border-radius: 12px;
                  text-decoration: none;
                  transition: all 0.2s ease;
                  border: 1px solid var(--color-border);
                  background: #ffffff;
                  {{ request()->routeIs('profile.*') 
                      ? 'box-shadow: var(--shadow);' 
                      : '' }}"
           onmouseover="this.style.background='var(--color-primary-soft)'"
           onmouseout="this.style.background='#ffffff'">

            {{-- Avatar --}}
            <div style="width: 34px; height: 34px;
                        border-radius: 50%;
                        background: var(--color-primary);
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: white;
                        font-size: 13px;
                        font-weight: 700;
                        flex-shrink: 0;">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div style="flex: 1; min-width: 0;">
                <div style="font-size: 13px;
                            font-weight: 600;
                            color: var(--color-text);
                            white-space: nowrap;
                            overflow: hidden;
                            text-overflow: ellipsis;">
                    {{ auth()->user()->name }}
                </div>
                <div style="font-size: 11px;
                            color: var(--color-text-muted);
                            text-transform: capitalize;">
                    {{ auth()->user()->role }}
                </div>
            </div>

            <svg width="14" height="14" viewBox="0 0 24 24"
                 fill="none" stroke="var(--color-text-muted)"
                 stroke-width="2">
                <polyline points="9 18 15 12 9 6"/>
            </svg>
        </a>

    </div>

</aside>
