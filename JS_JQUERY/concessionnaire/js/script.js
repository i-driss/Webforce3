$(function(){
    let tableauImages = [ "/Assets/background.jpg" , "/Assets/background-2.jpg", "/Assets/background-3.jpg" ];
    let tableauVoiture = [ "/Assets/vehicule1.png" , "/Assets/vehicule2.png", "/Assets/vehicule3.png", "/Assets/vehicule4.png"  ];
    let i = 1
    let n = 1

    setInterval(next, 3000);
    let header = $(':header')
    console.log(header)
    function next() {
        if(i == tableauImages.length){
            i = 0
    
        } 
        $('#imgBackground').css("background-image", "url("+ tableauImages[i] +")")  
        i++
        console.log(i)
    }


    $('.droite').on('click' , function() {
        
        if(n == tableauVoiture.length-1){
            n = 0
            $(this).prev().attr("src", ""+ tableauVoiture[n] +"")
            
    
        }
        n++
        $(this).prev().attr("src", ""+ tableauVoiture[n] +"")
        console.log(n)
        console.log($(this).prev().attr("src", ""+ tableauVoiture[n] +""))
    });

    $('.gauche').on('click' , function() {

        if(n == 0){
            n = tableauVoiture.length
            $(this).next().attr("src", ""+ tableauVoiture[n] +"")
    
        }
        n--
        $(this).next().attr("src", ""+ tableauVoiture[n] +"")
        console.log(n)
    });

    
    $(window).on('scroll' , function(){
               if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                const newRow = $('#grpVoiture').clone();
                 $('#grpVoiture').after(newRow);
               }
           })


    });