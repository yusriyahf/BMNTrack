@extends('layouts.app')

@section('title', 'Tambah Barang – ' . $ruangan->nama_ruangan)
@section('page-title', 'Tambah Barang')
@section('page-subtitle', 'Input data aset untuk ' . $ruangan->nama_ruangan)

@push('styles')
<style>
.ruangan-ctx-bar {
    background: linear-gradient(135deg, var(--primary-dark), var(--primary));
    border-radius: var(--radius);
    padding: 16px 20px;
    display: flex; align-items: center; gap: 14px;
    margin-bottom: 24px; color: #fff;
}
.ruangan-ctx-icon {
    width: 44px; height: 44px;
    background: rgba(255,255,255,.15);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px;
}
.ruangan-ctx-info label { font-size: 11px; opacity: .7; text-transform: uppercase; letter-spacing: .5px; display: block; margin-bottom: 2px; }
.ruangan-ctx-info strong { font-size: 15px; font-weight: 700; }
.ruangan-ctx-info span { font-size: 12px; opacity: .8; }

/* Photo Section */
.photo-section {
    border: 1.5px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    margin-bottom: 20px;
}
.photo-tabs {
    display: flex; border-bottom: 1.5px solid var(--border);
}
.photo-tab {
    flex: 1; padding: 12px;
    background: var(--primary-ultra);
    border: none; cursor: pointer;
    font-size: 13px; font-weight: 600;
    color: var(--text-mid);
    transition: var(--transition);
    display: flex; align-items: center; justify-content: center; gap: 7px;
}
.photo-tab.active {
    background: var(--primary-light);
    color: #fff;
}
.photo-panel { display: none; padding: 16px; }
.photo-panel.active { display: block; }

.foto-drop-area {
    border: 2px dashed var(--border);
    border-radius: var(--radius-sm);
    padding: 32px 20px;
    text-align: center;
    cursor: pointer; transition: var(--transition);
    color: var(--text-light);
}
.foto-drop-area:hover { border-color: var(--primary-light); color: var(--primary); }
.foto-drop-area i { font-size: 36px; display: block; margin-bottom: 10px; }
.foto-drop-area span { font-size: 13px; }
.foto-drop-area small { display: block; font-size: 11px; margin-top: 4px; }
.foto-preview {
    width: 100%; border-radius: var(--radius-sm); margin-top: 12px;
    max-height: 220px; object-fit: contain; display: none;
}

/* Camera */
#cameraView { width: 100%; border-radius: var(--radius-sm); display: none; }
#cameraCanvas { display: none; }
.camera-controls { display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; }

.kondisi-toggle {
    display: flex; gap: 10px;
}
.kondisi-opt {
    flex: 1; padding: 12px;
    border: 2px solid var(--border);
    border-radius: var(--radius-sm);
    cursor: pointer; text-align: center;
    transition: var(--transition);
    position: relative;
}
.kondisi-opt input { position: absolute; opacity: 0; }
.kondisi-opt-label { display: flex; flex-direction: column; align-items: center; gap: 6px; }
.kondisi-opt-label i { font-size: 22px; }
.kondisi-opt-label span { font-size: 13px; font-weight: 600; }
.kondisi-opt.selected-aman {
    border-color: var(--success);
    background: #d1fae5; color: #065f46;
}
.kondisi-opt.selected-rusak {
    border-color: var(--danger);
    background: var(--danger-light); color: #991b1b;
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}"><i class="fas fa-house"></i> Beranda</a>
        <span>/</span> <a href="{{ route('ruangan.index') }}">Ruangan</a>
        <span>/</span> <a href="{{ route('ruangan.show', $ruangan) }}">{{ $ruangan->nama_ruangan }}</a>
        <span>/</span> <span>Tambah Barang</span>
    </div>
</div>

<!-- Room Context -->
<div class="ruangan-ctx-bar">
    <div class="ruangan-ctx-icon"><i class="fas fa-door-open"></i></div>
    <div class="ruangan-ctx-info">
        <label>Ruangan tujuan input barang</label>
        <strong>{{ $ruangan->nama_ruangan }}</strong>
        <span>{{ $ruangan->gedung->nama_gedung ?? '-' }} · Lantai {{ $ruangan->lantai }}</span>
    </div>
</div>

