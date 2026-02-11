setInterval(() => {
    document.getElementById("reloj").innerHTML =
        new Date().toLocaleTimeString();
}, 1000);
