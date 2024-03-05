function removeCollectionForm(e) {
    e.currentTarget.closest('li').remove();
}

function addFormToCollection(e) {
    //on stocke element html (ul) en ciblant le button, puis son dataset, puis son nom de holder class
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);
  
    //on crée un nouvel element li qui sera implementer dans ul
    const item = document.createElement('li');
    item.classList.add('col-md-5');
  
    //injecter html dans li, lui injecte le dataprototype du ul en modifiant le nom
    item.innerHTML = collectionHolder
      .dataset
      .prototype
      .replace(
        /__name__/g,
        collectionHolder.dataset.index
      );
  
      //ajoute un bouton de suppression
    const btnRemove = document.createElement('button');
    btnRemove.setAttribute('type', 'button');
    btnRemove.classList.add('btn', 'btn-danger', 'text-light', 'btn-remove-collection');
    btnRemove.innerHTML = '<i class="bi bi-x-octagon-fill"></i>';

    item.prepend(btnRemove);

      //ajoute item li dans le code ul
    collectionHolder.appendChild(item);
  
    //ecoute event sur clic du bouton pour activer fct remove
    btnRemove.addEventListener('click', removeCollectionForm);

    //incremente l'index pour chaque li
    collectionHolder.dataset.index++;
  };

document
  .querySelectorAll('.add_item_link')
  .forEach(btn => {
      btn.addEventListener("click", addFormToCollection)
  });

  document.querySelectorAll('.btn-remove-collection')
  .forEach(btnRemove => {
    btnRemove.addEventListener('click', removeCollectionForm);
  })