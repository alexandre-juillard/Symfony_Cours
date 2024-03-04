import {sendVisibilityRequest} from './_requestVisibility';

//on recupere les elements input du enable

const inputs = document.querySelectorAll('input[data-switch-categorie]');

// console.error(inputs);
//pour chaque input de la page
inputs.forEach((item) => {
    //ecouteur d'event quand ca change (sur le toggle)
    item.addEventListener('change', (e) => {
        //on recupere id du dataset dans l'attribut html
        const id = e.target.dataset.switchCategorie;

        //on execute la fonction en récuperant url + element suivant (label)
        sendVisibilityRequest(`/admin/categories/${id}/switch`, e.currentTarget.nextElementSibling)
    })
})