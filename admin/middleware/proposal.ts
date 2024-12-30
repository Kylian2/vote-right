export default defineNuxtRouteMiddleware(async (to, from) => {
    const config = useRuntimeConfig() //Pour utiliser les variables d'environnement, sera utile lorsque l'on mettra les routes de productions
    try {
        const manage: boolean = await $fetch(`${config.public.baseUrl}/proposals/${to.params.id}/managed`, 
            { credentials : 'include'}
        );

        if(manage == true){
            return;
        }

        return navigateTo('/home');

    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation();
    }
})