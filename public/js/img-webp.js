/**
 * img-webp.js — Auto-convert image inputs to WebP before upload
 *
 * Cara kerja:
 *  1. Deteksi semua <input type="file" accept="image/*"> di halaman
 *  2. Saat user pilih gambar → baca via FileReader
 *  3. Gambar di-resize (max 1280px) lalu di-render ke Canvas
 *  4. Canvas di-export ke Blob WebP (quality 0.82)
 *  5. Blob dimasukkan kembali ke input via DataTransfer
 *  6. Preview & info ukuran ditampilkan
 */
(function () {
    'use strict';

    var MAX_WIDTH  = 1280;   // px — gambar lebih lebar dari ini akan di-resize
    var MAX_HEIGHT = 1280;   // px
    var QUALITY    = 0.82;   // WebP quality (0–1)

    /* Cek dukungan WebP & Canvas */
    function supportsWebP() {
        var c = document.createElement('canvas');
        return c.getContext && c.toDataURL('image/webp').indexOf('data:image/webp') === 0;
    }

    /* Format ukuran bytes → KB / MB */
    function fmtSize(bytes) {
        if (bytes < 1024)       return bytes + ' B';
        if (bytes < 1024*1024)  return (bytes/1024).toFixed(1) + ' KB';
        return (bytes/(1024*1024)).toFixed(2) + ' MB';
    }

    /* Buat atau update elemen info di bawah input */
    function setInfo(infoEl, msg, color) {
        if (!infoEl) return;
        infoEl.textContent = msg;
        infoEl.style.color = color || '#64748b';
    }

    /* Konversi satu File → Blob WebP */
    function convertToWebP(file, callback) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                /* Hitung ukuran baru (jaga aspek rasio) */
                var w = img.width;
                var h = img.height;
                if (w > MAX_WIDTH || h > MAX_HEIGHT) {
                    var ratio = Math.min(MAX_WIDTH / w, MAX_HEIGHT / h);
                    w = Math.round(w * ratio);
                    h = Math.round(h * ratio);
                }

                var canvas = document.createElement('canvas');
                canvas.width  = w;
                canvas.height = h;
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, w, h);

                canvas.toBlob(function (blob) {
                    callback(blob, w, h);
                }, 'image/webp', QUALITY);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    /* Tampilkan thumbnail preview */
    function showPreview(input, objectUrl) {
        /* Cari elemen preview: data-preview="<inputId>" atau sibling dengan class img-preview */
        var previewId = input.dataset.preview;
        var previewEl = previewId
            ? document.getElementById(previewId)
            : input.parentElement.querySelector('img.img-preview');

        if (previewEl) {
            previewEl.src = objectUrl;
            previewEl.style.display = 'block';
        }
    }

    /* Handle perubahan pada satu input */
    function handleChange(input, infoEl) {
        var file = input.files && input.files[0];
        if (!file || !file.type.startsWith('image/')) return;

        var origSize = file.size;
        setInfo(infoEl, '⏳ Mengkonversi…', '#6366f1');

        convertToWebP(file, function (blob, w, h) {
            if (!blob) {
                setInfo(infoEl, '⚠ Konversi gagal, file asli digunakan.', '#f59e0b');
                return;
            }

            /* Masukkan blob kembali ke input via DataTransfer */
            try {
                var baseName = file.name.replace(/\.[^.]+$/, '') || 'image';
                var webpFile = new File([blob], baseName + '.webp', { type: 'image/webp' });
                var dt = new DataTransfer();
                dt.items.add(webpFile);
                input.files = dt.files;

                var saved = origSize - blob.size;
                var pct   = Math.round((saved / origSize) * 100);
                var msg   = '✅ WebP ' + w + '×' + h + 'px · '
                          + fmtSize(blob.size)
                          + ' (hemat ' + (pct > 0 ? pct + '%' : '0%') + ' dari '
                          + fmtSize(origSize) + ')';
                setInfo(infoEl, msg, '#059669');

                /* Preview */
                var url = URL.createObjectURL(blob);
                showPreview(input, url);
            } catch (err) {
                /* DataTransfer tidak didukung (browser lama) — fallback gracefully */
                setInfo(infoEl, '⚠ Browser tidak mendukung penggantian file otomatis.', '#f59e0b');
                console.warn('img-webp: DataTransfer error', err);
            }
        });
    }

    /* Init satu input */
    function initInput(input) {
        if (input.dataset.webpInited) return;
        input.dataset.webpInited = '1';

        /* Buat elemen info jika belum ada */
        var infoEl = input.parentElement.querySelector('.webp-info');
        if (!infoEl) {
            infoEl = document.createElement('div');
            infoEl.className = 'webp-info';
            infoEl.style.cssText = 'font-size:12px;margin-top:6px;line-height:1.4;';
            input.parentElement.appendChild(infoEl);
        }

        input.addEventListener('change', function () {
            handleChange(input, infoEl);
        });
    }

    /* Init semua image inputs di halaman */
    function initAll() {
        if (!supportsWebP()) return; /* Browser tidak mendukung, skip */

        document.querySelectorAll('input[type="file"]').forEach(function (input) {
            var accept = input.getAttribute('accept') || '';
            if (accept.indexOf('image') !== -1 || accept === '') {
                initInput(input);
            }
        });
    }

    /* Jalankan saat DOM siap */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }

    /* Expose manual init untuk input yang dibuat setelah DOMContentLoaded */
    window.initWebpInput = initInput;
})();