<div class="card" style="max-width:720px;">
    <div class="card-header">
        <h5><i class="fas fa-plus-circle text-primary"></i> Form Input Barang Inventaris</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('ruangan.barang.store', $ruangan) }}"
              enctype="multipart/form-data" id="barangForm">
            @csrf
            <!-- Hidden base64 field for camera -->
            <input type="hidden" name="foto_barang_base64" id="foto_barang_base64">

            <!-- FOTO SECTION -->
            <div class="form-group">
                <label class="form-label">Foto Barang</label>
                <div class="photo-section">
                    <div class="photo-tabs">
                        <button type="button" class="photo-tab active" onclick="switchTab('gallery', this)">
                            <i class="fas fa-image"></i> Dari Galeri
                        </button>
                        <button type="button" class="photo-tab" onclick="switchTab('camera', this)">
                            <i class="fas fa-camera"></i> Gunakan Kamera
                        </button>
                    </div>

                    <!-- Gallery Panel -->
                    <div class="photo-panel active" id="panel-gallery">
                        <div class="foto-drop-area" id="dropArea" onclick="document.getElementById('foto_barang').click()">
                            <i class="fas fa-cloud-arrow-up"></i>
                            <span>Klik atau seret foto ke sini</span>
                            <small>JPG, PNG, WEBP — Maksimal 5MB</small>
                        </div>
                        <input type="file" id="foto_barang" name="foto_barang"
                            accept="image/*" style="display:none"
                            onchange="handleGalleryFile(this)">
                        <img id="galleryPreview" class="foto-preview" alt="Preview">
                    </div>

                    <!-- Camera Panel -->
                    <div class="photo-panel" id="panel-camera">
                        <video id="cameraView" autoplay playsinline></video>
                        <canvas id="cameraCanvas"></canvas>
                        <img id="capturedPreview" class="foto-preview" alt="Captured">
                        <div class="camera-controls">
                            <button type="button" class="btn btn-primary" id="btnStartCam" onclick="startCamera()">
                                <i class="fas fa-video"></i> Buka Kamera
                            </button>
                            <button type="button" class="btn btn-success" id="btnCapture" onclick="capturePhoto()" style="display:none;">
                                <i class="fas fa-circle"></i> Ambil Foto
                            </button>
                            <button type="button" class="btn btn-outline" id="btnRetake" onclick="retakePhoto()" style="display:none;">
                                <i class="fas fa-rotate-left"></i> Ulangi
                            </button>
                        </div>
                        <p style="font-size:12px;color:var(--text-light);margin-top:8px;">
                            <i class="fas fa-info-circle"></i> Izinkan akses kamera di browser Anda.
                        </p>
                    </div>
                </div>
                @error('foto_barang')
                <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- NAMA BARANG -->
            <div class="form-group">
                <label class="form-label" for="nama_barang">
                    Nama Barang <span class="required">*</span>
                </label>
                <input type="text" id="nama_barang" name="nama_barang"
                    class="form-control {{ $errors->has('nama_barang') ? 'is-invalid' : '' }}"
                    value="{{ old('nama_barang') }}"
                    placeholder="Contoh: Meja Kerja, Kursi Lipat, PC Desktop">
                @error('nama_barang')
                <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- KATEGORI & JUMLAH -->
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                <div class="form-group">
                    <label class="form-label" for="kategori">Kategori</label>
                    <select id="kategori" name="kategori" class="form-control">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="Furniture" {{ old('kategori') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                        <option value="Elektronik" {{ old('kategori') == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                       
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="jumlah">
                        Jumlah (Unit) <span class="required">*</span>
                    </label>
                    <input type="number" id="jumlah" name="jumlah"
                        class="form-control {{ $errors->has('jumlah') ? 'is-invalid' : '' }}"
                        value="{{ old('jumlah', 1) }}" min="1">
                    @error('jumlah')
                    <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- KONDISI -->
            <div class="form-group">
                <label class="form-label">Kondisi <span class="required">*</span></label>
                <div class="kondisi-toggle">
                    <label class="kondisi-opt {{ old('kondisi', 'Aman') === 'Aman' ? 'selected-aman' : '' }}" id="opt-aman">
                        <input type="radio" name="kondisi" value="Aman"
                            {{ old('kondisi', 'Aman') === 'Aman' ? 'checked' : '' }}
                            onchange="updateKondisi(this)">
                        <div class="kondisi-opt-label">
                            <i class="fas fa-shield-check"></i>
                            <span>Aman</span>
                        </div>
                    </label>
                    <label class="kondisi-opt {{ old('kondisi') === 'Rusak' ? 'selected-rusak' : '' }}" id="opt-rusak">
                        <input type="radio" name="kondisi" value="Rusak"
                            {{ old('kondisi') === 'Rusak' ? 'checked' : '' }}
                            onchange="updateKondisi(this)">
                        <div class="kondisi-opt-label">
                            <i class="fas fa-triangle-exclamation"></i>
                            <span>Rusak</span>
                        </div>
                    </label>
                </div>
                @error('kondisi')
                <div class="form-error"><i class="fas fa-triangle-exclamation"></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- KETERANGAN -->
            <div class="form-group">
                <label class="form-label" for="keterangan">Keterangan</label>
                <textarea id="keterangan" name="keterangan"
                    class="form-control"
                    placeholder="Tambahkan catatan atau keterangan tambahan tentang barang...">{{ old('keterangan') }}</textarea>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Barang
                </button>
                <a href="{{ route('ruangan.show', $ruangan) }}" class="btn btn-outline">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let cameraStream = null;

// Tab switching
function switchTab(tab, btn) {
    document.querySelectorAll('.photo-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.photo-panel').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('panel-' + tab).classList.add('active');
    if (tab !== 'camera' && cameraStream) {
        stopCamera();
    }
}

// Gallery file preview
function handleGalleryFile(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById('galleryPreview');
            preview.src = e.target.result;
            preview.style.display = 'block';
            document.getElementById('dropArea').style.display = 'none';
            // Clear camera base64 when gallery selected
            document.getElementById('foto_barang_base64').value = '';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Drag & Drop
const dropArea = document.getElementById('dropArea');
dropArea.addEventListener('dragover', (e) => { e.preventDefault(); dropArea.style.borderColor = 'var(--primary-light)'; });
dropArea.addEventListener('dragleave', () => { dropArea.style.borderColor = ''; });
dropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('foto_barang').files = dt.files;
        handleGalleryFile(document.getElementById('foto_barang'));
    }
});

// Camera
async function startCamera() {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
        });
        const video = document.getElementById('cameraView');
        video.srcObject = cameraStream;
        video.style.display = 'block';
        document.getElementById('btnStartCam').style.display = 'none';
        document.getElementById('btnCapture').style.display = 'inline-flex';
    } catch (err) {
        alert('Tidak dapat mengakses kamera: ' + err.message);
    }
}

