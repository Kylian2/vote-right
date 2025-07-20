# Documentation du serveur 

## Se connecter au serveur 

Pour se connecter au serveur, il faut utiliser la clé privée qui lui est associée. 

```bash
ssh -i [chemin_vers_la_cle] [utilisateur]@[adresse_ip]
```

Si vous voyez l'erreur WARNING: UNPROTECTED PRIVATE KEY FILE, il faut appliquer des droits plus restrictifs au fichier : `chmod 400 [fichier]`. 

## Installation

- Mise à jour 

Pour commencer, il faut mettre les programmes du serveur à jour : 

``` bash
apt update
apt upgrade
```

- Installation de docker (avec apt-get) *avec l'utilisateur root*. 

Pour préparer le repertoire apt de Docker : 

```bash
# Add Docker's official GPG key:
apt-get update
apt-get install ca-certificates curl
install -m 0755 -d /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
chmod a+r /etc/apt/keyrings/docker.asc

# Add the repository to Apt sources:
echo \
  "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
  $(. /etc/os-release && echo "$VERSION_CODENAME") stable" | \
  sudo tee /etc/apt/sources.list.d/docker.list > /dev/null
apt-get update
```

Ensuite, pour installer la denière version de docker : 

```bash
apt-get install docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
```

Normalement l'installation est terminée, vous pouvez verifier en regardant la version de docker installée avec `docker -v`ou`docker --version`. 
Vous pouvez également lancer l'image `hello-world`, cela téléchargera une image, la lancera dans un conteneur qui affichera un message de confirmation. 
```bash
docker run hello-world
```

- Utiliser Docker Compose pour orchestrer les conteneurs

Créez un fichier `compose.yml` et remplisez le avec la configuration du serveur. N'oubliez pas les variables d'environnements. 

Il faut les images suivantes : 
- traefik : pour le routing.
- mariadb : la base de données.
- phpmyadmin : l'interface web pour gérer la base de données.
- ghcr.io/kylian2/vote-right:main-backend : la backend
- ghcr.io/kylian2/vote-right:main-frontend : le frontend
- ghcr.io/kylian2/vote-right:main-admin : l'application admin
- ghcr.io/kylian2/vote-right-algorithms:main-algorithms : l'application des algorithmes

*N'oubliez pas de vous connecter à GitHub pour récuperer les images = `echo YOUR_GITHUB_TOKEN | docker login ghcr.io -u YOUR_GITHUB_USERNAME --password-stdin`*