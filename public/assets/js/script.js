$(document).ready(function() {

    // Connexion menu

    $('#open-connexion').on('click', function() {
        $('#connexion').css("display", "block");
    });
    $('#close-connexion').on('click', function() {
        $('#connexion').css("display", "none");
    });

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
    textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");

    anime.timeline({loop: false})
    .add({
        targets: '.text-apparition .letter',
        opacity: [0,1],
        easing: "easeInOutQuad",
        duration: 500,
        delay: (el, i) => 50 * (i+1)
    });
});