


$(function () {
    
    $('button').after('<div "><p id="envoye"></p></div>')
    $('button').on("click", function(event){
        event.preventDefault(),
        $("#envoye").text('Formulaire bien envoyé').css('background-color', "lightgreen")

    })
    
    let nom = $('#nom').val()
    let prenom = $('#prenom').val()
    let mail = $('#mail').val()
    let message = $('#message').val()
    

    $('main').append('<div><p></p><p></p><p></p><p></p></div>')
    $('div:eq(1)').css('padding-top', '40px')
    $('button').on("click", function(event){
        $('p:eq(1)').text('Nom : ' + nom )
        $('p:eq(2)').text('Prenom : ' + prenom )
        $('p:eq(3)').text('Mail : ' + mail )
        $('p:eq(4)').text('Message : ' + message )
    })

    

})