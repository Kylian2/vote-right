FROM node:21 AS build
#Etapes de constructions

WORKDIR /app

COPY . .

#RUN npm install @vuestic/nuxt

RUN npm install

RUN npm run build

FROM node:21
#Etapes de copy du build
WORKDIR /app

COPY --from=build /app .

EXPOSE 3000

CMD [ "npm", "run", "start" ]