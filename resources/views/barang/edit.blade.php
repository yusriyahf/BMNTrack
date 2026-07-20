@extends('layouts.app')

@section('title', 'Edit Barang – ' . $barang->nama_barang)
@section('page-title', 'Edit Barang')

@push('styles')
<style>
.foto-drop-area {
    border: 2px dashed var(--border);
    border-radius: var(--radius-sm);
    padding: 20px;
    text-align: center;
    cursor: pointer; transition: var(--transition);
    color: var(--text-light);
}
.foto-drop-area:hover { border-color: var(--primary-light); color: var(--primary); }
.kondisi-toggle { display: flex; gap: 10px; }
.kondisi-opt {
    flex: 1; padding: 12px;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    cursor: pointer; text-align: center;
    transition: var(--transition); position: relative;
}
.kondisi-opt input { position: absolute; opacity: 0; }
.kondisi-opt-label { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.kondisi-opt-label i { font-size: 22px; }
.kondisi-opt-label span { font-size: 13px; font-weight: 600; }
.kondisi-opt.selected-baik { border-color: var(--success); background: #d1fae5; color: #065f46; }
.kondisi-opt.selected-rusak { border-color: var(--danger); background: var(--danger-light); color: #991b1b; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Edit Barang</h1>
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
        <span>/</span> <a href="{{ route('ruangan.index') }}">Ruangan</a>
        <span>/</span> <a href="{{ route('ruangan.show', $barang->ruangan) }}">{{ $barang->ruangan->nama_ruangan }}</a>
        <span>/</span> <span>Edit Barang</span>
    </div>
</div>

<div class="card" style="max-width:720px;">
    <div class="card-header">
        <h5><i class="fas fa-pen text-primary"></i> Edit Data Barang</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('barang.update', $barang) }}"
              enctype="multipart/form-data" id="editBarangForm">
            @csrf @method('PUT')
            <input type="hidden" name="foto_barang_base64" id="foto_barang_base64">

            <!-- FOTO -->
            <div class="form-group">
                <label class="form-label">Foto Barang</label>
                @if($barang->foto_barang)
                <div style="margin-bottom:10px;">
                    <img src="{{ asset('storage/' . $barang->foto_barang) }}"
                         id="currentFoto" alt="Foto" style="width:120px;height:90px;object-fit:cover;border-radius:8px;border:2px solid var(--border);">
                </div>
                @endif
                <div class="foto-drop-area" onclick="document.getElementById('foto_barang_input').click()">
                    <i class="fas fa-cloud-arrow-up" style="font-size:24px;margin-bottom:6px;display:block;"></i>
                    <span style="font-size:13px;">Klik untuk pilih foto baru</span>
                    <small style="font-size:11px;color:var(--text-light);">Biarkan kosong jika tidak ingin mengganti</small>
                </div>
                <img id="newFotoPreview" alt="preview"
                     style="width:100%;max-height:200px;object-fit:contain;border-radius:8px;margin-top:10px;display:none;">
                <input type="file" id="foto_barang_input" name="foto_barang"
                    accept="image/*" style="display:none"
                    onchange="previewEdit(this)">
            </div>

            <!-- NAMA -->
            <div class="form-group">
                <label class="form-label">Nama Barang <span class="required">*</span></label>
                <input type="text" name="nama_barang"
                    class="form-control {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}"
                    value="{{ old('nama_barang', $barang->nama_barang) }}">
                @error('nama_barang')
                <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach(['Furniture', 'Elektronik', 'Lainnya'] as $kat)
                        <option value="{{ $kat }}" {{ old('kategori', $barang->kategori) == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Jumlah (Unit) <span class="required">*</span></label>
                    <input type="number" name="jumlah"
                        class="form-control {{ $errors->has('jumlah') ? 'is-invalid' : '' }}"
                        value="{{ old('jumlah', $barang->jumlah) }}" min="1">
                    @error('jumlah')
                    <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- KONDISI -->
            <div class="form-group">
                <label class="form-label">Kondisi <span class="required">*</span></label>
                <div class="kondisi-toggle">
                    @php $kondisiOld = old('kondisi', $barang->kondisi); @endphp
                    <label class="kondisi-opt {{ $kondisiOld === 'Baik' ? 'selected-baik' : '' }}" id="opt-baik">
                        <input type="radio" name="kondisi" value="Baik"
                            {{ $kondisiOld === 'Baik' ? 'checked' : '' }}
                            onchange="updateKondisi(this)">
                        <div class="kondisi-opt-label">
                            <i class="fas fa-check-circle"></i>
                            <span>Baik</span>
                        </div>
                    </label>
                    <label class="kondisi-opt {{ $kondisiOld === 'Rusak berat' ? 'selected-rusak' : '' }}" id="opt-rusak">
                        <input type="radio" name="kondisi" value="Rusak berat"
                            {{ $kondisiOld === 'Rusak berat' ? 'checked' : '' }}
                            onchange="updateKondisi(this)">
                        <div class="kondisi-opt-label">
                            <i class="fas fa-triangle-exclamation"></i>
                            <span>Rusak berat</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- KETERANGAN -->
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control"
                    placeholder="Tambahkan catatan...">{{ old('keterangan', $barang->keterangan) }}</textarea>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('ruangan.show', $barang->ruangan) }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewEdit(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('newFotoPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            const cur = document.getElementById('currentFoto');
            if (cur) cur.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateKondisi(radio) {
    document.getElementById('opt-baik').className  = 'kondisi-opt';
    document.getElementById('opt-rusak').className = 'kondisi-opt';
    if (radio.value === 'Baik') {
        document.getElementById('opt-baik').classList.add('selected-baik');
    } else {
        document.getElementById('opt-rusak').classList.add('selected-rusak');
    }
}
</script>
@endpush
@endsection
