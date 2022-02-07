# Cours Symfony

Mandy Ibéné - 2022/01/17

- [Cours Symfony](#cours-symfony)
  - [Créer un nouveau projet](#créer-un-nouveau-projet)
  - [Lancer un serveur](#lancer-un-serveur)
  - [Base de données et entités](#base-de-données-et-entités)
    - [Création et connexion à la base de données](#création-et-connexion-à-la-base-de-données)
    - [Ajout de tables et création d'entités](#ajout-de-tables-et-création-dentités)
    - [Ajouter une propriété après création de l'entité](#ajouter-une-propriété-après-création-de-lentité)
      - [Ajouter une relation](#ajouter-une-relation)
  - [Créer les crud automatiquement](#créer-les-crud-automatiquement)
  - [Créer un formulaire](#créer-un-formulaire)
  - [Controller](#controller)
    - [Créer un nouveau controller](#créer-un-nouveau-controller)
    - [Le routage (Routing)](#le-routage-routing)
    - [La méthode *render*](#la-méthode-render)
    - [Les classes utiles](#les-classes-utiles)
      - [EntityManager](#entitymanager)
      - [Request](#request)
  - [Entity](#entity)
  - [Langage TWIG](#langage-twig)
    - [Les délimiteurs](#les-délimiteurs)
    - [Les conditions](#les-conditions)
    - [Les boucles](#les-boucles)
  - [Les méthodes *asset* et *path*](#les-méthodes-asset-et-path)
    - [La méthode *asset*](#la-méthode-asset)
    - [La méthode *path*](#la-méthode-path)
      - [Avec paramètres](#avec-paramètres)
  - [Le système d'authentification](#le-système-dauthentification)
    - [Créer l'entité User](#créer-lentité-user)
    - [Ajouter des propriétés](#ajouter-des-propriétés)
    - [Ajouter les méthodes (connexion/déconnexion, inscription)](#ajouter-les-méthodes-connexiondéconnexion-inscription)
      - [Connexion et déconnexion](#connexion-et-déconnexion)
      - [Inscription](#inscription)
        - [La fonction *is_granted*](#la-fonction-is_granted)
    - [Access control](#access-control)
  - [Le fichier services.yaml](#le-fichier-servicesyaml)
  - [Ajouter Booststrap](#ajouter-booststrap)
    - [Mettre en forme les formulaires](#mettre-en-forme-les-formulaires)
    - [Supprimer des photos de la BDD lorsque qu'elle ne sont pas utilisées](#supprimer-des-photos-de-la-bdd-lorsque-quelle-ne-sont-pas-utilisées)
    - [Pour faire un reset password](#pour-faire-un-reset-password)

<!-- ____________________________________________________________________ -->

## Créer un nouveau projet

Pour créer un nouveau projet Symfony en utilisant Composer (préférentiellement), entrer dans une console, à la racine de htdocs, la commande :

    composer create-project symfony/website-skeleton my_project "5.3.*"

ou en utilisant le CLI de Symfony :

    symfony new my_project --webapp --version=5.3

Il demande pendant l'installation si on veux installer Doker (pas necessaire si pas de demande particulières)
--> no

## Lancer un serveur

Pour lancer un server, entrer dans le terminal VSCode la commande :

    symfony server:start

Puis cliquer suivre le lien http (par défaut, si aucun autre serveur est lancé, http://127.0.0.1:8000).

Pour stopper le server il suffit d'entrer :

    symfony server:stop

(ou de quitter VSCode tout simplement)


<!-- ____________________________________________________________________ -->


## Base de données et entités

### Création et connexion à la base de données

Dans le fichier *.env*, commenter la ligne :

    DATABASE_URL="postgresql://symfony:ChangeMe@127.0.0.1:5432/app?serverVersion=13&charset=utf8"

puis décommenter la ligne :

    DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"

Remplacer db_user, db_password par le nom d'utilisateur et le mot de passe, 127.0.0.1:3306 par l'adresse de la base de données si besoin (en local pas besoin) ainsi que db_name par le nom de la base de données.

    DATABASE_URL="mysql://root:@127.0.0.1:3306/doranco_symfony_1?serverVersion=5.7"

Si la bdd n'existe pas encore, il est possible de la créer via Symfony. Dans le terminal, entrer la commande suivante :

    symfony console doctrine:database:create

### Ajout de tables et création d'entités

> Il est conseillé de passer par Symfony, et non par phpmyadmin, pour faire des modifications dans la base de données, sinon il peut vite y avoir des erreurs.

    symfony console make:entity article

avec article le nom de l'entité, Symfony va se charger d'y mettre une majuscule automatiquement.

L'id de la table est créé par défaut. Symfony demande si on veut rajouter des propriétés à la table, leurs types, leurs tailles et si elles peuvent être nulle ou non.

Cela créé **2 fichiers** : un dans **/src/Entity/** et un autre dans **/src/Repository/**

Pour créer la table dans la bdd, il faut entrer une commande de mise à jour :

    symfony console doctrine:schema:update --force

qui fait la même chose que :

    symfony console d:s:u -f

### Ajouter une propriété après création de l'entité

Pour ajouter une propriété à une table, il suffit d'entrer de nouveau la commande utilisée pour créer l'entité, pour Article par exemple :

    symfony console make:entity Article

Puisque l'entité existe déjà, Symfony demande si on veut rajouter des propriétés. Il faut ensuite de nouveau mettre à jour la bdd :

    symfony console doctrine:schema:update --force

#### Ajouter une relation

Lors de l'ajout d'une propriété, il est possible de créer une relation.

Prenons l'entité Article par exemple. Un article est écrit par un utilisateur. On va donc créer une propriété user à Article qui fera le lien avec un utilisateur de la table user.

On choisit le type de relation au moment de donner le type de la propriété. Il y a plusieurs choix de relations :
- **OneToOne** : dans notre exemple, cela voudrait dire qu'un article ne peut être écrit que par un utilisateur et qu'un utilisateur ne peut écrire qu'un article.
- **OneToMany** : dans notre exemple, cela voudrait dire qu'un article peut avoir plusieurs auteurs mais que chaque auteur ne peut écrire qu'un seul article.
- **ManyToOne** : dans notre exemple, cela voudrait dire qu'un article ne peut avoir qu'un seul auteur mais qu'un auteur peut écrire plusieurs articles (ce qui va être notre choix).
- **ManyToMany** : dans notre exemple, cela voudrait dire qu'un article peut être écrit par plusieurs auteurs et que chaque auteur peut écrire plusieurs articles.

Pour ajouter la propriété user et créer une relation, on entre donc la commande (faire très attention à la majuscule pour la 3ème question !) :

    symfony console make:entity Article

    New property name (press <return> to stop adding fields):
    > user

    Field type (enter ? to see all types) [string]:
    > ManyToOne

    What class should this entity be related to?:
    > User

    Is the Article.user property allowed to be null (nullable)? (yes/no) [yes]:
    > no

    Do you want to add a new property to User so that you can access/update Article objects from it - e.g. $user->getArticles()? (yes/no) [yes]:
    >

    A new property will also be added to the User class so that you can access the related Article objects from it.


    New field name inside User [articles]:       
    >

    Do you want to activate orphanRemoval on your relationship?
    A Article is "orphaned" when it is removed from its related User.
    e.g. $user->removeArticle($article)

    NOTE: If a Article may *change* from one User
    to another, answer "no".

    Do you want to automatically delete orphaned App\Entity\Article objects (orphanRemoval)? (yes/no) [no]:
    > no

La dernière question permet de choisir si oui on non on souhaite supprimer tous les articles écrit par un utiliasteur si jamais on supprime ce dernier de la bdd (de la table user plus précisement). Généralement dans le blog on ne supprime pas les articles même si l'auteur a été supprimé d'où le "no". Cependant cela implique de gérer nous même le remplacement de la valeur user par autre chose pour les articles concernés, sinon des erreurs vont se produire.

Bien sûr on n'oubli pas de mettre à jour la base :

    symfony console doctrine:schema:update --force

Si il y a une erreur lors de la mise à jour c'est plus que probable que cela vienne des articles déjà présents dans la base de données. En effet, puisqu'on a décidé que la propriété user ne peut pas être nulle, alors les articles déjà présents dans la bdd posent problème parce qu'il n'ont pas de user associé, la valeur est donc nulle.

Dans phpmyadmin, on peut constater que la propriété user est une clé secondaire. Pour chaque artlce, la valeur de user correspond à l'id d'un utilisateur, id qui est la clé primaire de l'utilisateur dans la table user.


Pas besoin de champ user dans le formulaire de création d'article puisque c'est l'utilisateur connecté qui sera automatiquement associé à l'article, on ne lui laisse pas le choix. Mais imaginons que se soit une propriété categorie que l'on est rajouté à article et que cette dernière créé une relation avec une table categorie, on remarquera que le champs categorie n'a pas été ajouté dans le formulaire de création d'article. C'est à nous de le faire manuellement. il faut donc ajouter dans le fichier ArticleType.php situé dans /src/Form/ la ligne :

    ->add(categorie)

<!-- ____________________________________________________________________ -->


## Créer les crud automatiquement

Pour créer automatiquement le controller d'une entité (dans /src/Controller), ses méthodes de crud ainsi que les vues qui leurs sont associées (dans /templates/), il faut entrer la commande :

    symfony console make:crud EntityName

<!-- ____________________________________________________________________ -->


## Créer un formulaire

Il faut entrer la commande :

     symfony console make:form category Category

Le "category" correspond au nom du formulaire, "Category" correspond au nom de la classe à laquelle est lié le formulaire (ne pas oublier la majuscule).

Dans /src un nouveau dossier Form est créé avec à l'intérieur un fichier CategoryType.php.

>Il est possible de faire une mise en forme par défaut pour les formulaires, se référé à la section [Mettre en forme les formulaires](#mettre-en-forme-les-formulaires).


<!-- ____________________________________________________________________ -->


## Controller

### Créer un nouveau controller

Pour créer un nouveau controller (HomeController.php pour l'exemple) :

    symfony console make:controller home

>Les controllers créés étendent tous la classe AbstractController, ce qui leur permet d'accéder à des méthodes toutes faites, par exemple en écrivant $this->getUser() dans le controller, on accède à la méthode *getUser* qui retourne l'utilisateur (si le système d'authentification est mis en place) actuellement connecté.

### Le routage (Routing)

Au-dessus de chaque fonction du controller une anotation écrite de cette manière :

    #[Route('/home', name: 'home')]

**ou** alors :

    /**
     * @Route("/home", name="home")
     */

<font color="yellow">⚠ Il faut absolument respecté la syntaxe (espaces, ' ou ", : ou =) et mettre l'annotation juste au dessus de la déclaration de la fonction. ⚠</font>

L'annotation a au minimum deux attributs : l'url et le name.
- L'URI, ajoutée à la suite du lien du serveur dans le navigateur, permet d'accéder à la vue passée en argument du render.
- Le name est le mot utilisé pour appelé la méthode associée, il doit être unique dans tout le projet.


### La méthode *render*

La méthode *render* est héritée de AbstractController. Elle prend en arguments le nom d'une vue qui sera affichée, et un array clés-valeurs. Ce dernier rassemble les variables que l'on souhaite utiliser dans la vue.


### Les classes utiles

#### EntityManager

Elle va se charger du crud (ajouter, voir, modifier, supprimer) alors que le Repository lié à la classe va s'occuper des select, ...


#### Request

Elle permet de capter ce qui a été envoyé via la méthode post.


<!-- ____________________________________________________________________ -->


## Entity

C'est dans le dossier /src/Entity que viendront se placer les classes correspondant aux tables dans la base de données (se référer à la section [Base de données et entités](#base-de-données-et-entités)).

Pour les autres classes, on préférera créé un dossier **Classes** (ou Service, ...) dans /src.


<!-- ____________________________________________________________________ -->


## Langage TWIG

### Les délimiteurs

Les doubles accollades {{ ... }} sont l'équivalent d'un echo en php.

Les {% ... %} sont utilisés pour exécuter des déclarations (affectations, boucles, conditions, ...).


### Les conditions

    {% if condition %}
        {# ... #}
    {% else %}
        {# ... #}
    {% endif %}

Tester si la variable est **définie** :

    {% if var is defined %}
        {# ... #}
    {% endif %}

Tester si la variable est **vide** :

    {% if var is empty %}
        {# ... #}
    {% endif %}

Tester si la variable est **nulle** :

    {% if var is null %}
        {# ... #}
    {% endif %}

...


### Les boucles

    {% for item in tab %}
        {# ... #}
    {% endfor %}

ou encore :

    {% for key, item in tab %}
        {# ... #}
    {% endfor %}


<!-- ____________________________________________________________________ -->


## Les méthodes *asset* et *path*

### La méthode *asset*

La méthode *asset* sert à atteindre les fichiers et dossiers qui se trouvent dans le dossier public.

Par exemple, pour lier un fichier css à une page html, on écrit :

    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">


### La méthode *path*

La méthode *path* sert a appelé les méthodes. En argument on lui passe donc le name des méthodes.

#### Avec paramètres

Lorsque l'on veut passer un paramètre à une méthode, par exemple un id, il faut ajouter {id} dans son URI (dans le controller) :
    
    #[Route('/{id}/edit', name: 'article_edit', methods: ['GET', 'POST'])

et dans la vue :

    {{ path('edit_category', {'id' : item.id}) }}


<!-- ____________________________________________________________________ -->


## Le système d'authentification

Il y a 3 étapes pour créer un système d'authentification :

### Créer l'entité User

    symfony console make:user

Symfony demande ensuite de faire quelques choix de paramétrage, prendre les paramètres par défaut.

Enfin, mettre à jour la bdd :

    symfony console doctrine:schema:update --force


### Ajouter des propriétés

La plupart du temps, à ce stade, les propriétés de User ne sont pas suffisantes. Par défaut il y a l'email, le role et le password, mais il manque des informations comme le nom, le prénom...

Pour ajouter des propriétés (comme vu dans [Ajouter une propriété après création de l'entité](#ajouter-une-propriété-après-création-de-lentité)) il faut entrer la commande :

    symfony console make:entity User

Une fois les nouvelles propriétés ajoutées on met à jour la bdd :

    symfony console doctrine:schema:update --force


### Ajouter les méthodes (connexion/déconnexion, inscription)

#### Connexion et déconnexion

Pour créer les fichiers et méthodes nécessaires à la connexion et la déconnexion, il faut entrer la commande :

    symfony console make:auth

Il faut ensuite faire quelques paramétrages :

    What style of authentication do you want? [Empty authenticator]:
    [0] Empty authenticator
    [1] Login form authenticator
    > 1

    The class name of the authenticator to create (e.g. AppCustomAuthenticator):
    > AppCustomAuthenticator

    Choose a name for the controller class (e.g. SecurityController) [SecurityController]:     
    >

    Do you want to generate a '/logout' URL? (yes/no) [yes]:
    >

Aller dans le fichier /src/Security/AppCustomAuthenticator.php nouvellement créé et, dans la fonction onAuthenticationSuccess(), décommenter la ligne ci-dessous et changer 'some_route' par le name de la page sur laquelle Symfony doit nous rediriger quand on s'est authentifié, en l'occurence 'home' (s'il existe, sinon créer un controller pour home : [Créer un nouveau controller](#créer-un-nouveau-controller)) :

        return new RedirectResponse($this->urlGenerator->generate('home'));

Aller ensuite dans le fichier /config/packages/security.yaml, décommenter la ligne :

    # target: app_any_route

dans :

    logout:
            path: app_logout
            # where to redirect after logout
            # target: app_any_route

puis remplacer app_any_route par home.

<font color="yellow">⚠ Attention à l'indentation, target doit se trouver au même niveau que path ⚠</font>

#### Inscription

Pour créer le formulaire d'inscription, il faut entrer la commande :

    symfony console make:registration-form

Il faut ensuite faire quelques paramétrages :

    Do you want to add a @UniqueEntity validation annotation on your User class to make sure duplicate accounts aren't created? (yes/no) [yes]:
    >

    Do you want to send an email to verify the user's email address after registration? (yes/no) [yes]:
    > no

    Do you want to automatically authenticate the user after registration? (yes/no) [yes]:
    >

Dans le fichier /src/Form/RegistrationFormType.php, on ajoute sous :

    ->add(email)

les autres propriétés que l'on a ajouté auparavant (si on a ajouté une date d'inscription dans les propriétés, il n'y a pas matière à la rajouter dans le formulaire, ce n'est pas à l'utilisateur de choisir) :

    ->add('nom')
    ->add('prenom')
    ->add('adrese')
    

Puis, dans le fichier /templates/registration/register.html.twig, il faut ajouter les champs soit-même sinon ils seront automatiquement mis à la fin du formulaire :

    {{ form_start(registrationForm) }}
        {{ form_row(registrationForm.email) }}
        {{ form_row(registrationForm.nom) }}
        {{ form_row(registrationForm.prenom) }}
        {{ form_row(registrationForm.adresse) }}
        {{ form_row(registrationForm.plainPassword, {
            label: 'Password'
        }) }}
        {{ form_row(registrationForm.agreeTerms) }}

        <button type="submit" class="btn btn-success">Register</button>
    {{ form_end(registrationForm) }}


Utiliser le formulaire d'inscription pour créer un nouvel utilisateur.

Pour ajouter le rôle d'administrateur à un utilisateur, via phpmyadmin, dans la table user, modifier la valeur roles de l'utilisateur choisi en :

    ["ROLE_ADMIN"]

écrit exacetement comme ceci. 


##### La fonction *is_granted*

Les rôles servent à limiter l'affichage et les actions. On peut notamment les utilisé avec la fonction twig *is_granted*. Cette dernière permet de vérifier le rôle de l'utilisateur. Par exemple on rentre dans la condition if suivante :

    {% if is_granted('ROLE_ADMIN') %}
        {# ... #}
    {% endif %}

si l'utilisateur connecté est un administrateur.

### Access control

ajouter l'Acces control dans notre fichier permettant la création d'un projet Symfony. l'ajouter après la 9ème étape ! 

1)  Acces control :
    Dans le fichier security.yaml (config/packages) décommenter la ligne , bien respecter les espaces au debut:
    access_control:
    "{ path: ^/admin, roles: ROLE_ADMIN }" 

On creer ensuite un dossier Admin dans les Controllers et on y place tout les controllers dont seuls l'admin sera concerner (ex : Categories, Images, Produits...)
 (donc: ProduitController.php, CategoryController.php,...)

    Dans chacun des controllers du dossier "Admin":
    Il faut ensuite changer les namespaces des fichiers comme ceci :
        2) Ajouter "\Admin" dans le namespace (à la fin de la ligne)
        ex : namespace App\Controller\Admin;

        3) Ajouter "admin/" dans l'annotation de la class au début de l'URI 
        (ex: #[Route('/produit')] devient #[Route('/admin/produit')]

autres exemples :
ex : #[Route('/admin/images')]
ex : #[Route('/admin/category')]
ex : #[Route('/admin/produit')]

<!-- ____________________________________________________________________ -->

## Le fichier services.yaml

Lorsque des constantes sont utilisées plusieurs fois dans le projet de manière globale, le mieux est de les déclarer dans le fichier services.yaml situé dans le dossier /config/. On ajoute nos constantes sous parameters. Par exemple le chemin du dossier dans lequel on stocke les images chargées :

    parameters:
        upload_dir: '%kernel.project_dir%/public/images/'

Dans un controller, pour atteindre cette constante, il suffit de faire $this->getParameter('upload_dir').

<!-- ____________________________________________________________________ -->


## Ajouter Booststrap

Dans le dossier /public/, ajouter le dossier assets/ et le dossier images/. Dans le dossier /public/assets, ajouter les dossiers css/ et js/.

Télécharger le code source css et js de Bootstrap (https://getbootstrap.com/docs/5.1/getting-started/download/). Dézipper et copier les fichiers bootstrap.min.css et bootstrap.min.js respectivement dans les dossier /public/assets/css/ et /public/assets/js/ créés précedemment.

Puis dans le fichier base.html.twig du dossier template, ajouter les liens vers le css et le js de bootstrap, respectivement dans les blocs stylesheets et javascripts :

    {% block stylesheets %}
            <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    {% endblock %}

    {% block javascripts %}
            <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    {% endblock %}


### Mettre en forme les formulaires

Il est possible de faire adopter au formulaires un style par défaut. Dans le fichier /config/packages/twig.yaml, pour que les formulaires adoptent le style de Bootstrap, sous la ligne :

    default_path: '%kernel.project_dir%/templates'

on peut venir ajouter (au même niveau d'indentation) :

    form_themes: ['bootstrap_4_layout.html.twig']

### Supprimer des photos de la BDD lorsque qu'elle ne sont pas utilisées

Il faut utiliser la fonction unlink()
il faut modifier dans l'edit et dans la delete :
Afin de dire a la BDD si on édit et que la photo est remplacée et n'est plus utilisée: ne la garde pas, supprime la.
Si on la supprime de notre article ou fiche produit : ne la garde pas, supprime la.

Comme ceci :

  #[Route('/{id}/edit', name: 'images_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Images $image, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ImagesType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('images/edit.html.twig', [
            'image' => $image,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'images_delete', methods: ['GET'])]
    public function delete(Request $request, Images $image, EntityManagerInterface $entityManager): Response
    {
        
            $entityManager->remove($image);
            $entityManager->flush();
            $filename = $this->getParameter('upload_dir_produits') . $image->getImgname();
           
            if(file_exists($filename)){
                unlink($filename);
            }
            

            $id = $image->getProduit()->getId();

        return $this->redirectToRoute('produit_show', ['id' => $id ], Response::HTTP_SEE_OTHER);
    }
}


### Pour faire un reset password
Dans le terminal : composer require symfonycasts/reset-password-bundle

ensuite on fait :
symfony console make:reset-password

on reponds a la question :
 What route should users be redirected to after their password has been successfully reset? [app_home]: 
 > home



 What email address will be used to send reset confirmations? e.g. mailer@your-domain.com:   
 > lucile.debuchy@gmail.com
 je mets l'adresse du support technique c'est mieux : (il faut que ca soit une boite mail a laquelle j'ai accès)
 ex : support@lycuslabs.com

 What "name" should be associated with that email address? e.g. "Acme Mail Bot":
 > support

 ensuite on va sur le fichier .env
 et on change la ligne MAILER_DSN comme ceci :
 > MAILER_DSN=mailjet+api://660154336e069c57eac663b5adc88507:f08b2ed854cff0922a2f66c4420b6aba@api.mailjet.com
 (pour mailjet on prends la clé public que l'on mets avant les :
 et on prends la clé privé que l'on place apres les : comme ci dessus)


 au peut utiliser yopmail pour faire nos tests si ca ne fonctionne pas.
 on creer une adresse bidon qui n'est pas utilisé, et on peux s'envoyez un mail pour vérifier si ca fonctionne.

 on n'oublie pas de rajouter dans les templates > security > login un lien a href
 pour afficher la page reset password !
 ex :   <a href="{{ path('app_forgot_password_request') }}">Mot de passe oublié?</a>