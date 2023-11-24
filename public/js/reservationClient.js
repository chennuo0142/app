let html = "";
let show = document.getElementById('driver-info-show-js');
let code_user = document.getElementById('code_chauffeur');

code_user.onblur = () => {

    let url = "/reservation/by/client/getUser/" + code_user.value;
    console.log(code_user.value)
    console.log(url);

    fetch(url, { method: 'GET' })
        .then((response) => {


            return response.json()
        }).then((donnees) => {

            html = `
            <ul>
                <li>Societe:${donnees.company.name}</li>
                <li>
				<img src="/uploads/${donnees.photoDriver}" alt="photo" width="80">
                ${donnees.driver.name}</li>

                <li>
                <img src="/uploads/${donnees.photoCar}" alt="photo" width="80">
                ${donnees.car.brand} ${donnees.car.model} 
                ${donnees.car.registrationNumber}  </li>
             </ul>
            
            
            
            `;

            show.innerHTML = html;


        }).catch(function (err) {
            html = `
            <p class="warning">
	            <strong class="warning">Aucun chauffeur trouvee, veuillez corriger les informations</strong>
            </p>
            
            `;

            show.innerHTML = html;
        });
}