function actualizarReloj() {
  document.getElementById('clock').innerText =
    new Date().toLocaleTimeString();
}
setInterval(actualizarReloj, 1000);
actualizarReloj();

function fichar(tipo) {
  const ahora = new Date();

  fetch('http://localhost:3000/api/fichar', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      tipo,
      fecha: ahora.toLocaleDateString(),
      hora: ahora.toLocaleTimeString(),
      lat: 40.4168,   // geolocalización simulada
      lng: -3.7038
    })
  }).then(() => alert(`Fichaje de ${tipo} registrado`));
}
