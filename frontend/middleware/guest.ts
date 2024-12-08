export default defineNuxtRouteMiddleware(async () => {
    const config = useRuntimeConfig() //Pour utiliser les variables d'environnement, sera utile lorsque l'on mettra les routes de productions
    try {
        const headers = useRequestHeaders(['cookie']);
        const response = await $fetch(`${config.public.baseUrl}/auth/check`, { headers, credentials : 'include'});
        console.log("GUEST AUTH MIDDLEWARE");
        if (response === true) { 
            return navigateTo('/home');
        }
    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation();
    }
})