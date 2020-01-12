$(document).ready(function() {

    // Cookies

    setTimeout(function () {
        $(".close-cookies").fadeIn(200);
     }, 4000);
    $(".close-cookies, .cookies-ok").click(function() {
        $("#cookies").fadeOut(200);
    }); 

    // Smooth scroll

    $('.scrollTo').on('click', function() {
        let page = $(this).attr('href');
        let fromTop = 50;
        let speed = 750;
        $('html, body').animate( { scrollTop: $(page).offset().top - fromTop}, speed );
        return false;
    });

    // Anim text home

    let textWrapper = document.querySelector('.text-apparition');
    // Utilisation de la regex /\s/g qui remplace la valeur de chacune de mes lettres par mon span, $& fait référence à la valeur de chaque lettre
    textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

    anime.timeline({loop: false})
    .add({
        targets: '.text-apparition .letter',
        opacity: [0,1],
        // Courbe d'accélération douce non linéaire
        easing: "easeInOutQuad",
        // Durée de l'animation
        duration: 500,
        // Délai de 50ms entre chaque apparition de lettre (i+1)
        delay: (el, i) => 50 * (i+1)
    });
});