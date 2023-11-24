let list = document.getElementById('select-city');
let city = document.getElementById('city');
let code = document.getElementById('client_cp');
let isPro = document.getElementById('client_clientPro');
let isProClientDiv = document.getElementById('isProClient');

let html = "<option>Selectionner la ville</option>";

function getCityList() {

    if (code.value.length == 5) {
        html = "";
        console.log(code.value);
        const url = "https://geo.api.gouv.fr/communes?codePostal=" + code.value + "&fields=nom";

        fetch(url, { method: 'GET' })
            .then((response) => {
                return response.json()
            }).then((donnees) => {

                for (let item of donnees) {
                    console.log(item);
                    html += `<option value="${item.nom}">${item.nom}</option>`;

                }
                list.innerHTML = html;
            });
    }
}

function showClientProInput() {
    // console.log('show client pro informatin input');

    if (isPro.checked === false) {

        isProClientDiv.setAttribute("hidden", "hidden")


    } else {

        isProClientDiv.removeAttribute("hidden")
    }
}

//on affiche les villes si le code postal est deja present
window.onload = (event) => {
    getCityList();
    showClientProInput();
};

code.addEventListener('input', (event) => {
    getCityList();

});

isPro.addEventListener('change', (event) => {
    showClientProInput()
})


list.addEventListener('change', (event) => {
    city.value = list.value
})