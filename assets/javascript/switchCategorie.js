import {sendVisibilityRequest} from './_requestVisibility';

const inputs = document.querySelectorAll('input[data-switch-categorie]');

console.error(inputs);

inputs.forEach((item) => {
    item.addEventListener('change', (e) => {
        const id = e.target.dataset.switchCategorie;

        sendVisibilityRequest(`/admin/categories/${id}/switch`, e.currentTarget.nextElementSibling)
    })
})