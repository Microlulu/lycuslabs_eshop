
/* BOUTON DROPDOWN DANS LA NAVBAR PRINCIPALE et POUR FOOTER POUR PRODUITS (BOUTON DYNAMIQUE UTILISANT LA BASE DE DONNEE)*/
// Je crée un script pour mon bouton "products" : c'est un bouton dynamique qui prends en compte tous les produits existants et les affiches sous forme de dropdown.
// À la demande de mon client, j'ai fait un bouton dynamique et pas de page affichant tous les produits comme les e-commerces traditionnels, car mon client est une start-up qui pour l'instant n'a qu'un seul produit.
// Cette startup n'a pas pour projet de faire un site avec beaucoup de produits, le CEO m'a dit qu'au grand maximum, il y aurait 5 produits et tous seront très différents des uns des autres, d'où sa demande de les séparer dans des pages bien distincte sans liens entre eux.
fetch('/api/products')
    .then(response => {
        return response.json()
    })
    .then(products => {
        const listElement = document.getElementById("listsProducts");
        const listElementFooter = document.getElementById("listsProductsFooter");
        // je crée un text vide et je dis dans ma boucle += qui veut dire ajoute, mais ne supprime pas l'ancien produit
        let text = ""
        for (index in products) {
            // et la je viens créer mon ma balise li dans lequel je mets un lien a et je viens faire ma boucle sur mes produits, je dis prends l'id d'un produit dans l'index Products et ajoute son titre et un + pour dire répète cette action dans un
            // autre li pour chaque produits
            text += "<li>" +
                "<a href='/ourproduct/" + products[index].slug + "'>" + products[index].title + "</a>" +
                "</li>"
        }
        listElement.innerHTML = text
        listElementFooter.innerHTML = text
    });



/* NAVBAR PRINCIPALE  : MENU BURGER TOGGLE */
function burgerToggle() {
    let x = document.getElementById("myLinks");
    if (x.style.display === "block") {
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
    if(buttonID==="address-btn"){
        document.getElementById("adresses").style.display="block";
        document.getElementById("orders").style.display="none";
        document.getElementById("licenses").style.display="none";
        document.getElementById("downloads").style.display="none";
        document.getElementById("support").style.display="none";
    }
    if(buttonID === "orders-btn"){
        document.getElementById("adresses").style.display="none";
        document.getElementById("orders").style.display="block";
        document.getElementById("licenses").style.display="none";
        document.getElementById("downloads").style.display="none";
        document.getElementById("support").style.display="none";
    }
    if(buttonID === "downloads-btn"){
        document.getElementById("adresses").style.display="none";
        document.getElementById("orders").style.display="none";
        document.getElementById("licenses").style.display="none";
        document.getElementById("downloads").style.display="block";
        document.getElementById("support").style.display="none";
    }
    if(buttonID === "licences-btn") {
        document.getElementById("adresses").style.display = "none";
        document.getElementById("orders").style.display = "none";
        document.getElementById("licenses").style.display = "block";
        document.getElementById("downloads").style.display = "none";
        document.getElementById("support").style.display = "none";
    }
    if(buttonID === "support-btn"){
        document.getElementById("adresses").style.display="none";
        document.getElementById("orders").style.display="none";
        document.getElementById("licenses").style.display="none";
        document.getElementById("downloads").style.display="none";
        document.getElementById("support").style.display="block";
    }
}

/* FONCTION PUR CHANGER LARGE IMAGE PAR PETITES (pour page products, et page services (template 1 et template 2)*/
// j'appelle mon id de ma grande image et mon id de ma petite images ( qui possèdent des routes vers la BDD en id )
function showImage(mainImgSrc, imageSrc) {
    let mainImage = document.getElementById(mainImgSrc);
    let thumbServ = document.getElementById(imageSrc);
    // Et je dis simplement que petite image + grande image = grande image + petite image
    // De cette façon, cela changera la disposition des images
    // La petite image prendra la place de la grande et inversement.
    [thumbServ.src, mainImage.src] = [mainImage.src, thumbServ.src];
}



/*FONCTION POUR CHANGER LE BACKGROUND DES CARTES ADRESSES DANS LE PROFIL QUAND CLIQUEES*/
// Je crée un tableau de couleurs qui reste dans ma charte
function colorize(containerId) {
    let colors = ['#E4E4EC', '#DFE1EC', '#E0E5EB', '#DBE3EE', '#E6EAF0'];
    // Je les fais apparaitrent de manière aléatoire en prenant la longueur de mon tableau
    let randomColor = colors[Math.floor(Math.random() * colors.length)]
    //Je récupère le container dont je veux changer la couleur
    let adresseBoxes = document.getElementsByClassName('adresses_box');
    // Et je fais une boucle qui dit que si mon fond et blanc, il faut appliquer un style sur le fond
    for (let i = 0; i < adresseBoxes.length; i++) {
        adresseBoxes[i].style.background = getComputedStyle(document.body).getPropertyValue('--whitebg');
    }
    // et je precise ici que c'est le container qui est visé et que je lui applique ma variable ramdomColor
    let container = document.getElementById(containerId);
    container.style.background = randomColor;
}

/* POP UP ALERT POUR CONFIRMER LORS D'UNE SUPRESSION DANS LE PANEL ADMIN*/
const teams = document.getElementById('memberTeam');
if (teams){
    teams.addEventListener('click', e => {
        if (e.target.className === 'myClass') {
            if (confirm('Are you sure that you want to delete this?')) {
                const id = e.target.getAttribute('data-id');
                alert(id);
            }
        }
    })
}