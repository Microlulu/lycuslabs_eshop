
// BOUTON PODUITS DYNAMIQUE
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


// MENU BURGER TOGGLE
/*function abc(){
    var element = document.getElementById("myLinks");
    element.classList.toggle("mystyle");

}*/

function burgerToggle() {
    let x = document.getElementById("myLinks");
    if (x.style.display == "block") {
        x.style.display = "none";
    } else {
        x.style.display = "block";
    }
}


/**/












const ProductImg = document.getElementById("ProductImg");
const SmallImg = document.getElementsByClassName("small-img");

/*SmallImg[0].onclick =function ()
{
    ProductImg.src = SmallImg[0].src;
}
SmallImg[1].onclick =function ()
{
    ProductImg.src = SmallImg[1].src;
}
SmallImg[2].onclick =function ()
{
    ProductImg.src = SmallImg[2].src;
}
SmallImg[3].onclick =function ()
{
    ProductImg.src = SmallImg[3].src;
}
*/

