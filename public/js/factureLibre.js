window.onload = () => {
    let client = document.getElementById('client-js');

    client.addEventListener('change', function (event) {
        //on recupere id client suite a la selection
        let idClient = document.getElementById('client-js').value;

        //on recupere les information du client a travers son api
        let url = "/gestion/client/api/" + idClient;

        fetch(url, { method: 'GET' })
            .then((response) => {

                return response.json()
            })
            .then((client) => {

                if (client.status == 400) {
                    alert(client.message);
                    redirection();
                }

                document.getElementById('facture_name').value = client.name;
                document.getElementById('facture_email').value = client.email;
                document.getElementById('facture_adress').value = client.adress;
                document.getElementById('facture_zipCode').value = client.cp;
                document.getElementById('facture_city').value = client.city;
            })

    })


    //on selectione tout les boutons del
    for (let i = 1; i <= 4; i++) {
        document.getElementById("delArticle-js-" + i).addEventListener('click', function (event) {
            event.preventDefault();
            delData(i);
        });
    }
}

function delData(i) {
    document.getElementById("facture_article" + i).value = "";
    document.getElementById("facture_price" + i).value = "";
    document.getElementById("facture_tva" + i).value = "";
    document.getElementById("facture_quantity" + i).value = "";
}

function redirection() {
    document.location.href = "https://localhost:8000/logout";
}