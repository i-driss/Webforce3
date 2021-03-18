$(function () {
    //     console.log('Hello Jquery');

    //    let titles = $('h2');

    //     console.log(titles);

    //     $('h2:gt(2)').hide();
    //     $('#monParagraphe').hide();

    $('h2:first').text('Mon nouveau Titre')
    $('h2:gt(0)').text('Des nouveaux Titres')
    // $('body').html('<h1>Hello Jquery</h1>')
    $('p:first').html('<b>mon nouveau paragraphe </b>')
    $('h2:last').after('<h2>Mon quatrieme titre</h2>')
    $('p:last').after('<p>Un autre paragraphe</p>')
    $('h2:eq(1)').before('<h3>Mon sous titre</h3>')
    $('p').wrap('<div class="maDiv"></div>')
    $('h2:not(h2:last)').css('color', 'blue')
    $('h2:last').css('color', 'green')
    $('body').css('background-color', 'lightgrey')
    $('p').css('text-align', 'center')

    $('body').width(600).css('border', '1px solid black').css('text-align', 'center').css('margin', 'auto').css('padding', '16px')
    $('h2').addClass('secondTitre')
   let eltId = $('h2:first').attr('id')
   console.log(eltId)
   $('p').replaceWith('<p>Un paragraphe identique</p>')
   $('body').css('font-family', 'Helvetica')
   $('#monParagraphe').css('color', 'red')
   let height = $('body').height()
   let width = $('body').width()
    
   $('body').append(` Hauteur = ${height}px, Largeur = ${width}px`)

   let title1 = $('h2:first').text()
   let title2 = $('h2:eq(1)').text()

   $('h2:eq(1)').text(title1)
   $('h2:first').text(title2)
   

   let paragrapheId = $('p').attr('id')
   $('p').append(` L'ID de ce paragraphe est : ${paragrapheId}`)

    console.log(height) 


});