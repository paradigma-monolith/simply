$(function () {
  // Fungsi resize canvas untuk retina display
  function resizeCanvas(canvas) {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    
    // Simpan data gambar sebelumnya jika ada
    let signaturePad = canvas._signaturePad;
    let data = null;
    if (signaturePad && !signaturePad.isEmpty()) {
      data = signaturePad.toData();
    }
    
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    const context = canvas.getContext("2d");
    context.scale(ratio, ratio);
    
    // Kembalikan data gambar setelah resize
    if (signaturePad) {
        signaturePad.clear(); // Wajib clear internal state setelah resize canvas
        if (data) {
            signaturePad.fromData(data);
        }
    }
  }

  // Loop semua canvas dengan class .signature-pad
  document.querySelectorAll(".signature-pad").forEach(function (canvas) {       
    // Set ukuran awal sebelum SignaturePad diinisialisasi
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);

    // Inisialisasi SignaturePad untuk setiap canvas
    const signaturePad = new SignaturePad(canvas, {
      backgroundColor: "rgb(255, 255, 255)",
    });

    // Menyimpan instance ke DOM agar bisa diakses nanti saat submit
    canvas._signaturePad = signaturePad;

    // Cari tombol dalam blok yang sama
    const wrapper = canvas.closest(".signature-wrapper");
    const clearButton = wrapper?.querySelector(".clear-signature");
    const colorButton = wrapper?.querySelector(".change-color-signature");

    // Tombol Clear
    if (clearButton) {
      clearButton.addEventListener("click", function () {
        signaturePad.clear();
      });
    }

    // Tombol Change Color
    if (colorButton) {
      colorButton.addEventListener("click", function () {
        signaturePad.penColor =
          signaturePad.penColor === "rgba(0, 0, 255, 1)"
            ? "rgba(0, 0, 0, 1)"
            : "rgba(0, 0, 255, 1)";
      });
    }

    // Resize canvas saat halaman dimuat dan diresize
    setTimeout(() => resizeCanvas(canvas), 100); // Beri sedikit delay agar CSS benar-benar dirender
    window.addEventListener("resize", () => resizeCanvas(canvas));
  });

  // Jika canvas ada di dalam elemen yang disembunyikan (accordion/tab/card), resize ulang saat dibuka
  $(document).on('shown.bs.collapse expanded.lte.cardwidget shown.bs.tab shown.bs.modal', function () {
    document.querySelectorAll(".signature-pad").forEach(function (canvas) {
      resizeCanvas(canvas);
    });
  });

  // Submit handler spesifik per form
  $(document).on("submit", "form", function (e) {
    e.preventDefault();

    const $form = $(this); // Form yang sedang disubmit
    let allFilled = true;

    // Hanya ambil canvas yang ada di dalam form ini
    $form.find(".signature-pad").each(function () {
      const canvas = this;
      const signaturePad = canvas._signaturePad;
      const signatureInput = canvas.closest("div")?.querySelector(".signature");

      if (!signaturePad) return;

      if (signaturePad.isEmpty()) {
        allFilled = false;
        return false; // hentikan loop
      }

      if (signatureInput) {
        signatureInput.value = signaturePad.toDataURL(); // Simpan base64 PNG
      }
    });

    if (!allFilled) {
      alert("Tanda tangan tidak boleh kosong.");
      return;
    }

    // Jika valid, biarkan form dikirim
    this.submit();
  });
});
