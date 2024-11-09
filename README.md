# VoteRight

## Membres de l'équipe

- **Kylian Richard** as Kylian2
- **Rodriguez Esteban** as Laren21
- **Guiborat--Bost** Mathieu as mguiborat

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

## Utilisation de GitHub

### 1. Récuperer le travail

Pour récuperer pour la première fois le travail, il faut cloner le dépot sur sa machine. 
```
git clone https://github.com/Kylian2/jo-application.git
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
