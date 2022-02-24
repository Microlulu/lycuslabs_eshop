/* BURGER MENU */

 /*   const openBurger = document.querySelector('#burger');
    const mainMenu = document.querySelector('.mainMenu');
    const closeMenu = document.querySelectorAll('#close');

    // Toggle Navbar
    openBurger.addEventListener('click',show);
    closeMenu.addEventListener('click',close);


function show(){
    mainMenu.style.display = "block"
    mainMenu.style.right = "0"
}
function close(){
    mainMenu.style.right = "-100%"


}
*/





/* PAGE LOGIN / REGISTER */

/* J'appelle toutes mes classes avec le query selector et je leur attribue des noms */
let switchCtn = document.querySelector('#switch-cnt');
let switchC1 = document.querySelector('#switch-c1');
let switchC2 = document.querySelector('#switch-c2');
let switchCircle = document.querySelectorAll('.switch__circle');
let switchBtn = document.querySelectorAll('.switch-btn');
let aContainer = document.querySelector('#a-container');
let bContainer = document.querySelector('#b-container');
let allButtons = document.querySelectorAll('.submitRight');

/* La méthode preventDefault() de l'interface Event indique à l'utilisateur que si l'événement n'est pas explicitement
traité, son action par défaut ne doit pas être appliquée comme elle le serait normalement.
L'événement continue à se propager comme d'habitude, sauf si un EventListener appelle stopPropagation() ou
stopImmediatePropagation(), ce qui met fin à la propagation immédiatement. */
let getButtons = (e) => e.preventDefault();

let changeForm = (e) => {
    switchCtn.classList.add('is-gx');
    /* La méthode setTimeout() définit une minuterie qui exécute une fonction ou un morceau de code spécifié une fois
    que la minuterie a expiré.*/
    /* Je set un timer a 1500secondes et je lui dis: quand le temps est écoulé enlève switchCtn*/
    setTimeout(function () {
        switchCtn.classList.remove('is-gx');
    }, 1500);

    /* Je crée des toggles pour pouvoir par la suite switcher les cards sign in et register.
      J'attribue une classe is-txl de mon css qui contient une transition et un déplacement sur la droite sur les containers a et b
      ce qui permettra de le déplacer vers la droite à chaque clique.

      J'attribue en suite à ma variable switchC1 et switchC2 un toggle 'is-hidden" qui permets de cacher le bloc en fonction du container principal appelé.
      SwitchC1 étant un bloc texte réservé à mes utilisateurs possédant déjà un compte "welcome back".
      SwitchC2 étant un bloc texte réservé à mes utilisateurs qui ne possèdent pas de compte "Hello friend".

      J'attribue une classe is-txr de mon css qui contient une transition et un déplacement vers la gauche sur les containers a et b
      ce qui permettra de le déplacer vers la droite à chaque clique.

      Les toggles me permettent de dire que s'il y a une classe retire la s'il n'y en a pas ajoute là.
      Ils me permettront également de changer les textes au moment des switchs grave au lien avec les classes de mon CSS */
    switchCtn.classList.toggle('is-txr');
    switchCircle[0].classList.toggle('is-txr');
    switchCircle[1].classList.toggle('is-txr');

    switchC1.classList.toggle('is-hidden');
    switchC2.classList.toggle('is-hidden');
    aContainer.classList.toggle('is-txl');
    bContainer.classList.toggle('is-txl');
    bContainer.classList.toggle('is-z200');
};

/* Une fois que tout mes toggles sont créés je peux désormais ajouter un EventListener pour dire que a chaque fois qu'un des boutons sera cliqué il faut qu'il change de formulaire*/
let mainF = (e) => {
    for (var i = 0; i < allButtons.length; i++) allButtons[i].addEventListener('click', getButtons);
    for (var i = 0; i < switchBtn.length; i++) switchBtn[i].addEventListener('click', changeForm);
};

window.addEventListener('load', mainF);

/* FIN PAGE LOGIN / REGISTER */