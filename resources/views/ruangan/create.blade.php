@extends('layouts.app')

@section('title', 'Tambah Ruangan')
@section('page-title', 'Tambah Ruangan')

@push('styles')
<style>
.foto-preview-box {
    width: 100%; max-width: 320px; height: 180px;
    border: 2px dashed var(--border);
    border-radius: var(--radius);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    color: var(--text-light); font-size: 13px;
    cursor: pointer; transition: var(--transition); position: relative; overflow: hidden;
    background: var(--primary-ultra);
}
.foto-preview-box:hover { border-color: var(--primary-light); color: var(--primary); }
.foto-preview-box img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.foto-preview-box i { font-size: 32px; margin-bottom: 8px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Tambah Ruangan</h1>
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
        <span>/</span> <a href="{{ route('ruangan.index') }}">Ruangan</a>
        <span>/</span> <span>Tambah</span>
    </div>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-header">
        <h5><i class="fas fa-plus-circle text-primary"></i> Form Data Ruangan</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('ruangan.store') }}" enctype="multipart/form-data">
            @csrf

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="gedung_id">
                        Gedung <span class="required">*</span>
                    </label>
                    <select id="gedung_id" name="gedung_id"
                        class="form-control {{ $errors->has('gedung_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Pilih Gedung --</option>
                        @foreach($gedungs as $g)
                        <option value="{{ $g->id }}" {{ old('gedung_id') == $g->id ? 'selected' : '' }}>
                            {{ $g->kode_gedung }} - {{ $g->nama_gedung }}
                        </option>
                        @endforeach
                    </select>
                    @error('gedung_id')
                    <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="nama_ruangan">
                        Nama Ruangan <span class="required">*</span>
                    </label>
                    <input type="text" id="nama_ruangan" name="nama_ruangan"
                        class="form-control {{ $errors->has('nama_ruangan') ? 'is-invalid' : '' }}"
                        value="{{ old('nama_ruangan') }}"
                        placeholder="Contoh: Ruang Rapat Utama, Lab Komputer A">
                    @error('nama_ruangan')
                    <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="lantai">
                        Lantai <span class="required">*</span>
                    </label>
                    <input type="number" id="lantai" name="lantai"
                        class="form-control {{ $errors->has('lantai') ? 'is-invalid' : '' }}"
                        value="{{ old('lantai', 1) }}" min="1" max="50">
                    @error('lantai')
                    <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="luas_ruangan">Luas Ruangan</label>
                    <input type="text" id="luas_ruangan" name="luas_ruangan"
                        class="form-control {{ $errors->has('luas_ruangan') ? 'is-invalid' : '' }}"
                        value="{{ old('luas_ruangan') }}"
                        placeholder="Contoh: 6x8 m, 48 m²">
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label" for="pic_ruangan">PIC Ruangan (Penanggung Jawab)</label>
                    <input type="text" id="pic_ruangan" name="pic_ruangan"
                        class="form-control {{ $errors->has('pic_ruangan') ? 'is-invalid' : '' }}"
                        value="{{ old('pic_ruangan') }}"
                        placeholder="Nama penanggung jawab ruangan">
                </div>

                <div class="form-group">
                    <label class="form-label" for="tanggal_pendataan">Tanggal Pendataan</label>
                    <input type="date" id="tanggal_pendataan" name="tanggal_pendataan"
                        class="form-control {{ $errors->has('tanggal_pendataan') ? 'is-invalid' : '' }}"
                        value="{{ old('tanggal_pendataan', date('Y-m-d')) }}">
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Foto Ruangan</label>
                    <div class="foto-preview-box" id="fotoBox" onclick="document.getElementById('foto_ruangan').click()">
                        <i class="fas fa-camera"></i>
                        <span>Klik untuk pilih foto</span>
                        <small style="font-size:11px;margin-top:4px;">JPG, PNG, WEBP (maks. 5MB)</small>
                    </div>
                    <input type="file" id="foto_ruangan" name="foto_ruangan"
                        accept="image/*" style="display:none"
                        onchange="previewFoto(this, 'fotoBox')">
                    @error('foto_ruangan')
                    <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Ruangan
                </button>
                <a href="{{ route('ruangan.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function previewFoto(input, boxId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        const box = document.getElementById(boxId);
        reader.onload = (e) => {
            box.innerHTML = `<img src="${e.target.result}" alt="preview">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
@endsection
