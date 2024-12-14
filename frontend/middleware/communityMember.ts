export default defineNuxtRouteMiddleware(async (to, from) => {
    const config = useRuntimeConfig(); // Récupération des variables d'environnement
    const isServer = process.server;

    // Vérification que le paramètre 'id' est défini dans la route
    if (!to.params.id) {
        console.warn("Le paramètre 'id' est manquant.");
        return abortNavigation();
    }

    try {
        // Récupération des en-têtes HTTP (incluant les cookies)
        const headers = isServer ? useRequestHeaders(['cookie']) : undefined;
        console.log(headers);

        // Construction de la requête API
        const response = await $fetch(`${config.public.baseUrl}/communities/${to.params.id}/membership`, {
            headers,
            credentials: 'include', // Inclut les cookies si disponibles
        });

        // Vérification de la réponse
        if (response !== true) { // Remplacez par la clé correcte de votre API
            console.warn("Accès refusé : l'utilisateur n'est pas membre.");
            return abortNavigation();
        }
    } catch (error) {
        console.error("Erreur lors de la vérification de l'appartenance :", error);
        return abortNavigation();
    }
});
