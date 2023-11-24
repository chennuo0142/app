//fonction qui calcule et affiche les numeros de tva de l'entreprise


//on recupere les numeros
const sirets = document.getElementById('company_siret').value;

//on suprime les espaces debut et fin du siret 
let siret = sirets.trim();

//on supprime les espace entre les nombre
const siretFinale = siret.split(' ').join('');

//on selectionne la div js-show-numTva
const content = document.getElementById("js-show-numTva");

//on compte le nombre de caractere dans siret: 9 ou 14
if (siretFinale.length >= 9) {

    //on selectionne les 9 premier caracteres
    const siren = siretFinale.substring(0, 9);

    //formule de calcule numeros de tva:
    //[12+3*(sirent % 97)] % 97
    const CleNumTva = [12 + 3 * (siren % 97)] % 97

    //on assemble le numeros de tva finale
    const numTva = 'FR' + CleNumTva + siren;

    //on insert les infos dans la div
    content.innerHTML = `Numeros TVA: ${numTva}  -  Numeros Siren: ${siren}`

}