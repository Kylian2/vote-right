export default defineNuxtRouteMiddleware(async (to, from) => {
    if (!to.params.id) {
        console.warn("Route parameter 'id' is missing");
        return navigateTo('/home');
    }

    const config = useRuntimeConfig();

    try {
        const headers = import.meta.server ? useRequestHeaders(['cookie']) : {};
        const response = await $fetch(`${config.public.baseUrl}/proposals/${to.params.id}/membership`, {
            headers,
            credentials: 'include',
        });

        if (response !== true) { 
            return navigateTo('/home');
        }
    } catch (error) {
        console.error("Error during authorization check:", error);
        return abortNavigation(); // Ou rediriger vers une page d'erreur
    }
});
