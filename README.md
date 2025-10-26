# VoteRight

## Membres de l'équipe

- **Kylian Richard** as Kylian2
- **Esteban Rodrigues** as Esteban141
- **Mathieu Guiborat--Bost**  as mguiborat

## Installer l'environnement de développement

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les outils suivants :

- **Docker** : pour gérer les conteneurs.
- **Git** : pour cloner les dépôts de projet.
- **Node.js** et **npm** : pour le développement des frontends NuxtJs.
- **PHP** : pour le développement du backend.
- **composer** : pour la gestion des dépendances php.

## Structure du Projet

Le projet se compose de plusieurs services définis dans un fichier `docker-compose.yml` :

- **Base de données MariaDB** : utilisée pour stocker les données du backend.
- **phpMyAdmin** : pour gérer la base de données via une interface web.
- **Backend PHP** : le serveur NGINX qui exécute le backend.
- **Frontend NUXTJS** : deux applications front-end distinctes (non incluses dans le fichier `docker-compose.yml`).

## Installation

### 1. Cloner le Dépôt Git

Commencez par cloner le dépôt contenant le code source du projet.

```bash
git clone https://github.com/Kylian2/vote-right
cd <nom_du_dossier>
```

### 2. Configuration du Fichier `compose.yml`

Lors du clone de l'application vous avez récupéré un fichier `compose.yml`. Ce fichier permet d'orchester la base de données et le service phpmyadmin associé ainsi que de faire tourner un serveur nginx local servant le backend.

```yaml
version: "3.1"

services:
  db:
    image: mariadb:10.3
    container_name: voterigth_db
    restart: always
    command: --default-authentication-plugin=mysql_native_password
    ports:
      - 3306:3306
    expose:
      - "3306"
    environment:
      MYSQL_ROOT_PASSWORD: root
    volumes:
      - dbdata:/var/lib/mysql

  phpmyadmin:
    image: phpmyadmin
    restart: always
    ports:
      - 8888:80
    environment:
      - PMA_ARBITRARY=1
  
  backend:
    image: nginx:1.22-alpine
    ports:
      - "3333:80"
    volumes: 
      - ./backend:/backend
      - ./backend/config/nginx.conf:/etc/nginx/nginx.conf

  php:
    build: 
      context: .
      dockerfile: Dockerfile
    volumes: 
      - ./backend:/backend

volumes:
  dbdata:
```

### 3. Configurer le Backend PHP

Le backend PHP est servi par NGINX. Assurez-vous que votre code PHP est bien dans le dossier `./backend`. Vous pouvez également configurer NGINX à l'aide du fichier `./backend/config/nginx.conf`.

Créez un fichier `.env` dans le dossier config contenant les variables suivantes  :

```ini
APP_NAME=Voteright
DB_HOST=db
DB_NAME=voterigth_db
DB_USER=root
DB_PASS=root
EMAIL_USER=user@email.fr
EMAIL_DOMAIN=mail.fr
EMAIL_API_KEY=yourapikey
IMAGE_URL=url-du-frontend
```

**Mailer** : nous utilisons *Mailgun* pour envoyer nos emails. Il vous faudra un compte pour remplir les variables d'environnements associées au mailing.

Ensuite installez les dépendances php à l'aide de composer (assurez vous d'être dans le dossier `./backend`). 

```
composer install
```

### 4. Installation des Frontends NUXTJS 3

Le projet est composé de deux frontend : 
 - `frontend`: le frontend de base destiné aux utilisateurs classiques de l'application
 - `admin`: le frontend de l'application d'administration

Pour les frontends, vous devez configurer les environnements de développement séparément.

- **Installation des dépendances** :
  Allez dans les répertoires des frontend et installez les dépendances Node.js via npm.

```bash
cd ./frontend
npm install
```

- **Lancer l'application frontend** :

```bash
npm run dev
```

Cela lancera le serveur de développement sur `http://localhost:3000` pour le premier frontend et sur un autre port pour le second, selon la configuration de votre projet.

