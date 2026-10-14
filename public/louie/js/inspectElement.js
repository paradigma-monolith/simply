document.addEventListener('keydown', function (event) {
  if (event.ctrlKey && event.shiftKey && event.key === 'I') {
      // Blok akses ke Developer Tools
      event.preventDefault();
      alert('Akses ke Developer Tools diblokir.');
  }
});
document.addEventListener('contextmenu', function (event) {
    // Mencegah tindakan bawaan dari klik kanan
    event.preventDefault();
});