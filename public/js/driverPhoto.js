window.onload = () => {
    // Gestion des boutons "Supprimer"
    let links = document.querySelectorAll("[data-insert]");
    let photo = document.getElementById("driver-photo-js");

    // On boucle sur links
    for (let link of links) {
        // On écoute le clic
        link.addEventListener("click", function (e) {
            // On empêche la navigation
            e.preventDefault()

            // On demande confirmation
            if (confirm("Voulez-vous utiliser cette image comme photo profil?")) {
                // On envoie une requête Ajax vers le href du lien avec la méthode DELETE
                fetch(this.getAttribute("href"), {
                    method: "POST",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": this.dataset.token })
                }).then(
                    // On récupère la réponse en JSON
                    response => response.json()
                ).then(data => {
                    if (data.success)
                        photo.setAttribute("src", data.new_src)
                    else
                        alert(data.error)
                }).catch(e => alert(e))
            }
        })
    }
}