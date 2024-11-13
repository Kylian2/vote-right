export default defineNuxtRouteMiddleware(async () => {
    //const config = useRuntimeConfig() //Pour utiliser les variables d'environnement, sera utile lorsque l'on mettra les routes de productions
    try {
        const headers = useRequestHeaders(['cookie']);
        const response = await $fetch(`http://localhost:3333/auth/check`, { headers, credentials : 'include'});
        if (response !== true) { 
            return navigateTo('/login');
        }
    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation();
    }
})