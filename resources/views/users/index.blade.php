@extends('layouts.app')

@section('title', 'Users')
@section('breadcrumb', 'Users')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Users</h1>
        <div class="page-actions">
            <a href="{{ route('users.create') }}" class="btn btn-primary">+ Tambah User</a>
        </div>
    </div>

    @if(session('success'))
        <div class="card" style="margin-bottom: 24px; background: var(--color-success-soft); color: var(--color-success); border-color: rgba(22, 163, 74, 0.3);">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <form method="GET" action="{{ route('users.index') }}" class="search-wrapper">
            <input type="text"
                   name="search"
                   class="search-input"
                   placeholder="Cari user..."
                   value="{{ request('search') }}">
            <button type="submit" class="search-icon" aria-label="Cari user">
                🔍
            </button>
        </form>

        <table class="table-modern">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $u)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>
                            @if($u->role === 'admin')
                                <span class="badge badge-success">Admin</span>
                            @elseif($u->role === 'manajer')
                                <span class="badge badge-success">Manajer</span>
                            @else
                                <span class="badge">{{ ucfirst($u->role ?: 'tidak diketahui') }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-secondary btn-sm" title="Edit">✏️</a>
                            <button type="button"
                                    class="btn btn-secondary btn-sm js-open-delete"
                                    title="Hapus"
                                    data-delete-url="{{ route('users.destroy', $u->id) }}"
                                    data-delete-label="{{ $u->name . ' • ' . $u->email }}">
                                🗑️
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">Belum ada user</div>
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
                        Konfirmasi Hapus User
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
                    Kamu akan menghapus user berikut:
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
        const label = button.dataset.deleteLabel || 'User terpilih';
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
