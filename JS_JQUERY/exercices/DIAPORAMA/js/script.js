// La flèche droite passe de img1 à image2 puis metsky
// La flèche gauche passe de metsky à image2 puis img1
// Faire défiler automatiquement toutes les 5 secondes
// Arrêter le défilement quand la souris est sur le diaporama, le reprendre quand elle n'y est plus

let tableauImages = [ "/img1.jpg" , "/image2.jpg", "/metsky.jpg" ];
let ligne ;
let pointeur = []
let imageDiapo = document.querySelector("img")
let test = document.querySelector(".indicateur")

// Début windows.onload
window.onload = () => {
    
    
var refreshIntervalId = setInterval("suivant()",3000);
let eltClickdroit = document.querySelector(".droite")
let eltClickgauche = document.querySelector(".gauche")
let section = document.querySelector("section")




for ( ligne in tableauImages) {
    pointeur.push("<span>&bull;</span>")
    test.innerHTML = `${pointeur}`
  }
  ligne = 0
  test.children[ligne].style = "opacity: 100%"


eltClickdroit.addEventListener("click", suivant)

eltClickgauche.addEventListener("click", precedent)

section.addEventListener("mouseenter", function(){
    clearInterval(refreshIntervalId);
    console.log("entré")
    console.log(test)
})

section.addEventListener("mouseleave", function(){
    refreshIntervalId = setInterval("suivant()",3000);
    console.log("sorti")
    
})



} // Fin window.onload

function suivant () {
    
    if(ligne < tableauImages.length-1){
        ligne++
        test.children[ligne].style = "opacity: 100%"
        test.children[ligne-1].style = "opacity: 50%"

    } else {
        ligne = 0     
        test.children[ligne].style = "opacity: 100%"
        test.children[pointeur.length-1].style = "opacity: 50%"
    }
    
    imageDiapo.src = `images${tableauImages[ligne]}`
}

function precedent () {
    if(ligne > 0){
        ligne--
        test.children[ligne].style = "opacity: 100%"
        test.children[ligne+1].style = "opacity: 50%"
    }else {
        ligne = tableauImages.length-1     
        test.children[ligne].style = "opacity: 100%"
        test.children[0].style = "opacity: 50%"
    }
          imageDiapo.src = `images${tableauImages[ligne]}`
        
}





  
