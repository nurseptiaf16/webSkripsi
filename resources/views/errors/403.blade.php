@extends('layouts.app')
@section('content')
<div style="text-align:center; padding: 80px 20px;">
    <h1 style="color: var(--color-primary); 
               font-size: 64px; margin-bottom: 0;">
        403
    </h1>
    <p style="color: var(--color-text-muted); 
              font-size: 18px; margin: 16px 0 32px;">
        Anda tidak memiliki akses ke halaman ini.
    </p>
    <a href="{{ route('dashboard') }}"
       style="background: var(--color-primary);
              color: white; padding: 10px 24px;
              border-radius: var(--radius);
              text-decoration: none;">
        Kembali ke Dashboard
    </a>
</div>
@endsection