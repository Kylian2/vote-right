# Le Frontend

Le frontends de notre projet utilise le framework NuxtJs.
Le fichier suivant contiendra toutes les informations nécessaires au développement du frontend. 

Les documentations de vue et nuxt sont disponibles aux liens ci-dessous, pour des informations plus précises et les détails de ces frameworks, il est judicieux de les consulter. 
- VueJs : https://vuejs.org/
- NuxtJs : https://nuxt.com/

## Installation 

Pour faire fontionner le frontend, il est impératifs d'avoir [NodeJs](https://nodejs.org/en) d'installé. ([Installation de nodeJS)](https://nodejs.org/en/download/package-manager)).

Ensuite, le serveur frontend se lance via la commande `npm run dev`. Il sera disponible sur le port 3000 (de l'adresse locale pour de développement).

*Veillez à avoir démarrer les conteneurs associés au backend et à la base de données du projet (`docker compose up -d`)*

## Les conventions de developpement 

Les variables sont nommées en **anglais** et en **camelCase**,
ex : 
```js
let myProposal = "Hello World";
```

## Le style

Pour styliser notre application, nous utilisons le préprocesseur *Sass*. 

### Organisation
Les feuilles de styles sont disponibles dans le dossier */assets/styles*. Elles organisées dans des fichiers et sous-dossiers de la façon suivante : 
- */components* : Les fichiers de styles relatifs aux composants, ou aux boutons. 
- */layout* : Les fichiers de styles relatifs éléments de structure du site (header, footer...). 
- */pages* : Les éléments de styles propres à chaque pages. 
- */utils* : Ce fichier contient les différents utilitaires du style de l'application, cela peut être les animations, mixins etc.... 
- */variables* : Contient les variables de style. Il faut veiller à ne pas donner des noms trop spécifiques, ex pas de $red pour la couleur d'une erreur mais plutot $error.
- *global.scss* : Les styles globaux à l'application (taille des titres, style des liens...).
- *reset.scss* : Le reset css.
- *main.scss* : Ce fichier contient l'importation de tous les autres fichiers. 

### La méthode BEM

En ce qui concerne le nom des classe, nous utilisont le plus possible la méthode BEM. 

Qu'est-ce que la méthode BEM ? 

La méthode **BEM** (acronyme de **Block, Element, Modifier**) est une méthodologie utilisée en développement front-end pour organiser et structurer le code CSS. Son objectif principal est d'améliorer la lisibilité, la réutilisabilité et la maintenabilité du code en suivant une convention de nommage claire et logique. Voici un aperçu des principaux concepts et avantages de la méthode :

#### Concepts clés
1. **Block (Bloc)** :
   - C’est une entité autonome et indépendante qui représente un composant principal.
   - Un bloc peut être un bouton, un formulaire, un menu, etc.
   - Exemple :
     ```html
     <div class="button"></div>
     <div class="menu"></div>
     ```

2. **Element (Élément)** :
   - Représente une partie constitutive d’un bloc qui n’a pas de sens en dehors de ce bloc.
   - Les éléments sont liés au bloc par un double underscore (`__`).
   - Exemple :
     ```html
     <div class="menu">
       <div class="menu__item"></div>
       <div class="menu__link"></div>
     </div>
     ```

3. **Modifier (Modificateur)** :
   - Représente une variation ou un état du bloc ou de l’élément.
   - Les modificateurs sont ajoutés avec un double tiret (`--`).
   - Exemple :
     ```html
     <div class="button button--primary"></div>
     <div class="menu__item menu__item--active"></div>
     ```

#### Exemple d'utilisation
##### HTML
```html
<div class="card">
  <h2 class="card__title">Titre de la carte</h2>
  <p class="card__description">Description de la carte</p>
  <button class="card__button card__button--disabled">En savoir plus</button>
</div>
```

##### CSS
```css
.card {
  border: 1px solid #ddd;
  padding: 16px;
  background-color: #fff;
}

.card__title {
  font-size: 1.5em;
  margin-bottom: 8px;
}

.card__description {
  font-size: 1em;
  color: #666;
}

.card__button {
  padding: 10px 20px;
  border: none;
  cursor: pointer;
}

.card__button--disabled {
  background-color: #ccc;
  cursor: not-allowed;
}
```

**Avantages**
- **Lisibilité** : Les noms de classes sont explicites et reflètent leur rôle dans le composant.
   
- **Évolutivité** : Les blocs et les éléments peuvent être étendus sans risque de conflit de styles grâce à leur isolation.

- **Réutilisabilité** : Les blocs peuvent être utilisés dans différents contextes avec des modificateurs pour s’adapter.

**Points à surveiller** : 
- **Verbosité** : Les noms de classes peuvent devenir longs et redondants, surtout pour des projets complexes.
- **Rigidité** : Si mal appliqué, BEM peut introduire une complexité inutile.

## Les composants

### Header 

Composant clé de l'application, il est visible sur chaque page, il permet de naviguer de page en page. Il est également adapté aux interfaces mobiles. 

**Props :**

| Nom   | Requise ? | Valeur par défaut | Effet                                                      |
|-------|-----------|-------------------|------------------------------------------------------------|
| Type  | Oui       |                   | Indique si le header est en version connecté ou visiteur   |
| Actif | Non       |              | Indique quel lien est actif (pour styliser en conséquence) |

``` html
<Header type="notlogged"></Header>
```

### Input 

Composant affichant un input et son label. Le label est à indiquer dans le slot. 

Il est possible de spécifier des règles sur les valeurs entrées par l'utilisateur via la props *rules*.

**Props :**

| Nom         | Requise ? | Valeur par défaut | Effet                                                                                                         |
|-------------|-----------|-------------------|---------------------------------------------------------------------------------------------------------------|
| name        | Oui       |                   | Le nom du composant crée, sert à identifié. <br>Il doit être nommé de la même façon que la variable réactive. |
| type        | Oui       |                   | Le type du champs input                                                                                       |
| placeholder | Non       |                   | Le placeholder                                                                                                |
| required    | Non       | false             | Indique si le champs est obligatoire                                                                          |
| rules       | Non       |                   | Le jeu de règles auxquels la valeur entrée doit se soumettre                                                  |

``` html
<Input 
    type="text" 
    name="lastname" 
    placeholder="Entrez votre nom" 
    required
    :rules="[
        (v) => Boolean(v) || 'Un nom est requis', 
    ]"
>Nom</Input>
```

Il est possible de récupérer la valeur du champs (et la validité des règles) avec les variables suivantes: 

```js
//Contient la valeur du champ
const lastname = useState('lastname');

//True si toutes les règles du jeu sont valides. 
const lastnameValid = useState('lastnameValid');
```

### InputNumber

Composant affichant un input de type number et son label. Le label est à indiquer dans le slot. 

Il est possible de spécifier des règles sur les valeurs entrées par l'utilisateur via la props *rules*.

**Props :**

| Nom         | Requise ? | Valeur par défaut | Effet                                                                                                         |
|-------------|-----------|-------------------|---------------------------------------------------------------------------------------------------------------|
| name        | Oui       |                   | Le nom du composant crée, sert à identifié. <br>Il doit être nommé de la même façon que la variable réactive. |                        |
| placeholder | Non       |                   | Le placeholder                                                                                                |
| required    | Non       | false             | Indique si le champs est obligatoire                                                                          |
| rules       | Non       |                   | Le jeu de règles auxquels la valeur entrée doit se soumettre                                                  |
| min       | Non       |                   | La valeur minimum du champs             |
| max       | Non       |                   | La valeur maximun du champ           |
| step       | Non       |     1              | l'incrémentation du champ           |

``` html
<InputNumber name="proposalYear" type="number" :min="1900" :max="2099" :placeholder="`Par défaut : ${new Date().getFullYear()}`"
  :rules="[
      (v) => !v || (v >= 1900 && v <= 2099) || 'La valeur doit être entre 1900 et 2099',
  ]"      
>Année</InputNumber>
```

### ColorPicker 

Composant affichant une liste de couleur permettant à l'utilisateur d'en choisir une.
Une fois la couleurs choisi, il est possible de récupérer le choix avec le même méchanisme que précédement (une variable nommée *name*).

**Props :**

| Nom         | Requise ? | Valeur par défaut | Effet                                                                                                         |
|-------------|-----------|-------------------|---------------------------------------------------------------------------------------------------------------|
| name        | Oui       |                   | Le nom du composant crée, sert à identifié. <br>Il doit être nommé de la même façon que la variable réactive. |
| colors | Oui       |                   | La liste des couleurs, entrées dans un tableau de string au format HEX                                                               |
| rules       | Non       |                   | Le jeu de règles auxquels la valeur entrée doit se soumettre                                                  |

``` html
<ColorPicker 
    name="color"
    :colors="colors"
    :rules="[
            (v) => Boolean(v) || 'Une couleur est requise', 
    ]"
>Couleur</ColorPicker>
```

Exemple de tableau de couleur : 

```js
const colors = useState("colors", () => ["#5AB7EE", "#FDBE55", "#FB961F", "#13329F", "#8700CF", "#F669D9", "#DE3D59"]);
```

### ImagePicker 

Composant affichant une liste d'image permettant à l'utilisateur d'en choisir une.
Une fois la couleurs choisi, il est possible de récupérer le choix avec le même méchanisme que précédement (une variable nommée *name*).

**Props :**

| Nom         | Requise ? | Valeur par défaut | Effet                                                                                                         |
|-------------|-----------|-------------------|---------------------------------------------------------------------------------------------------------------|
| name        | Oui       |                   | Le nom du composant crée, sert à identifié. <br>Il doit être nommé de la même façon que la variable réactive. |
| images | Oui       |                   | La liste des images, entrées dans un tableau de string contenant les chemin jusqu'aux images                               |
| rules       | Non       |                   | Le jeu de règles auxquels la valeur entrée doit se soumettre                                                  |

``` html
<ImagePicker 
    name="image" 
    :images="images" 
    :rules="[
            (v) => Boolean(v) || 'Une image est requise', 
    ]"
    >
    <template #title>Image</template>
    <template #legend>Selectionnez une image de bannière pour votre groupe</template>
</ImagePicker>
```

Exemple de tableau de couleur : 

```js
const images = useState("images", () => ["100001.png", "100002.png", "100003.png", "100004.png", "100005.png", "100006.png", "100007.png"])
```

### ImagePicker 

Composant permettant de choisir un émoji parmis une liste d'émoji.
Une fois l'émoji choisi, il est possible de récupérer le code ascii associé avec le même méchanisme que précédement (une variable nommée *name*).

**Props :**

| Nom         | Requise ? | Valeur par défaut | Effet                                                                                                         |
|-------------|-----------|-------------------|---------------------------------------------------------------------------------------------------------------|
| name        | Oui       |                   | Le nom du composant crée, sert à identifié. <br>Il doit être nommé de la même façon que la variable réactive. |
| placeholder | Non       |    'Choisir'               | Le placeholder  |
| rules       | Non       |                   | Le jeu de règles auxquels la valeur entrée doit se soumettre                                                  |

``` html
<EmojiPicker 
    name="emoji"
    :rules="[
            (v) => Boolean(v) || 'Une couleur est requise', 
    ]"
>Emoji</EmojiPicker>
```

### TextArea 

Composant affichant une zone de texte et sont label. Le label est à indiquer dans le slot. 

**Props :**

| Nom         | Requise ? | Valeur par défaut | Effet                                                                                                         |
|-------------|-----------|-------------------|---------------------------------------------------------------------------------------------------------------|
| name        | Oui       |                   | Le nom du composant crée, sert à identifié. <br>Il doit être nommé de la même façon que la variable réactive. |
| rows        | Non       |       6            | Le nombre de ligne du champ.                                         |
| placeholder | Non       |                   | Le placeholder                                                                                                |
| required    | Non       | false             | Indique si le champs est obligatoire                                                                          |
| rules       | Non       |                   | Le jeu de règles auxquels la valeur entrée doit se soumettre                                                  |

``` html
<TextArea 
    name="description" 
    placeholder="Décrivez votre groupe" 
    :rows='10' 
    required
    :rules="[
        (v) => Boolean(v) || 'Une description est requise', 
    ]"    
>Description</TextArea>
```

### CardCommunity

Affiche une carte contenant les informations de la communauté passé via les props. 

L'objet passé via *community* passé doit avoir le format suivant :
```
{
    community['CMY_name_VC'] : string,
    community['CMY_image_VC'] : string,
    community['CMY_color_VC'] : string,
    community['CMY_emoji_VC'] : string,
    community['CMY_nb_member_NB'] : string | integer,
    community['CMY_themes_TAB'] : Array,
}
```

### CardProposal

Affiche une carte le titre et le thème d'une proposition. 

L'objet passé via *proposal* passé doit avoir le format suivant :
```
{
    community['CMY_title_VC'] : string,
    community['CMY_color_VC'] : string,
    community['CMY_theme_VC'] : string,
}
```

### Banner

Affiche une bannière aux couleurs de la communauté

| Nom         | Requise ? | Valeur par défaut | Effet                                                                                                         |
|-------------|-----------|-------------------|---------------------------------------------------------------------------------------------------------------|
| community        | Oui       |                   | les informations de la communauté, doit contenir CMY_id_NB, CMY_color_VC, CMY_image_VC |
| themes | Oui       |               | Les thèmes à afficher sur la bannières  |
| back       | Non       |    Faux               | Indique si le bouton "retour au groupe" doit apparaitre     

Le slot contient le titre afficher sur la bannière.

ex: 

```html
<Banner :community="community" :themes="communityThemes"> Le titre visible sur la bannière </Banner>
```

### Comment

Affiche un commentaire, avec le nom de l'envoyeur et le message encadré

| Nom         | Requise ? | Valeur par défaut | Effet                                                        |
|-------------|-----------|-------------------|--------------------------------------------------------------|
| comment     | Oui       |                   | L'objet commentaire à afficher                               |

ex: 

```html
<Comment :comment="comment"></Comment>
```

### Modal

Crée une modale. Le modale est composé d'un titre, d'un corps et de deux boutons d'actions, l'un pour valider une action, l'autre pour fermer la modale. 

| Nom           | Requise ? | Valeur par défaut | Effet                                                                                                                                |
|---------------|-----------|-------------------|--------------------------------------------------------------------------------------------------------------------------------------|
| okText        | Non       | Valider           | Le texte du bouton validant.                                                                                                         |
| cancelText    | Non       |                   | Affiche le bouton annulant l'action avec le texte défini dans la variable. <br>Si la variable est nulle, le bouton n'est pas affiché |
| beforeOk      | Non       | () => {}          | La fonction lancée au moment de la validation (appuie sur le bouton OK)                                                              |
| beforeCancel  | Non       | () => {}          | La fonction lancée au moment de l'annulation (appuie sur le bouton Cancel)                                                           |
| BeforeClose   | Non       | () => {}          | La fonction lancée au moment de la fermeture de la modale                                                                            |
| Fullscreen    | Non       | false             | Un boolean indiquant si la modale apparait en plein écran                                                                            |
| name          | Oui       |                   | Permet d'identifier la modal.                                                                                                        |
| placeholder   | Non       |                   | Le placeholder                                                                                                                       |
| required      | Non       | false             | Indique si le champs est obligatoire                                                                                                 |
| disableValid  | Non       | false             | Indique si le bouton Ok doit être désactiver

La props `name` doit toujours être définie, c'est elle qui permet d'identifier la modal et de gérer sont statut ouvert/fermé. Quand cette props est définie, vous avez accès à une variable réactive booléenne `{name}Modal`. 
Lorsque cette variable est `true` la modal est ouverte, `false` la modal est fermée. 

Deux slots sont disponibles dans cette modal. Le slot `#title` comporte le titre de la modal, le slot `#body` le corps de la modal, c'est dans le body que les éléments qui la compose doivent être insérés. 

ex: 

```Html
<Modal
    :name="`infos`"
    ok-text="Valider"
    cancel-text="Annuler"
    :before-ok="() => {console.log('Ok button clicked')}"
    :before-cancel="() => {console.log('Modal closed')}"
    >
        <template #title>Le titre de la modal</template>
        <template #body>  
            <p>
              Ceci est le corps de la modal, le contenu doit être placé ici. 
            </p>
        </template>
</Modal>
```

```js
const open = useState(`$infosModal`, () => false); //permet de controler la modal
```

### Toast

Le toast permet d'afficher temporairement une alerte sur l'écran de l'utilisateur. 
Il y a plusieurs niveau d'alerte : 
- Informations (bleu) (4)
- Validation (vert) (3)
- Alerte (jaune) (2)
- Erreur (rouge) (1)

Le toast est composer d'un slot qui contient le message d'afficher par le toast.

| Nom    | Requise ? | Valeur par défaut | Effet                                                                       |
|--------|-----------|-------------------|-----------------------------------------------------------------------------|
| loader | Non       | false             | Affiche la barre du temps restant                                           |
| time   | Non       | 5                 | La durée d'affichage entière en seconde.                                    |
| name   | Oui       |                   | Le nom permettant d'identifier le toast, il doit être unique à chaque toast |

Une fois la props `name` définie, vous avez accès à une variable booléenne réactive `${name}Up` permettant de gérer le statu up/down du toast.

ex:

```html
<Toast 
    name="toast" 
    :type="3" 
    :time="5" 
    :loader="true"
    class="toast"
>
    J'affiche un message de validation !
</Toast>
```

```js
//permet de gérer le toast
const up = useState('ToastUp');
```

### Profile

Composant affichant un cercle avec en son centre les initiales de l'utilisateur passé dans la props.

| Nom    | Requise ? | Valeur par défaut | Effet                                                                       |
|--------|-----------|-------------------|-----------------------------------------------------------------------------|
| user | Oui       |              | L'utilisateur pour lequel on veut afficher le profil                                       |

ex:

```html
<Profile :user="user"/>
```

```js
const user = useState('user');
```

### Les middleware

Dans l'application, il y a des middlewares permettant de restreindre l'accès à des ressources :

**Application Utilisateur**:

1. *auth* : autorisant l'accès d'une page uniquement aux personnes connectés. Redirige vers */login*
2. *guest* : bloquant l'accès d'une page aux personnes connectés. Redirige vers */home*.
3. *community-member* : bloquant l'accès d'une page aux membres de faisant pas partie de la communautée. Redirige vers */home*.
4. *proposal-access* : bloquant l'accès d'une page aux personnes n'étant pas membre de la communauté rattachée à la proposition. Redirige vers */home*.

**Application Administrateur**:

1. *auth* : autorisant l'accès d'une page uniquement aux personnes connectés. Redirige vers */*
2. *guest* : bloquant l'accès d'une page aux personnes connectés. Redirige vers */home*.
3. *managed* : bloque l'accès aux pages aux utilisateurs n'ayant pas un role de gestion. Redirige vers */home*.
4. *decider* : bloque l'accès aux pages aux utilisateurs n'ayant pas un role de decideur (ou d'administrateur) dans la communauté (se base sur le paramètre de l'url). Redirige vers */home*.
5. *proposal* : bloque l'accès aux pages aux utilisateurs n'ayant pas un de gestion dans la communauté (pour les pages ayant comme paramètre un identifiant de proposition). Redirige vers */home*.
6. *assessor* : bloque l'accès aux pages aux utilisateurs n'ayant pas un de gestion dans la communauté **de la proposition** (**pour les pages ayant comme paramètre un identifiant de proposition**). Redirige vers */home*.
7. *moderator* : bloque l'accès aux pages aux utilisateurs n'ayant pas un role de modérateur (ou d'administrateur) dans la communauté (se base sur le paramètre de l'url). Redirige vers */home*.

## Fonctionnalités 

### Connaitre la page d'où l'utilisateur provient

Pour connaitre la page d'où l'utilisateur provient, une variable `from` est mise en place. Celle-ci est accessible partout depuis l'application (peut-être pas dans les middlewares...). 

**Composition de `from`** : 
- `name`: le nom de la page (tiré de l'arborescence de placement du fichier vue associé, ex : /communities/[id]/budget aura le nom 'communities-id-budget')
- `href`: l'url de provenance

**Attention** : cette fonctionnalité requiert le placement dans le hook `onBeforeUnmount` de la variable : 
````js
onBeforeUnmount(() => {
    const from = useState('from', () => {
        return {
            name: route.name,
            href: route.href,
        }
    }); 
    from.value = {
        name: route.name,
        href: route.href,
    }
})
````

**Exemple d'utilisation:**
````html
<template>
  <NuxtLink v-if="from?.name === 'communities-id-budget'" class="back" :to="from?.href">Retour au budget</NuxtLink>
  <NuxtLink v-else-if="community" class="back" :to="`/communities/${community['CMY_id_NB']}`">Retour au groupe</NuxtLink>
</template>

<script setup>
onBeforeUnmount(() => {
    const from = useState('from', () => {
        return {
            name: route.name,
            href: route.href,
        }
    }); 
    from.value = {
        name: route.name,
        href: route.href,
    }
})
</script>
````

*Note du 01/04/2025 : Cette fonctionnalité est en place uniquement dans l'application administrateur*


## Conseils suite aux bugs rencontrés

### Utilisation des graphiques Chart.JS

*Bug:* 
````
Uncaught RangeError: Maximum call stack size exceeded
````

Il semble que le comportement des graphiques Chart.JS soit instables avec des variables réactives, préferez l'utilisation de variables non-réactives pour la créations de graphiques. 

Le problèmes rencontrés est une boucle infinie (semble-t-il) lors de la mise à jour de grahiques avec la méthode `update()`. 

Exemple dans le fichier `budget.vue` de l'application admin.