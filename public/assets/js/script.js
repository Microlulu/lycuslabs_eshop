
/* BOUTON POUR PRODUITS DYNAMIQUE UTILISANT LA BASE DE DONNEE*/
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
    //Je crée une fonction qui montrera les sections quand le boutons correspondant a la section sera cliqué.
   let buttonID =  button.id;
   //Pour chaque boutons :je prends l'id du boutons et je prends la section associée (l'élement) et je dis :
    // display block sur la section que je veux afficher et display none sur toutes les autres.
    // et je repète l'opération pour chaque bouton.
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
/* NAVBAR UTILISATEUR : MENU BURGER TOGGLE */
/*function burgerToggleUser() {
    let x = document.getElementById("myNavbar");
    if (x.style.display == "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}
*/


