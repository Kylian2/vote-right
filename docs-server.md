# Documentation du serveur 

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
Vous pouvez également lancé l'image `hello-world`, cela téléchargera une image, la lancera dans un conteneur qui affichera un message de confirmation. 
```bash
docker run hello-world
```

