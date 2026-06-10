@extends('layouts.app')

@section('title', 'OLTs')

@section('content')
    <div class="page-header">
        <h1 class="page-title">OLTs</h1>
        <div class="page-actions">
            <a href="{{ route('olts.create') }}" class="btn btn-primary">+ Tambah OLT</a>
        </div>
    </div>

    @if(session('success'))
        <div class="card" style="margin-bottom: 24px; background: var(--color-success-soft); color: var(--color-success); border-color: rgba(22, 163, 74, 0.3);">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div style="display: flex; gap: 12px; 
                    margin-bottom: 20px; 
                    flex-wrap: wrap;">

            {{-- Search Input --}}
            <div style="flex: 1; min-width: 200px;">
                <input type="text" 
                       id="searchInput"
                       placeholder="Cari OLT..."
                       style="width: 100%;
                              padding: 10px 16px;
                              border: 1px solid var(--color-border);
                              border-radius: 20px;
                              background: var(--color-surface-2);
                              font-size: 14px;">
            </div>

            {{-- Filter Status --}}
            <select id="filterStatus"
                    style="padding: 10px 16px;
                           border: 1px solid var(--color-border);
                           border-radius: 10px;
                           background: var(--color-surface);
                           font-size: 14px;
                           color: var(--color-text);
                           cursor: pointer;">
                <option value="">Semua Status</option>
                <option value="active">Active</option>
                <option value="maintenance">Maintenance</option>
                <option value="non-active">Non-Active</option>
            </select>

            {{-- Filter Lokasi --}}
            <select id="filterLokasi"
                    style="padding: 10px 16px;
                           border: 1px solid var(--color-border);
                           border-radius: 10px;
                           background: var(--color-surface);
                           font-size: 14px;
                           color: var(--color-text);
                           cursor: pointer;">
                <option value="">Semua Lokasi</option>
                @foreach($olts->pluck('location')->unique()->filter() as $lokasi)
                    <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                @endforeach
            </select>

        </div>

        <table class="table-modern">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Hostname OLT</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($olts as $index => $olt)
                    <tr data-status="{{ $olt->status }}" 
                        data-lokasi="{{ $olt->location }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $olt->hostname }}</td>
                        <td>{{ $olt->location }}</td>
                        <td>
                            @if($olt->status === 'active')
                                <span style="background: var(--color-success-soft);
                                             color: var(--color-success);
                                             padding: 4px 12px;
                                             border-radius: 20px;
                                             font-size: 12px;
                                             font-weight: 500;">
                                    ● Active
                                </span>
                            @elseif($olt->status === 'maintenance')
                                <span style="background: var(--color-warning-soft);
                                             color: var(--color-warning);
                                             padding: 4px 12px;
                                             border-radius: 20px;
                                             font-size: 12px;
                                             font-weight: 500;">
                                    ● Maintenance
                                </span>
                            @else
                                <span style="background: var(--color-danger-soft);
                                             color: var(--color-danger);
                                             padding: 4px 12px;
                                             border-radius: 20px;
                                             font-size: 12px;
                                             font-weight: 500;">
                                    ● Non-Active
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('olts.edit', $olt->id) }}" class="btn btn-secondary btn-sm" title="Edit">✏️</a>
                            <button type="button"
                                    class="btn btn-secondary btn-sm js-open-delete"
                                    title="Hapus"
                                    data-delete-url="{{ route('olts.destroy', $olt->id) }}"
                                    data-delete-label="{{ $olt->hostname . ' • ' . $olt->location }}">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">Belum ada data OLT</div>
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
                        Konfirmasi Hapus OLT
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
                    Kamu akan menghapus OLT berikut:
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
const searchInput = document.getElementById('searchInput');
const filterStatus = document.getElementById('filterStatus');
const filterLokasi = document.getElementById('filterLokasi');
const rows = document.querySelectorAll('tbody tr');

function applyFilter() {
    const search = searchInput.value.toLowerCase();
    const status = filterStatus.value.toLowerCase();
    const lokasi = filterLokasi.value.toLowerCase();

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const rowStatus = row.dataset.status.toLowerCase();
        const rowLokasi = row.dataset.lokasi.toLowerCase();

        const matchSearch = text.includes(search);
        const matchStatus = status === '' || rowStatus === status;
        const matchLokasi = lokasi === '' || rowLokasi === lokasi;

        row.style.display = matchSearch && matchStatus && matchLokasi 
            ? '' : 'none';
    });
}

searchInput.addEventListener('input', applyFilter);
filterStatus.addEventListener('change', applyFilter);
filterLokasi.addEventListener('change', applyFilter);
</script>

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
        const label = button.dataset.deleteLabel || 'OLT terpilih';
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
