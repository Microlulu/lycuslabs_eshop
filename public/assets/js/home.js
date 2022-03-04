// j'appelle tous mes ids et je leur donne un nom
let stars = document.getElementById('stars');
let moon = document.getElementById('moon');
let mountains_behind = document.getElementById('mountains_behind');
let mountains_front = document.getElementById('mountains_front');
let textMoto = document.getElementById('textMoto');
let btnWhite = document.getElementById('btnWhite');
//let header = document.querySelector('header');
// pour faire un header // NAV BAR

window.addEventListener('scroll',function (){
    let value = window.scrollY;
    stars.style.left = value + 0.25 +'px';
    moon.style.top = value + 1.05 +'px';
    mountains_behind.style.top = value + 0.5 +'px';
    mountains_front.style.top = value + 0 +'px';
    textMoto.style.marginRight = value + 4 +'px';
    textMoto.style.marginTop = value + 1.5 +'px';
    btnWhite.style.marginTop = value + 1.5 +'px';
    //header.style.top = value + 0.5 +'px';
    // pour faire disparaitre la navbar vers le haut quand je scroll





})
