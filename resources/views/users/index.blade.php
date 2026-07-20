@extends('layouts.app')
@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')
@section('page-subtitle', 'Kelola akun pengguna aplikasi')

@push('styles')
<style>
.user-avatar-lg{width:40px;height:40px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0;}
.role-admin{background:linear-gradient(135deg,#1a4fba,#2563eb);}
.role-petugas{background:linear-gradient(135deg,#059669,#10b981);}
.action-btns{display:flex;gap:6px;}
.user-row:hover{background:#f8faff;}

/* Modal */
.um-backdrop{position:fixed;inset:0;background:rgba(0,0,0,.55);backdrop-filter:blur(4px);z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;opacity:0;pointer-events:none;transition:opacity .25s;}
.um-backdrop.show{opacity:1;pointer-events:all;}
.um-modal{background:var(--bg-card);border-radius:16px;width:100%;max-width:460px;box-shadow:0 24px 60px rgba(0,0,0,.35);transform:translateY(24px) scale(.97);transition:transform .28s;overflow:hidden;}
.um-backdrop.show .um-modal{transform:translateY(0) scale(1);}
.um-modal-header{padding:20px 24px 16px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);}
.um-modal-header h5{font-size:16px;font-weight:700;display:flex;align-items:center;gap:8px;margin:0;}
.um-modal-close{width:32px;height:32px;border:none;background:var(--bg-body);border-radius:8px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--text-light);font-size:14px;transition:background .2s,color .2s;}
.um-modal-close:hover{background:var(--danger-light);color:var(--danger);}
.um-modal-body{padding:20px 24px;}
.um-modal-footer{padding:14px 24px;border-top:1px solid var(--border);display:flex;justify-content:flex-end;gap:8px;}

/* Toast */
.um-toast{position:fixed;bottom:28px;right:28px;padding:12px 20px;border-radius:10px;font-size:13.5px;font-weight:600;display:flex;align-items:center;gap:10px;z-index:99999;box-shadow:0 8px 24px rgba(0,0,0,.18);transform:translateY(60px);opacity:0;transition:transform .3s,opacity .3s;}
.um-toast.show{transform:translateY(0);opacity:1;}
.um-toast.success{background:#d1fae5;color:#065f46;border:1px solid #a7f3d0;}
.um-toast.error{background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;}

/* Spinner */
.um-spinner{width:16px;height:16px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:um-spin .6s linear infinite;display:none;flex-shrink:0;}
@keyframes um-spin{to{transform:rotate(360deg);}}
.um-btn-loading .um-spinner{display:inline-block;}
.um-btn-loading .btn-text{display:none;}

/* Search */
.um-search-wrap{display:flex;align-items:center;gap:10px;margin-bottom:16px;}
.um-search-input{flex:1;height:40px;border:1px solid var(--border);border-radius:8px;padding:0 14px 0 36px;font-size:13px;background:var(--bg-body);color:var(--text-dark);outline:none;transition:border-color .2s,box-shadow .2s;}
.um-search-input:focus{border-color:var(--primary);box-shadow:0 0 0 3px rgba(26,79,186,.1);}
.um-search-icon{position:relative;margin-right:-30px;z-index:1;color:var(--text-light);font-size:12px;padding-left:12px;pointer-events:none;}
.um-err-box{margin-bottom:14px;padding:10px 14px;border-radius:8px;background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;font-size:13px;display:none;}

/* Password toggle */
.pwd-wrap{position:relative;}
.pwd-wrap .form-control{padding-right:42px;}
.pwd-eye{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-light);font-size:15px;padding:4px;line-height:1;transition:color .2s;}
.pwd-eye:hover{color:var(--primary);}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
        <span>/</span><span>Manajemen User</span>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5><i class="fas fa-users text-primary"></i> Daftar Pengguna</h5>
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Tambah User
        </button>
    </div>
    <div class="card-body">
        <div class="um-search-wrap">
            <i class="fas fa-search um-search-icon"></i>
            <input type="text" id="userSearch" class="um-search-input"
                   placeholder="Cari nama atau username…" oninput="filterUsers(this.value)">
        </div>
        <div class="table-wrapper">
            <table id="userTable">
                <thead>
                    <tr>
                        <th style="width:40px">No.</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th style="width:160px">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTbody">
                @forelse($users as $i => $u)
                <tr class="user-row"
                    data-search="{{ strtolower($u->nama.' '.$u->username.' '.$u->role) }}">
                    <td class="text-muted" style="text-align:center">{{ $i+1 }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <span class="user-avatar-lg {{ $u->role==='admin' ? 'role-admin' : 'role-petugas' }}">
                                {{ strtoupper(substr($u->nama,0,1)) }}
                            </span>
                            <div>
                                <div style="font-weight:700;font-size:14px;">{{ $u->nama }}</div>
                                @if($u->id===auth()->id())
                                <span class="badge badge-warning" style="font-size:10px;">Anda</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td><code style="background:var(--bg-body);padding:2px 8px;border-radius:5px;font-size:13px;">{{ $u->username }}</code></td>
                    <td>
                        <span class="badge {{ $u->role==='admin' ? 'badge-primary' : 'badge-success' }}">
                            <i class="fas {{ $u->role==='admin' ? 'fa-shield-halved' : 'fa-user' }}"></i>
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td>
                        <div class="action-btns">
                            <button class="btn btn-warning btn-sm btn-icon" title="Edit"
                                    onclick="openEditModal({{ $u->id }})">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn btn-outline btn-sm btn-icon"
                                    style="border-color:#6366f1;color:#6366f1;" title="Ganti Password"
                                    onclick="openPwdModal({{ $u->id }},'{{ addslashes($u->nama) }}')">
                                <i class="fas fa-key"></i>
                            </button>
                            @if($u->id!==auth()->id())
                            <button class="btn btn-danger btn-sm btn-icon" title="Hapus"
                                    onclick="confirmDelete({{ $u->id }},'{{ addslashes($u->nama) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:var(--text-light);">
                        <i class="fas fa-users" style="font-size:32px;opacity:.3;display:block;margin-bottom:10px;"></i>
                        Belum ada pengguna.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="um-backdrop" id="addModal">
    <div class="um-modal" onclick="event.stopPropagation()">
        <div class="um-modal-header">
            <h5><i class="fas fa-user-plus text-primary"></i> Tambah User Baru</h5>
            <button class="um-modal-close" type="button" onclick="closeModal('addModal')"><i class="fas fa-times"></i></button>
        </div>
        <form id="addForm" autocomplete="off">
            @csrf
            <div class="um-modal-body">
                <div id="addErrors" class="um-err-box"></div>
                <div class="form-group">
                    <label class="form-label">Nama <span class="required">*</span></label>
                    <input type="text" name="nama" id="addNama" class="form-control" placeholder="Nama lengkap" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Username <span class="required">*</span></label>
                    <input type="text" name="username" id="addUsername" class="form-control" placeholder="Username unik" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password <span class="required">*</span></label>
                    <div class="pwd-wrap">
                        <input type="password" name="password" id="addPassword" class="form-control" placeholder="Min. 6 karakter" required>
                        <button type="button" class="pwd-eye" onclick="togglePwd('addPassword',this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                    <div class="pwd-wrap">
                        <input type="password" name="password_confirmation" id="addPasswordConfirm" class="form-control" placeholder="Ulangi password" required>
                        <button type="button" class="pwd-eye" onclick="togglePwd('addPasswordConfirm',this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                    </div>
                </div>
                <div class="form-group" style="margin-bottom:0">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" id="addRole" class="form-control" required>
                        <option value="petugas">Petugas</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>
            <div class="um-modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeModal('addModal')">Batal</button>
                <button type="button" class="btn btn-primary" id="addBtn" onclick="submitAdd()">
                    <span class="btn-text"><i class="fas fa-save"></i> Simpan</span>
                    <span class="um-spinner"></span>
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="um-backdrop" id="editModal">
    <div class="um-modal" onclick="event.stopPropagation()">
        <div class="um-modal-header">
            <h5><i class="fas fa-pen text-primary"></i> Edit User</h5>
            <button class="um-modal-close" type="button" onclick="closeModal('editModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="um-modal-body">
            <div id="editErrors" class="um-err-box"></div>
            <div class="form-group">
                <label class="form-label">Nama <span class="required">*</span></label>
                <input type="text" id="editNama" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Username <span class="required">*</span></label>
                <input type="text" id="editUsername" class="form-control" required>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Role <span class="required">*</span></label>
                <select id="editRole" class="form-control" required>
                    <option value="petugas">Petugas</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>
        <div class="um-modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('editModal')">Batal</button>
            <button type="button" class="btn btn-primary" id="editBtn" onclick="submitEdit()">
                <span class="btn-text"><i class="fas fa-save"></i> Simpan</span>
                <span class="um-spinner"></span>
            </button>
        </div>
    </div>
</div>

{{-- MODAL GANTI PASSWORD --}}
<div class="um-backdrop" id="pwdModal">
    <div class="um-modal" onclick="event.stopPropagation()">
        <div class="um-modal-header">
            <h5><i class="fas fa-key" style="color:#6366f1"></i> Ganti Password</h5>
            <button class="um-modal-close" type="button" onclick="closeModal('pwdModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="um-modal-body">
            <div id="pwdErrors" class="um-err-box"></div>
            <p id="pwdUserLabel" style="font-size:13px;color:var(--text-light);margin-bottom:16px;"></p>
            <div class="form-group">
                <label class="form-label">Password Baru <span class="required">*</span></label>
                <div class="pwd-wrap">
                    <input type="password" id="pwdNew" class="form-control" placeholder="Min. 6 karakter" required>
                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdNew',this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:0">
                <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                <div class="pwd-wrap">
                    <input type="password" id="pwdConfirm" class="form-control" placeholder="Ulangi password baru" required>
                    <button type="button" class="pwd-eye" onclick="togglePwd('pwdConfirm',this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                </div>
            </div>
        </div>
        <div class="um-modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('pwdModal')">Batal</button>
            <button type="button" class="btn btn-primary" id="pwdBtn" style="background:#6366f1;" onclick="submitPwd()">
                <span class="btn-text"><i class="fas fa-key"></i> Ubah Password</span>
                <span class="um-spinner"></span>
            </button>
        </div>
    </div>
</div>

{{-- MODAL HAPUS --}}
<div class="um-backdrop" id="deleteModal">
    <div class="um-modal" style="max-width:380px;" onclick="event.stopPropagation()">
        <div class="um-modal-header">
            <h5><i class="fas fa-triangle-exclamation" style="color:var(--danger)"></i> Hapus User</h5>
            <button class="um-modal-close" type="button" onclick="closeModal('deleteModal')"><i class="fas fa-times"></i></button>
        </div>
        <div class="um-modal-body">
            <p id="deleteMsg" style="font-size:14px;line-height:1.6;"></p>
        </div>
        <div class="um-modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeModal('deleteModal')">Batal</button>
            <button type="button" class="btn btn-danger" id="deleteBtn" onclick="submitDelete()">
                <span class="btn-text"><i class="fas fa-trash"></i> Ya, Hapus</span>
                <span class="um-spinner"></span>
            </button>
        </div>
    </div>
</div>

<div class="um-toast" id="umToast"></div>

@push('scripts')
<script>
const CSRF     = '{{ csrf_token() }}';
const BASE_URL = '{{ url("/users") }}';
let editId     = null;
let pwdId      = null;
let deleteId   = null;

/* ── Modal ─────────────────────────────── */
function openModal(id) {
    document.getElementById(id).classList.add('show');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('show');
}
// Tutup saat klik di luar modal
document.querySelectorAll('.um-backdrop').forEach(function(el) {
    el.addEventListener('click', function() { closeModal(el.id); });
});
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        ['addModal','editModal','pwdModal','deleteModal'].forEach(closeModal);
    }
});

/* ── Toast ──────────────────────────────── */
function showToast(msg, type) {
    type = type || 'success';
    var t = document.getElementById('umToast');
    t.className = 'um-toast ' + type;
    t.innerHTML = '<i class="fas fa-' + (type === 'success' ? 'circle-check' : 'circle-exclamation') + '"></i> ' + msg;
    t.classList.add('show');
    setTimeout(function() { t.classList.remove('show'); }, 3500);
}

/* ── Loading ────────────────────────────── */
function setLoading(btnId, on) {
    var btn = document.getElementById(btnId);
    if (!btn) return;
    btn.classList.toggle('um-btn-loading', on);
    btn.disabled = on;
}

/* ── Error box ──────────────────────────── */
function showErr(boxId, errors) {
    var el   = document.getElementById(boxId);
    var msgs = [];
    if (typeof errors === 'object') {
        Object.values(errors).forEach(function(v) {
            (Array.isArray(v) ? v : [v]).forEach(function(m) { msgs.push(m); });
        });
    } else {
        msgs.push(errors);
    }
    el.innerHTML = msgs.map(function(m) { return '<div>• ' + m + '</div>'; }).join('');
    el.style.display = 'block';
}
function clearErr(boxId) {
    var el = document.getElementById(boxId);
    el.style.display = 'none';
    el.innerHTML = '';
}

/* ── Password eye toggle ──────────────── */
function togglePwd(inputId, btn) {
    var inp  = document.getElementById(inputId);
    var icon = btn.querySelector('i');
    if (inp.type === 'password') {
        inp.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        inp.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

/* ── Search ─────────────────────────────── */
function filterUsers(q) {
    q = q.toLowerCase().trim();
    document.querySelectorAll('#userTbody .user-row').forEach(function(row) {
        row.style.display = (!q || row.dataset.search.includes(q)) ? '' : 'none';
    });
}

/* ── Fetch helper ───────────────────────── */
async function apiCall(url, method, data) {
    data.append('_token', CSRF);
    if (method !== 'POST') { data.append('_method', method); method = 'POST'; }
    var res  = await fetch(url, {
        method:  method,
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body:    data
    });
    var json = await res.json();
    return { ok: res.ok, status: res.status, json: json };
}

/* ── ADD ────────────────────────────────── */
function openAddModal() {
    ['addNama','addUsername','addPassword','addPasswordConfirm'].forEach(function(id) {
        document.getElementById(id).value = '';
    });
    document.getElementById('addRole').value = 'petugas';
    clearErr('addErrors');
    openModal('addModal');
}
async function submitAdd() {
    clearErr('addErrors');
    var fd = new FormData();
    fd.append('nama',                  document.getElementById('addNama').value.trim());
    fd.append('username',              document.getElementById('addUsername').value.trim());
    fd.append('password',              document.getElementById('addPassword').value);
    fd.append('password_confirmation', document.getElementById('addPasswordConfirm').value);
    fd.append('role',                  document.getElementById('addRole').value);

    if (!fd.get('nama') || !fd.get('username') || !fd.get('password')) {
        showErr('addErrors', {e: ['Semua field wajib diisi.']}); return;
    }
    setLoading('addBtn', true);
    try {
        var r = await apiCall(BASE_URL, 'POST', fd);
        if (!r.ok) { showErr('addErrors', r.json.errors || {e:[r.json.message || 'Gagal menyimpan.']}); return; }
        closeModal('addModal');
        showToast(r.json.message || 'User berhasil ditambahkan.');
        setTimeout(function() { location.reload(); }, 800);
    } catch(ex) {
        showErr('addErrors', {e:['Terjadi kesalahan jaringan: ' + ex.message]});
    } finally {
        setLoading('addBtn', false);
    }
}

/* ── EDIT ───────────────────────────────── */
async function openEditModal(id) {
    editId = id;
    clearErr('editErrors');
    openModal('editModal');
    setLoading('editBtn', true);
    try {
        var res  = await fetch(BASE_URL + '/' + id, { headers:{'Accept':'application/json','X-CSRF-TOKEN':CSRF} });
        var data = await res.json();
        document.getElementById('editNama').value     = data.nama;
        document.getElementById('editUsername').value = data.username;
        document.getElementById('editRole').value     = data.role;
    } catch(ex) {
        showToast('Gagal memuat data.', 'error');
        closeModal('editModal');
    } finally {
        setLoading('editBtn', false);
    }
}
async function submitEdit() {
    clearErr('editErrors');
    var fd = new FormData();
    fd.append('nama',     document.getElementById('editNama').value.trim());
    fd.append('username', document.getElementById('editUsername').value.trim());
    fd.append('role',     document.getElementById('editRole').value);

    if (!fd.get('nama') || !fd.get('username')) {
        showErr('editErrors', {e:['Nama dan username wajib diisi.']}); return;
    }
    setLoading('editBtn', true);
    try {
        var r = await apiCall(BASE_URL + '/' + editId, 'PUT', fd);
        if (!r.ok) { showErr('editErrors', r.json.errors || {e:[r.json.message || 'Gagal menyimpan.']}); return; }
        closeModal('editModal');
        showToast(r.json.message || 'User berhasil diperbarui.');
        setTimeout(function() { location.reload(); }, 800);
    } catch(ex) {
        showErr('editErrors', {e:['Terjadi kesalahan jaringan: ' + ex.message]});
    } finally {
        setLoading('editBtn', false);
    }
}

/* ── PASSWORD ───────────────────────────── */
function openPwdModal(id, nama) {
    pwdId = id;
    document.getElementById('pwdNew').value     = '';
    document.getElementById('pwdConfirm').value = '';
    document.getElementById('pwdUserLabel').textContent = 'Mengganti password untuk: ' + nama;
    clearErr('pwdErrors');
    openModal('pwdModal');
}
async function submitPwd() {
    clearErr('pwdErrors');
    var p1 = document.getElementById('pwdNew').value;
    var p2 = document.getElementById('pwdConfirm').value;
    if (!p1) { showErr('pwdErrors', {e:['Password wajib diisi.']}); return; }
    if (p1 !== p2) { showErr('pwdErrors', {e:['Konfirmasi password tidak cocok.']}); return; }

    var fd = new FormData();
    fd.append('password',              p1);
    fd.append('password_confirmation', p2);

    setLoading('pwdBtn', true);
    try {
        var r = await apiCall(BASE_URL + '/' + pwdId + '/password', 'PUT', fd);
        if (!r.ok) { showErr('pwdErrors', r.json.errors || {e:[r.json.message || 'Gagal mengubah.']}); return; }
        closeModal('pwdModal');
        showToast(r.json.message || 'Password berhasil diubah.');
    } catch(ex) {
        showErr('pwdErrors', {e:['Terjadi kesalahan jaringan: ' + ex.message]});
    } finally {
        setLoading('pwdBtn', false);
    }
}

/* ── DELETE ─────────────────────────────── */
function confirmDelete(id, nama) {
    deleteId = id;
    document.getElementById('deleteMsg').innerHTML =
        'Apakah Anda yakin ingin menghapus user <strong>' + nama + '</strong>? Tindakan ini tidak dapat dibatalkan.';
    openModal('deleteModal');
}
async function submitDelete() {
    if (!deleteId) return;
    setLoading('deleteBtn', true);
    try {
        var r = await apiCall(BASE_URL + '/' + deleteId, 'DELETE', new FormData());
        closeModal('deleteModal');
        if (!r.ok) { showToast(r.json.message || 'Gagal menghapus.', 'error'); return; }
        showToast(r.json.message || 'User berhasil dihapus.');
        setTimeout(function() { location.reload(); }, 800);
    } catch(ex) {
        showToast('Terjadi kesalahan jaringan.', 'error');
    } finally {
        setLoading('deleteBtn', false);
        deleteId = null;
    }
}
</script>
@endpush
@endsection
