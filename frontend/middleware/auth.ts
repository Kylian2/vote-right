export default defineNuxtRouteMiddleware(async (to, from) => {
    const config = useRuntimeConfig() //Pour utiliser les variables d'environnement, sera utile lorsque l'on mettra les routes de productions
    if(import.meta.server && to.fullPath === from.fullPath){
        return
    }
    try {
        const headers = useRequestHeaders(['cookie']);
        const response = await $fetch(`${config.public.baseUrl}/auth/check`, { headers, credentials : 'include'});
        console.log("FROM AUTH MIDDLEWARE --- ");
        console.log(to.fullPath);
        console.log(from.fullPath);
        console.log(import.meta.client ? "client" : "serveur");
        console.log(response);
        console.log (" ------- ");
        if (response !== true) { 
            return navigateTo('/login');
        }
    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation();
    }
})