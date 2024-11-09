export default defineNuxtRouteMiddleware(async () => {
    //const config = useRuntimeConfig() //Pour utiliser les variables d'environnement, sera utile lorsque l'on mettra les routes de productions
    try {
        const headers = useRequestHeaders(['cookie'])
        console.log(headers)
        const response = await $fetch(`http://localhost:3333/auth/check`, { headers, credentials : 'include'});
        console.log(response);
        if (response === true) { 
            return navigateTo('/home');
        }
    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation();
    }
})