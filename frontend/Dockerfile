# Étape 1 : Construction de l'application
FROM node:21 AS build

# Définir le dossier de travail
WORKDIR /app

# Copier uniquement les fichiers nécessaires
COPY package*.json ./

# Installer les dépendances
RUN npm install

# Copier le reste des fichiers du projet
COPY . .

# Construire l'application Nuxt
RUN npm run build

# Étape 2 : Préparer l'image pour l'exécution
FROM node:21

# Définir le dossier de travail
WORKDIR /app

# Copier les fichiers nécessaires depuis l'étape précédente
COPY --from=build /app . 

# Exposer le port par défaut de Nuxt
EXPOSE 3000

# Démarrer l'application
CMD ["npm", "run", "start"]
