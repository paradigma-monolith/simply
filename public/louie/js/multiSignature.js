$(function () {
  // Fungsi resize canvas untuk retina display
  function resizeCanvas(canvas) {
    const ratio = Math.max(window.devicePixelRatio || 1, 1);
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    const context = canvas.getContext("2d");
    context.scale(ratio, ratio);
  }

  // Loop semua canvas dengan class .signature-pad
  document.querySelectorAll(".signature-pad").forEach(function (canvas) {
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
    window.addEventListener("DOMContentLoaded", () => resizeCanvas(canvas));
    window.addEventListener("resize", () => resizeCanvas(canvas));
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