Vous pour choisir les ports exposés par les frontends en ajoutant à la racine de chacunes des applications une variable d'environnement `PORT=XXXX` (dans un fichier `.env`).

### 5. Démarrer les Services Docker

Une fois le fichier `compose.yml` configuré et le fichier `.env` créé, vous pouvez démarrer l'ensemble des services Docker avec la commande suivante :

```bash
docker compose up -d
```

Cela démarrera tous les services en arrière-plan :

- La base de données `MariaDB`.
- Le service `phpmyadmin` pour accéder à la base de données via une interface web.
- Le serveur backend PHP avec nginx.
  
### 6. Accéder à phpMyAdmin

Une fois les services démarrés, vous pouvez accéder à **phpMyAdmin** en ouvrant un navigateur et en vous rendant à l'URL suivante :

```
http://localhost:8888
```

Utilisez les informations suivantes pour vous connecter à la base de données MariaDB :

- **Utilisateur** : `root`
- **Mot de passe** : `root` (comme défini dans les variables d'environnement).

### Arrêter l'Environnement

Pour arrêter l'environnement Docker, utilisez la commande suivante :

```bash
docker compose down
```

Cela arrêtera tous les services Docker et supprimera les conteneurs, mais conservera les volumes de données persistants.


## Conventions et règles 

### 1. Convention de commit 

Nous suivons la convention de commit suivante : [Convention](https://www.conventionalcommits.org/fr/v1.0.0/). 

Voici quelques **points clés** de cette convention : 

#### Structure du message de commit

Un message de commit se compose de trois parties :

1. En-tête (header)
2. Corps (body)
3. Pied de page (footer)

``` 
<type>(étendue optionnelle): <description>
[corps optionnel]
[pied optionnel]
```

#### En-tête (Header)

L'en-tête est obligatoire et doit être concis. Il se compose de :
- **Type** : Un mot décrivant la nature du commit
- **Sujet** : Une courte description de la modification (max. 50 caractères).

Voici quelques exemples de types : 

- **feat** : Une nouvelle fonctionnalité.
- **fix** : Une correction de bug.
- **docs** : Des modifications concernant la documentation.
- **style** : Des changements de style (formatage, points et virgules manquants, etc.) qui n'affectent pas le code.
- **refactor** : Une modification du code qui n'apporte ni nouvelle fonctionnalité ni - - correction de bug.
- **test** : Ajouter ou modifier des tests.
- **chore** : Des tâches de maintenance qui ne modifient pas le code source (mise à jour des outils de build, configuration, etc.).

Exemples : 

- `fix: type incorrect dans les attributs de la classe Equipe `
- `feat(langue): ajouter la langue polonaise `

#### Corps (Body)

Le corps est optionnel mais recommandé pour les commits complexes. Il fournit une description détaillée des modifications, raisons et contexte.

#### Pied de page (Footer)

Le pied de page est optionnel et est utilisé pour des informations supplémentaires comme les références aux tickets (issues) ou les notes spéciales.

Exemple de commit avec en-tête, corps et pieds de page : 
```
fix: corriger le bug d'affichage sur la page d'accueil

Ce correctif résout un problème où les images ne s'affichaient pas correctement sur la page d'accueil. La cause était une mauvaise URL d'image générée par la fonction de rendu

Reviewed-by: Zanzibar35
Refs: #123
```

### 2. Convention de nommage des branches 

La convention de nommage des branches reprend les éléments de la convention de commit, elle permet d'identifier clairement la tâche par son type et son identifiant.

#### Structure du nom de la branche

- **Type** : Un mot décrivant la raison d'être de la branche.
- **Identifiant de la tâche** : Un identifiant qui commence par **TSKVOTERIGHT** et suivi d'une suite de chiffres correspondant à l'identifiant de la tâche parmi l'ensemble des tâches réalisées.

Voici les différents types :

- **feat** : Une branche dédiée au développement d'une fonctionnalité.
- **fix** : Une branche dédiée à la résolution d'un bug.
- **chore** : Une branche dédiée à des modifications qui n'impactent pas le code.

Exemples : 

- `feat/TSKVOTERIGHT-123`
- `fix/TSKVOTERIGHT-123`  

## Utilisation de GitHub

### 1. Récuperer le travail

Pour récuperer pour la première fois le travail, il faut cloner le dépot sur sa machine. 
```
git clone https://github.com/Kylian2/vote-right
```
### 2. Créer sa branche de travail

Pour travailler de façon organiser et en évitant le plus possible les conflits, il faut créer une branche de travail. 

```
git checkout -b <nom_de_la_branche>
```

### 3. Travailler sur une branche 

La branche sera utilisé tout au long de l'implémentation de la fonctionnalité sur laquelle vous travaillez. 
Pendant ce temps, vous pouvez **faire des modifications** et les **ajouter**. 

Pour ajouter les modifications : 
```
git add .
git commit -m 'mon_message_qui_suit_la_convention'
```

### 4. Pousser les modifications
Après avoir fait le **commit**, vous pouvez partager à tout les membres vos modifications, pour cela il faut faire : 
```
git push origin <nom_de_la_branche>
```

Cette commande à exactement le même effet que :
```
git push
```
sauf que c'est plus clair pour git, puisqu'il sait sur quelle branche envoyer les modifications. 

Voilà ce qu'il se passe : 

- La branche **<nom_de_la_branche>** est créée sur GitHub si elle n'existait pas déjà.
- Les commits sont maintenant disponibles dans le dépôt distant sous **<nom_de_la_branche>**.
- Les autres membres de l'équipe peuvent voir et accéder à la branche en allant sur GitHub.

### 5. Créer une Pull Request 
Quand tout le travail à faire sur cette fonctionnalité a été fait, vous pouvez créer une Pull Request. 
La Pull Request permet d'amorcer le processus pour integrer les modifications de la branche dans la branche principale. 

Pour créer une Pull Request, il faut : 

- Acceder au débot GitHub. 
- Acceder à l'onglet *Pull Request*. Puis selectionner *New Pull Request*
- Choissiez la branche sur laquelle vous voulez effectuer une Pull Request. 
- Vous pourrez voir l'historique des commits et des modifications. 
- Cliquez sur *Create pull request*, décrivez vos modifications, et soumettez la PR.

### 6. Revoir et fusionner la Pull Request 

Les membres de l'équipes peuvent revoir le code, poser des questions, demander des modifications...

Ensuite quand tout le monde est d'accord, vous pouvez fusionner la Pull Request en cliquant sur *Merge pull request*. 

Si il y a des conflits resolvez les. 

Et puis tout est prêt !

Le mieux est ensuite de supprimer la branche de travail que l'on vient de fusionner. Pour cela il y a un bouton qui apparaitra une fois la Pull Request terminée. Sinon : 
```
git push origin --delete <nom_de_la_branche>
```

### 7. Mettre à jour la branche principale pour tout les membres. 

Après la fusion de la PR, chaque membre de l'équipe doit mettre à jour sa branche principale locale (la branche main sur sa machine) : 

```
git checkout main
git pull origin main
```

*Dans le cas où vous n'avez pas supprimé la branche de travail, il faudra la mettre à jour également en fusionnant avec la branche principale :*

```
git checkout <nom_de_la_branche>
git merge main
```

*Si des conflits surviennent, il faudra les resoudre manuellement. Dans les fichiers en conflits, il seront indiqués dans le code par les symboles  **<<<<<<, ======,** et **>>>>>>**.*

*Vous pourrez ensuite valider la correction des conflits en poussant :*

```
git add .
git commit -m 'Résolution des conflits'
```

### 8. Continuer le développement

Répétez ces étapes pour chaque nouvelle fonctionnalité ou correction de bug.
