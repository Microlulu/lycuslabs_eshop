
/* BOUTON DROPDOWN DANS LA NAVBAR PRINCIPALE POUR PRODUITS (BOUTON DYNAMIQUE UTILISANT LA BASE DE DONNEE)*/
    // Je crée un script pour mon bouton "products" : c'est un bouton dynamique qui prends en compte tous les produits existants et les affiches sous forme de dropdown.
    // À la demande de mon client, j'ai fait un bouton dynamique et pas de page affichant tous les produits comme les e-commerces traditionnels, car mon client est une start-up qui pour l'instant n'a qu'un seul produit.
    // Cette startup n'a pas pour projet de faire un site avec beaucoup de produits, le CEO m'a dit qu'au grand maximum, il y aurait 5 produits et tous seront très différents des uns des autres, d'où sa demande de les séparer dans des pages bien distincte sans liens entre eux.
    fetch('/api/products')
    .then(response => {
    return response.json()
})
    .then(products => {
    const listElement = document.getElementById("listsProducts");
    // je crée un text vide et je dis dans ma boucle += qui veut dire ajoute, mais ne supprime pas l'ancien produit
    let text = ""
    for (index in products) {
    // et la je viens créer mon ma balise li dans lequel je mets un lien a et je viens faire ma boucle sur mes produits, je dis prends l'id d'un produit dans l'index Products et ajoute son titre et un + pour dire répète cette action dans un
    // autre li pour chaque produits
    text += "<li>" +
    "<a href='/ourproduct/" + products[index].id + "'>" + products[index].title + "</a>" +
    "</li>"
}
    listElement.innerHTML = text
});



/* NAVBAR PRINCIPALE  : MENU BURGER TOGGLE */
function burgerToggle() {
    let x = document.getElementById("myLinks");
    if (x.style.display == "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}


/* NAVBAR DE L'UTILISATEUR DANS LA PAGE PROFILE : BOUTONS */
function showSection(button){
    // Je crée une fonction qui montrera les sections quand le boutons correspondant à la section sera cliqué
    // ET qui laissera le bouton cliqué en gris pour pouvoir se repérer dans la page profile plus simplement

    /* POUR LA COULEUR GRISE*/
    // Je récupère l'id du bouton
   let buttonID =  button.id;
    // Je récupère ensuite tout les boutons ayant la classe "button"
    let allButtons = document.getElementsByClassName("button");
    // Je fais une loop sur les boutons
    for(let i = 0; i<allButtons.length; i++){
        // Si les boutons contiennent la classe active => supprime la classe active
        if(allButtons[i].classList.contains("active")){
            allButtons[i].classList.remove("active");
        }
    }
    // Je récupère le bouton cliqué et je lui ajoute la classe active
    button.classList.add("active");

    /* POUR SWITCHER DE SECTION EN CLIQUANT SUR LES BOUTONS*/
    // Pour chaque bouton :je prends l'id du bouton et je prends la section associée (element) et je dis :
    // display block sur la section que je veux afficher et display none sur toutes les autres.
    // et je répète l'opération pour chaque bouton.
    if(buttonID=="address-btn"){
       document.getElementById("adresses").style.display="block";
       document.getElementById("orders").style.display="none";
       document.getElementById("licenses").style.display="none";
       document.getElementById("downloads").style.display="none";
       document.getElementById("support").style.display="none";
   }
   if(buttonID == "orders-btn"){
       document.getElementById("adresses").style.display="none";
       document.getElementById("orders").style.display="block";
       document.getElementById("licenses").style.display="none";
       document.getElementById("downloads").style.display="none";
       document.getElementById("support").style.display="none";
   }
   if(buttonID == "downloads-btn"){
       document.getElementById("adresses").style.display="none";
       document.getElementById("orders").style.display="none";
       document.getElementById("licenses").style.display="none";
       document.getElementById("downloads").style.display="block";
       document.getElementById("support").style.display="none";
   }
   if(buttonID == "licences-btn") {
       document.getElementById("adresses").style.display = "none";
       document.getElementById("orders").style.display = "none";
       document.getElementById("licenses").style.display = "block";
       document.getElementById("downloads").style.display = "none";
       document.getElementById("support").style.display = "none";
   }
   if(buttonID == "support-btn"){
       document.getElementById("adresses").style.display="none";
       document.getElementById("orders").style.display="none";
       document.getElementById("licenses").style.display="none";
       document.getElementById("downloads").style.display="none";
       document.getElementById("support").style.display="block";
   }
}




/* FONCTION POUR VOIR DANS MON CODE TOUS LES TAGS UTILISES */
/* Le but de cette fonction est d'enlever le * dans mon CSS et d'optimisé le class * uniquement avec les tags dont je me sers sans englober tous les tags.*/
/* Pour me servir de cette fonction il faut que je rajoute un paragraphe dans mon HTML comprenant l'id "allTagsUsed" <p id="allTagsUsed"></p>*/
/*
// Je crée une constante collection et je viens récupérer tous les tags
const collection = document.getElementsByTagName("*");
// Je précise que je veux un texte.
let text = ""
// Et je viens créer une boucle qui prendra un par un tous les tags et les affichera dans mon paragraphe sous forme de texte.
for (let i = 0; i < collection.length; i++) {
    // Ma boucle signifie : prends l'index et commence à zéro, prends toute la longueur de la collection (donc tous les tags) et ajoute +1 a chaque fois que tu en trouves un.
    text += collection[i].tagName + "<br>";
    // Dispose les tags de ma collection en utilisant leur nom de tags et rajoute un <br> à fin de chaque tag pour que ça soit plus lisible.
}
document.getElementById("allTagsUsed").innerHTML = text;
// Prends l'id "AllTagsUsed" dans mon HTML et donne le texte que j'ai demandé.
*/
