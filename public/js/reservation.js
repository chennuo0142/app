
function getReservation() {

    let reservation = document.getElementById('reservationId-js').value;
    let url = "/gestion/reservation/" + reservation + "/getArticle";
    var html = "";
    let show = document.getElementById('showArticles-js');

    fetch(url, { method: 'GET' })
        .then((response) => {

            return response.json()
        })
        .then((donnes) => {

            for (let item of donnes) {
                //on verifie si le prix est en

                if (item.isTtc === true) {

                    html += `
                    <tr>
                        <td>${item.name}</td>
                        <td>${item.price}</td>
                        <td>${item.tva}</td>
                        <td>${item.quantite}</td>
                        <td>${item.ht}</td>
                        <td>
                          
                        <a href="/gestion/reservation/${item.id}/${reservation}/delete" class="delete-article-js">Delete</a>
                           
                        </td>
                    </tr>
                `
                } else {

                    html += `
                    <tr>
                    <td>${item.name}</td>
                    <td>${item.price}</td>
                    <td>${item.tva}</td>
                    <td>${item.quantite}</td>
                    <td>${item.ttc}</td>
                    <td>
                          
                        <a href="/gestion/reservation/${item.id}/${reservation}/delete" class="delete-article-js">Delete</a>
                           
                        </td>
                    </tr>
                `
                }

            }

            show.innerHTML = html
        })
}

function handlleEvent(e) {

    e.preventDefault();
    let id = document.getElementById('select-article-js').value;

    let reservation = document.getElementById('reservationId-js').value;
    let urlPost = "/gestion/reservation/" + id + "/" + reservation + "/postArticle";

    fetch(urlPost, { method: 'POST' }).then((response) => {

        return response.json()
    });

    getReservation();

}

function setTTC(value) {

    let etat = value.checked
    let reservation = document.getElementById('reservationId-js').value;

    let urlPostTTC = "/gestion/reservation/" + reservation + "/" + etat + "/postTTC"

    fetch(urlPostTTC, { method: 'POST' })
        .then(function (response) {

        })

    getReservation()

}

// let links = document.querySelectorAll('a.delete-article-js');

// console.log(links);

// for (let link of links) {
//     let urlPost = link.href;

//     //on gref un ecouteur event sur chaque link
//     link.addEventListener('click', function (event) {
//         event.preventDefault();
//         fetch(urlPost, { method: 'POST' })

//         getReservation();
//     })

// }


document.getElementById('btn-add-article').addEventListener('click', handlleEvent);

