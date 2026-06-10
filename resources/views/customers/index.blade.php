@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Customers</h1>
        <div class="page-actions">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">+ Tambah Customer</a>
        </div>
    </div>

    @if(session('success'))
        <div class="card" style="margin-bottom: 24px; background: var(--color-success-soft); color: var(--color-success); border-color: rgba(22, 163, 74, 0.3);">
            {{ session('success') }}
        </div>
    @endif

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" 
              action="{{ route('customers.index') }}"
              style="display: flex; 
                     gap: 12px; 
                     align-items: flex-end;
                     flex-wrap: wrap;">

            {{-- Search --}}
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block;
                              font-size: 12px;
                              font-weight: 600;
                              color: var(--color-text-muted);
                              margin-bottom: 6px;
                              text-transform: uppercase;
                              letter-spacing: 0.05em;">
                    Cari
                </label>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari customer..."
                       style="width: 100%;
                              padding: 10px 16px;
                              border: 1px solid var(--color-border);
                              border-radius: 10px;
                              background: var(--color-surface-2);
                              font-size: 14px;">
            </div>

            {{-- Filter OLT --}}
            <div>
                <label style="display: block;
                              font-size: 12px;
                              font-weight: 600;
                              color: var(--color-text-muted);
                              margin-bottom: 6px;
                              text-transform: uppercase;
                              letter-spacing: 0.05em;">
                    OLT
                </label>
                <select name="olt_id"
                        style="padding: 10px 16px;
                               border: 1px solid var(--color-border);
                               border-radius: 10px;
                               background: var(--color-surface);
                               font-size: 14px;
                               color: var(--color-text);
                               cursor: pointer;
                               min-width: 180px;">
                    <option value="">Semua OLT</option>
                    @foreach($olts as $olt)
                        <option value="{{ $olt->id }}"
                            {{ request('olt_id') == $olt->id 
                                ? 'selected' : '' }}>
                            {{ $olt->hostname }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tahun --}}
            <div>
                <label style="display: block;
                              font-size: 12px;
                              font-weight: 600;
                              color: var(--color-text-muted);
                              margin-bottom: 6px;
                              text-transform: uppercase;
                              letter-spacing: 0.05em;">
                    Tahun
                </label>
                <select name="year"
                        style="padding: 10px 16px;
                               border: 1px solid var(--color-border);
                               border-radius: 10px;
                               background: var(--color-surface);
                               font-size: 14px;
                               color: var(--color-text);
                               cursor: pointer;
                               min-width: 130px;">
                    <option value="">Semua Tahun</option>
                    @foreach($years as $year)
                        <option value="{{ $year }}"
                            {{ request('year') == $year 
                                ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Urutkan --}}
            <div>
                <label style="display: block;
                              font-size: 12px;
                              font-weight: 600;
                              color: var(--color-text-muted);
                              margin-bottom: 6px;
                              text-transform: uppercase;
                              letter-spacing: 0.05em;">
                    Urutkan
                </label>
                <select name="urutan"
                        style="padding: 10px 16px;
                               border: 1px solid var(--color-border);
                               border-radius: 10px;
                               background: var(--color-surface);
                               font-size: 14px;
                               color: var(--color-text);
                               cursor: pointer;
                               min-width: 180px;">
                    <option value="">Default</option>
                    <option value="tahun_terbaru"
                        {{ request('urutan') === 'tahun_terbaru' 
                            ? 'selected' : '' }}>
                        Tahun Terbaru
                    </option>
                    <option value="tahun_terlama"
                        {{ request('urutan') === 'tahun_terlama' 
                            ? 'selected' : '' }}>
                        Tahun Terlama
                    </option>
                    <option value="total_terbanyak"
                        {{ request('urutan') === 'total_terbanyak' 
                            ? 'selected' : '' }}>
                        Total Terbanyak
                    </option>
                    <option value="total_tersedikit"
                        {{ request('urutan') === 'total_tersedikit' 
                            ? 'selected' : '' }}>
                        Total Tersedikit
                    </option>
                </select>
            </div>

            {{-- Tombol --}}
            <button type="submit" class="btn btn-primary"
                    style="padding: 10px 20px;">
                Filter
            </button>

            {{-- Reset --}}
            @if(request()->anyFilled([
                'search','olt_id','year','urutan'
            ]))
                <a href="{{ route('customers.index') }}"
                   style="padding: 10px 16px;
                          border: 1px solid var(--color-border);
                          border-radius: 10px;
                          color: var(--color-text-muted);
                          text-decoration: none;
                          font-size: 14px;
                          white-space: nowrap;">
                    Reset
                </a>
            @endif

        </form>
    </div>

    <div class="card">
        {{-- Info hasil filter --}}
        @if(request()->anyFilled(['search','olt_id','year','urutan']))
            <div style="margin-bottom: 12px;
                        font-size: 13px;
                        color: var(--color-text-muted);">
                Menampilkan {{ $customers->count() }} data
                @if(request('olt_id'))
                    · OLT: <strong style="color: var(--color-text);">
                        {{ $olts->find(request('olt_id'))->hostname ?? '' }}
                    </strong>
                @endif
                @if(request('year'))
                    · Tahun: <strong style="color: var(--color-text);">
                        {{ request('year') }}
                    </strong>
                @endif
            </div>
        @endif

        <table class="table-modern">
            <thead>
                <tr>
                    <th>No</th>
                    <th>OLT</th>
                    <th>Month</th>
                    <th>Year</th>
                    <th>B2C</th>
                    <th>B2B</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($customers as $index => $c)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $c->olt->hostname ?? '-' }}</td>
                        <td>{{ $c->month }}</td>
                        <td>{{ $c->year }}</td>
                        <td>{{ $c->b2c }}</td>
                        <td>{{ $c->b2b }}</td>
                        <td>{{ $c->total_customers }}</td>
                        <td>
                            <a href="{{ route('customers.edit', $c->id) }}" class="btn btn-secondary btn-sm" title="Edit">✏️</a>
                            <button type="button"
                                    class="btn btn-secondary btn-sm js-open-delete"
                                    title="Hapus"
                                    data-delete-url="{{ route('customers.destroy', $c->id) }}"
                                    data-delete-label="{{ ($c->olt->hostname ?? '-') . ' • ' . $c->month . ' ' . $c->year }}">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">Belum ada data customer</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="deleteModal"
         role="dialog"
         aria-modal="true"
         aria-labelledby="deleteModalTitle"
         aria-hidden="true"
         style="position: fixed;
                inset: 0;
                display: none;
                align-items: center;
                justify-content: center;
                padding: 24px;
                background: rgba(15, 23, 42, 0.55);
                backdrop-filter: blur(6px);
                z-index: 2000;">
        <div style="width: min(520px, 100%);
                    background: var(--color-surface);
                    border-radius: 20px;
                    border: 1px solid rgba(226, 232, 240, 0.9);
                    box-shadow: 0 20px 50px rgba(15, 23, 42, 0.18);
                    overflow: hidden;">
            <div style="padding: 20px 24px;
                        background: linear-gradient(135deg, #fff1f2, #ffe4e6 45%, #fff7ed);
                        border-bottom: 1px solid rgba(226, 232, 240, 0.9);
                        display: flex;
                        gap: 16px;
                        align-items: center;">
                <div style="width: 44px;
                            height: 44px;
                            border-radius: 14px;
                            background: #fee2e2;
                            color: #b91c1c;
                            display: grid;
                            place-items: center;
                            font-size: 20px;
                            box-shadow: inset 0 0 0 1px rgba(185, 28, 28, 0.15);">
                    ⚠️
                </div>
                <div>
                    <h3 id="deleteModalTitle" style="margin: 0;
                                                     font-size: 18px;
                                                     font-weight: 700;
                                                     color: #7f1d1d;">
                        Konfirmasi Hapus Customer
                    </h3>
                    <p style="margin: 4px 0 0;
                              font-size: 13px;
                              color: #9f1239;">
                        Tindakan ini tidak bisa dibatalkan.
                    </p>
                </div>
            </div>

            <div style="padding: 20px 24px;">
                <p style="margin: 0 0 12px;
                          color: var(--color-text);
                          font-size: 14px;">
                    Kamu akan menghapus data berikut:
                </p>
                <div id="deleteModalTarget"
                     style="display: inline-flex;
                            align-items: center;
                            gap: 8px;
                            padding: 8px 12px;
                            background: var(--color-surface-2);
                            border: 1px dashed var(--color-border);
                            border-radius: 12px;
                            font-size: 13px;
                            color: var(--color-text-muted);">
                    -
                </div>
            </div>

            <div style="padding: 18px 24px 22px;
                        display: flex;
                        justify-content: flex-end;
                        gap: 10px;
                        background: #fffafb;">
                <button type="button"
                        class="btn btn-secondary"
                        id="deleteModalCancel">
                    Batal
                </button>
                <form id="deleteModalForm" method="POST" style="margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn"
                            style="background: var(--color-danger);
                                   color: #ffffff;
                                   border: none;">
                        Hapus Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
const deleteModal = document.getElementById('deleteModal');
const deleteModalTarget = document.getElementById('deleteModalTarget');
const deleteModalForm = document.getElementById('deleteModalForm');
const deleteModalCancel = document.getElementById('deleteModalCancel');
const deleteButtons = document.querySelectorAll('.js-open-delete');

function openDeleteModal(url, label) {
    deleteModalForm.setAttribute('action', url);
    deleteModalTarget.textContent = label;
    deleteModal.style.display = 'flex';
    deleteModal.setAttribute('aria-hidden', 'false');
}

function closeDeleteModal() {
    deleteModal.style.display = 'none';
    deleteModal.setAttribute('aria-hidden', 'true');
}

deleteButtons.forEach(button => {
    button.addEventListener('click', () => {
        const url = button.dataset.deleteUrl;
        const label = button.dataset.deleteLabel || 'Data terpilih';
        openDeleteModal(url, label);
    });
});

deleteModalCancel.addEventListener('click', closeDeleteModal);
deleteModal.addEventListener('click', (event) => {
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
});
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && deleteModal.getAttribute('aria-hidden') === 'false') {
        closeDeleteModal();
    }
});
</script>
@endpush
