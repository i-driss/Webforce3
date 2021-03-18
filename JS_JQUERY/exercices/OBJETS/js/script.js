let frigo = {
    temperature: 6,
    contenu: {
        legumes: ["courgettes", "carottes"],
        fruits: ["bananes", "tomates"]
        
    }
    
}

let eltSpan = document.querySelector("#alertTemp")
let eltFruits = document.querySelector("#fruits")
let listeFruit = document.querySelector("#listeFruits")

window.onload = () => {

for (let fruit in frigo.contenu.fruits ) {
    fruits = frigo.contenu.fruits[fruit]
    listeFruit.innerHTML = listeFruit.innerHTML + `<li>${fruits}</li>`
}

  let bouttonFruits = document.querySelector("#bouttonFruits")
  let temp = document.querySelector("#temp")
  
   changement()

    temp.addEventListener("input", changement)
    bouttonFruits.addEventListener("click", AjoutFruits)

}//Fin Windows.onload



function changement() {
    frigo.temperature = Number(temp.value)
    if(frigo.temperature > 6) {
        eltSpan.innerText = "trop chaud"
    }
    if(frigo.temperature < 0) {
        eltSpan.innerText = "trop froid"
    }
    if(frigo.temperature <= 6 && frigo.temperature >= 0) {
        eltSpan.innerText = "tout va bien"
    }
    console.log(eltSpan)
    console.log(eltFruits)
}

function AjoutFruits() {
    frigo.contenu.fruits.push(eltFruits.value) 
    listeFruit.innerHTML = listeFruit.innerHTML + `<li>${eltFruits.value}</li>`
}



