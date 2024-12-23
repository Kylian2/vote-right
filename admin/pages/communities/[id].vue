<template>

    <Header></Header>

    <h1>Club Espace</h1>

    <main class="community">

        <section class="community__lateral">

            <div class="community__recap-budget">
                <h3>
                    Récapitulatif
                </h3>
                <p><b>Budget Total : </b> 79 000 € /an max</p>
                <p><b>Budget Utilisé : </b> 79 000 € /an</p>
                <button class="btn btn--small"> Modifier le budget maximal</button>
            </div>

            <div class="community__recap-themes">
                <h3>
                    Par thème
                </h3>
                <div class="community__recap-themes__list">
                    <p><b>Animation : </b> 79 000 € /an (max: 5000 €)</p>
                    <p><b>Aventure : </b> 15 000 € /an (max: 35000 €)</p>
                    <p><b>Sciences : </b> 6 700 € /an (max: 19000 €)</p>
                </div>
                <div>
                    <p><b>Frais fixes :</b> 4200 € /an</p>
                </div>
                <button class="btn btn--small"> Modifier les budgets</button>
            </div>

            <div class="community__actions">
                <button class="btn btn--small"> Ajouter un thème</button>
                <button class="btn btn--small"> Voir toutes les propositions</button>
                <button class="btn btn--small"> Modifier le groupe</button>
                <button class="btn btn--small"> Gérer les membres</button>
                <button class="btn btn--small"> Accéder aux outils de modérations</button>
            </div>

            <div class="community__legend">
                <div>
                    <img src="/images/like.png" alt="">
                    <p class="legend">: proposition appréciée</p>
                </div>
            </div>
        </section>
        <section></section>
    </main>

</template>