function capturePhoto() {
    const video  = document.getElementById('cameraView');
    const canvas = document.getElementById('cameraCanvas');
    const ctx    = canvas.getContext('2d');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    ctx.drawImage(video, 0, 0);
    const dataUrl = canvas.toDataURL('image/jpeg', 0.9);

    // Set captured image
    const preview = document.getElementById('capturedPreview');
    preview.src = dataUrl;
    preview.style.display = 'block';
    video.style.display   = 'none';
    document.getElementById('btnCapture').style.display = 'none';
    document.getElementById('btnRetake').style.display  = 'inline-flex';

    // Set base64 for submission
    document.getElementById('foto_barang_base64').value = dataUrl;
    // Clear file input so base64 is used
    document.getElementById('foto_barang').value = '';

    stopCamera();
}

function retakePhoto() {
    document.getElementById('capturedPreview').style.display = 'none';
    document.getElementById('btnRetake').style.display = 'none';
    document.getElementById('btnCapture').style.display = 'none';
    document.getElementById('btnStartCam').style.display = 'inline-flex';
    document.getElementById('foto_barang_base64').value = '';
    startCamera();
}

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(t => t.stop());
        cameraStream = null;
    }
}

// Stop camera when leaving page
window.addEventListener('beforeunload', stopCamera);

// Kondisi toggle styling
function updateKondisi(radio) {
    document.getElementById('opt-aman').className  = 'kondisi-opt';
    document.getElementById('opt-rusak').className = 'kondisi-opt';
    if (radio.value === 'Aman') {
        document.getElementById('opt-aman').classList.add('selected-aman');
    } else {
        document.getElementById('opt-rusak').classList.add('selected-rusak');
    }
}
</script>
@endpush
@endsection
