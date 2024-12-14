export default defineNuxtRouteMiddleware(async (to, from) => {
    const config = useRuntimeConfig() //Pour utiliser les variables d'environnement, sera utile lorsque l'on mettra les routes de productions

    try {
        const headers = useRequestHeaders(['cookie']);
        const response = await $fetch(`${config.public.baseUrl}/communities/${to.params.id}/membership`, { headers, credentials : 'include'});
        if (response !== true) { 
            return navigateTo('/home');
        }
    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation();
    }
})