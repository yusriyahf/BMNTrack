@extends('layouts.app')

@section('title', 'Edit Gedung')
@section('page-title', 'Edit Gedung')

@section('content')
<div class="page-header">
    <h1>Edit Gedung</h1>
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
        <span>/</span> <a href="{{ route('gedung.index') }}">Gedung</a>
        <span>/</span> <span>Edit</span>
    </div>
</div>

<div class="card" style="max-width:600px;">
    <div class="card-header">
        <h5><i class="fas fa-pen text-primary"></i> Edit Gedung</h5>
        <span class="badge badge-primary">{{ $gedung->kode_gedung }}</span>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('gedung.update', $gedung) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label" for="kode_gedung">
                    Kode Gedung <span class="required">*</span>
                </label>
                <input type="text" id="kode_gedung" name="kode_gedung"
                    class="form-control {{ $errors->has('kode_gedung') ? 'is-invalid' : '' }}"
                    value="{{ old('kode_gedung', $gedung->kode_gedung) }}"
                    style="text-transform:uppercase"
                    maxlength="20">
                @error('kode_gedung')
                <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="nama_gedung">
                    Nama Gedung <span class="required">*</span>
                </label>
                <input type="text" id="nama_gedung" name="nama_gedung"
                    class="form-control {{ $errors->has('nama_gedung') ? 'is-invalid' : '' }}"
                    value="{{ old('nama_gedung', $gedung->nama_gedung) }}"
                    maxlength="100">
                @error('nama_gedung')
                <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('gedung.index') }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
