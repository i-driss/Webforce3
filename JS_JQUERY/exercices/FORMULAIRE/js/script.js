let PseudoBool, EmailBool, PasswordBool; envoyer

window.onload = () => {
    
    
    let eltPseudo = document.querySelector("#ipseudo");
    eltPseudo.addEventListener("click", function() {
        console.log("L'utilisateur a cliqué sur pseudo");
    });

    
    eltPseudo.addEventListener("input", function() {
        let nb
        nb = eltPseudo.value.length
            
        if(nb <= 5){
            eltPseudo.style.border = "solid 3px green"
            PseudoBool = true
        }else {
            eltPseudo.style.border = "solid 3px red"
            PseudoBool = false
        }
        veriForm()
    });
    
    
    eltPseudo.addEventListener("focusin", function() {
        let nb
        nb = eltPseudo.value.length
        if(nb <= 5 && nb != 0){
            eltPseudo.style.border = "solid 3px green"
        }else {
            if(nb > 5){
                eltPseudo.style.border = "solid 3px red"
            }
        }
    });
    
    let eltMail2 = document.querySelector("#imail2");
    let eltMail = document.querySelector("#imail");

    eltMail2.addEventListener("blur", function() {

        if(this.value === eltMail.value && this != "") {
            this.style.border = eltMail.style.border = "solid 3px green"
            EmailBool = true
        }else {
            if(this.value != eltMail.value && this != "")
            this.style.border = eltMail.style.border = "solid 3px red"
            EmailBool = false
        }
        veriForm()
    });
    

    let eltPassword = document.querySelector("#ipassword")
    let eltPassword2 = document.querySelector("#ipassword2")
    
    eltPassword2.addEventListener("blur", function(){

        if(eltPassword.value === this.value ) {
            this.style.border = eltPassword.style.border = "solid 3px green"
            PasswordBool = true
        }else if (eltPassword.value != eltPassword2.value){
            this.style.border = eltPassword.style.border = "solid 3px red"
            PasswordBool = false
        }

        veriForm()
    });

    let bouton = document.querySelector("div button")
    
    
    bouton.addEventListener("click", function(){
        
        if (eltPassword.type === "password"){
            eltPassword.type = eltPassword2.type = "text"
            
            
        } else {
            eltPassword.type = eltPassword2.type = "password" 
        }
    })
    
    console.log(eltMail.value)
    console.log(eltMail2.value)
    console.log(eltPassword.value)
    console.log(eltPassword2.value)
    console.log(eltPseudo.value.length)
    console.log(envoyer)
    
    
}// Fin window.onload

function veriForm() {
     envoyer = document.querySelector("#envoyer")

    if (PseudoBool && EmailBool && PasswordBool ) {
        envoyer.style.display = "initial"
    } else {
        envoyer.style.display = "none"
    }
}

 



