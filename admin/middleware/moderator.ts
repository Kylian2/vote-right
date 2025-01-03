export default defineNuxtRouteMiddleware(async (to, from) => {
    const config = useRuntimeConfig() //Pour utiliser les variables d'environnement, sera utile lorsque l'on mettra les routes de productions
    try {
        const response: {'MEM_role_NB': number, 'ROL_label_VC': String, } = await $fetch(`${config.public.baseUrl}/users/me/role/${to.params.id}`, { credentials : 'include'});

        const ADMIN = 1;
        const MODERATOR = 4;

        if(response['MEM_role_NB'] == ADMIN || response['MEM_role_NB'] == MODERATOR){
            return;
        }

        return navigateTo('/home');

    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation();
    }
})