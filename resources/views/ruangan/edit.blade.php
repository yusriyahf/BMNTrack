@extends('layouts.app')

@section('title', 'Edit Ruangan – ' . $ruangan->nama_ruangan)
@section('page-title', 'Edit Ruangan')

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
.foto-preview-box:hover { border-color: var(--primary-light); }
.foto-preview-box img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.foto-preview-box i { font-size: 32px; margin-bottom: 8px; }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1>Edit Ruangan</h1>
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
        <span>/</span> <a href="{{ route('ruangan.index') }}">Ruangan</a>
        <span>/</span> <a href="{{ route('ruangan.show', $ruangan) }}">{{ $ruangan->nama_ruangan }}</a>
        <span>/</span> <span>Edit</span>
    </div>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-header">
        <h5><i class="fas fa-pen text-primary"></i> Edit Data Ruangan</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('ruangan.update', $ruangan) }}" enctype="multipart/form-data">
            @csrf @method('PUT')

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Gedung <span class="required">*</span></label>
                    <select name="gedung_id" class="form-control {{ $errors->has('gedung_id') ? 'is-invalid' : '' }}">
                        @foreach($gedungs as $g)
                        <option value="{{ $g->id }}" {{ old('gedung_id', $ruangan->gedung_id) == $g->id ? 'selected' : '' }}>
                            {{ $g->kode_gedung }} - {{ $g->nama_gedung }}
                        </option>
                        @endforeach
                    </select>
                    @error('gedung_id')
                    <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Nama Ruangan <span class="required">*</span></label>
                    <input type="text" name="nama_ruangan"
                        class="form-control {{ $errors->has('nama_ruangan') ? 'is-invalid' : '' }}"
                        value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}">
                    @error('nama_ruangan')
                    <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Lantai <span class="required">*</span></label>
                    <input type="number" name="lantai"
                        class="form-control {{ $errors->has('lantai') ? 'is-invalid' : '' }}"
                        value="{{ old('lantai', $ruangan->lantai) }}" min="1">
                </div>

                <div class="form-group">
                    <label class="form-label">Luas Ruangan</label>
                    <input type="text" name="luas_ruangan"
                        class="form-control"
                        value="{{ old('luas_ruangan', $ruangan->luas_ruangan) }}">
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">PIC Ruangan</label>
                    <input type="text" name="pic_ruangan"
                        class="form-control"
                        value="{{ old('pic_ruangan', $ruangan->pic_ruangan) }}">
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Pendataan</label>
                    <input type="date" name="tanggal_pendataan"
                        class="form-control"
                        value="{{ old('tanggal_pendataan', optional($ruangan->tanggal_pendataan)->format('Y-m-d')) }}">
                </div>

                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Foto Ruangan</label>
                    <div class="foto-preview-box" id="fotoBox" onclick="document.getElementById('foto_input').click()">
                        @if($ruangan->foto_ruangan)
                            <img src="{{ asset('storage/' . $ruangan->foto_ruangan) }}" alt="Foto">
                        @else
                            <i class="fas fa-camera"></i>
                            <span>Klik untuk ganti foto</span>
                        @endif
                    </div>
                    <input type="file" id="foto_input" name="foto_ruangan"
                        accept="image/*" style="display:none"
                        onchange="previewFoto(this, 'fotoBox')">
                    <small style="color:var(--text-light);font-size:12px;margin-top:4px;display:block;">
                        Biarkan kosong jika tidak ingin mengganti foto.
                    </small>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('ruangan.show', $ruangan) }}" class="btn btn-outline">
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
