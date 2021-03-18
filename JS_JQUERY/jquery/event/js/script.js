$(function () {
    $('#button').on('click', function(){
        $('p').fadeToggle()
        $('#button').text('Afficher')
    });

  
    

    // $('#valider').text('Cacher').attr('id', 'cacher')


    $('h2, p').wrapAll('<div></div>')

    $('div').mouseenter(function(){
        $(this).css('background-color', 'green') 
        $(this).css('border', '4px solid black')
    });
    $('div').mouseleave(function(){
        $(this).css('background-color', 'yellow')
        $(this).css('border', '1px solid black') 
    });

    $('h1').dblclick(function() {
        $(this).css('color', 'red')
    })

    $( "div" ).mousedown(function() {
        $(this).css('background-color', 'grey') 
      });

    $( "div" ).mouseup(function() {
        $(this).css('background-color', '') 
      });
   
      $('body').append('<p id="log"></p>')
      $( document ).on( "mousemove", function( event ) {
        $( "#log" ).text( "les coordonées de la souris sont: " + event.pageX + "X, " + event.pageY + "Y");
    });

    // $('body').append('<div id="pointeur"></div>')
    // $('#pointeur').css("width", "20px").css("height", "20px").css("border-radius", "20px").css("background-color", "red").css("position", "absolute")
    // let x
    // $(document ).on( "mousemove", function( event ) {
    //     x = event.pageX+"px"
    //     y = event.pageY+"px"
        
    //     $('#pointeur').css("left", x).css("transform", "translateX(-50%)").css("top", y).css("transform", "translateY(-50%)")
    // });
    $('input').after('<span></span>')
    $("input").keydown(function(event){
        $(this).css("background-color", "lightgreen")
        console.log(event.originalEvent.key)
        if(event.key == "g"){
            $('span').text("Bien joué")
        } else {
            $('span').text("")
        }
    })
    $("input").keyup(function(){
        $(this).css("background-color", "red")
    })

    $("input").on({
        'focus': function(){$(this).css("background-color", "lightgrey")},
        'focusout': function(){$(this).css("background-color", "")}
    })
    
    let stop

    $("#button").on({
        'click': function(){$('body').css("background-color", "lightgrey")},
        'click': function(){$('input').trigger('focus')},
        'mouseover': function(){$('body').css("background-color", "lightgrey")},
        'mouseleave': function(){$('body').css("background-color", "")}
    })
    
    $("#deactivate").on({
        'click': function(){$('#button').off('click')},
    })
    $("#activate").on("click", function(){
        $('#button').on('click', function(){
            $('p').slideToggle()
            $('#button').text('toggle')
        });
    })
    
    $('#button').click(function(){
        $('input').trigger('focus');
        });

    $('#hide').click(function(){
        $('p').toggle(2000, function(){
            console.log("le texte est caché")
            })

        });
       
    $("h1").animate({fontSize:"20px"}, 2000);
    $('div').css("background-color", "lightblue")
    $('div').animate({width: "300px"}, 2000)
    $('#button').on("click", function(){
        $('p').animate({fontSize: "30px" , display: "none"}, 2000)
        
    })
});
// $(‘conteneur’).append(‘<balise>Mon nouveau contenu</balise>’) → Ajoute le nouveau contenu à la fin
// de l’élément conteneur