// Quand on manipule le DOM on DOIT attendre que tout le document soit chargé
window.onload = () => {
    // Ici on peut manipuler le document
    // On est certains qu'il est totalement chargé
    // On a une "variable systeme" qui contient le document
    console.log(document.children);

    //Selection d'élements dans le document
    // Selection de balises
    let balisesSection = document.getElementsByTagName("section");

    //selection de classes
    let classeTitre = document.getElementsByClassName("titre");

    //Sélection d'id 
    let paragraphe = document.getElementById("paragraphe")

    //Les "querySelector"
    //Le querySelector "simple " qui retourne le 1er résultt correspondant à la requete
    let premiereSection = document.querySelector("section")
    let premierTitre = document.querySelector(".titre")
    paragraphe = document.querySelector("#paragraphe")
    let titreSection2 = document.querySelector("section:nth-child(2) .titre")

    //Le querySelector "multiple" qui retourne TOUS les résultats correspondant à la requete
    balisesSection = document.querySelectorAll("section")

    //Modifier le contenu html d'un element
    paragraphe.innerHTML = "Le texte a été modifié ceci est important"
    compteur = document.querySelector("#compteur")

    let eltJours = document.querySelector("#jours");
    let eltHeures = document.querySelector("#heures");
    let eltMinutes = document.querySelector("#minutes");
    let eltSecondes = document.querySelector("#secondes");
    

    setInterval(function() {


        if (secondes > 0){
            secondes--;
        } else {
            if(minutes > 0) {
                minutes--;
                secondes = 59;
            } else {
                if (heures > 0) {
                    heures--;
                    secondes = minutes = 59;
                } else {
                    if (jours > 0) {
                        jours--
                        heures = 24
                        secondes = minutes = 59;
                    }
                }
            }
        }
        eltJours.innerHTML = jours;
        eltHeures.innerHTML = heures;
        eltMinutes.innerHTML = minutes;
        eltSecondes.innerHTML = secondes;
       
    },1000);
    
    // setInterval("direct()", 1000)
    
    
} // Fin de window.onload




Date.prototype.getUTCMonth
Date.prototype.getDay
Date.prototype.getHours
Date.prototype.getSeconds

// 25/12/2020 00:00 
let noel = new Date ('2020-12-25 00:00:00')
let date = new Date ()
let difference = noel - date 
console.log(difference/60)

// combien y a-t-il de ms dans un jour 
// 1000ms*60s*60mn*24h = 86 400 000
// Combien y a-t-il de jours dans dodos

let jours = difference / (1000 * 60 * 60 * 24);

// On arrondit à l'entier inférieur
jours = Math.floor(jours);

console.log(jours)
// On recupere le reste apès retrait des jours

let reste = difference % (1000 * 60 * 60 * 24);

// Combien y a-t-il de ms dans 1 heure
// 1000ms * 60s * 60 mn = 3 600 000
// Combien y a-t-il d'heures dans reste

let heures = Math.floor(reste / (1000 * 60 * 60));

console.log(heures)
// On recupere le reste après retrait des heures
reste = reste % (1000 * 60 * 60);

// Combien y a-t-il de ms dans 1 minute
//  1000ms * 60s = 60 000
// Combien y a-t-il de minutes dans reste
let minutes = Math.floor(reste / (1000 * 60));

console.log(minutes);
// On récupere le reste apres retrait des minutes
reste = reste % (1000 * 60);

//Combien y a-t-il de ms dans 1 seconde -> 1000 ms
let secondes = Math.floor(reste / 1000);

console.log(secondes);

console.log(`Il reste ${jours} jours, ${heures} heures, ${minutes} minutes et ${secondes} secondes avant noel`)

