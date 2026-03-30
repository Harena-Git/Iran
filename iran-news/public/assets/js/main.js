// Script JavaScript principal
console.log('Application Iran News chargée');

// Fonction pour formater les dates
function formatDate(dateStr) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateStr).toLocaleDateString('fr-FR', options);
}

// Confirmation de suppression
function confirmDelete(id) {
    return confirm('Êtes-vous sûr de vouloir supprimer cet élément ?');
}
