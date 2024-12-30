export default defineNuxtRouteMiddleware(async (to, from) => {
    const config = useRuntimeConfig() //Pour utiliser les variables d'environnement, sera utile lorsque l'on mettra les routes de productions
    try {
        const communities: Array<{CMY_id_NB: string}> = await $fetch(`${config.public.baseUrl}/communities/managed`, 
            { credentials : 'include'}
        );

        if (Array.isArray(communities)) {
            if (!(communities.length > 0 && typeof communities[0] === 'object' && communities[0] !== null)) {
                return navigateTo('/home');
            }
        }else{
            return navigateTo('/home');
        }

        let managed: boolean = false;
        for (let i = 0; i < communities.length; i++){
            managed = communities[i]['CMY_id_NB'] == to.params.id;
            if(managed){
                return;
            }
        }

        return navigateTo('/home');

    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation();
    }
})