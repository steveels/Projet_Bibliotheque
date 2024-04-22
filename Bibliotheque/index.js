
document.getElementById('entrervotredatederendu').addEventListener('click', function() {
    // Effectuer une action pour afficher la date effective
    // Par exemple, vous pouvez ajouter un élément HTML pour afficher la date effective dans la page
    var effectiveDate = new Date(); // Remplacez cela par la date effective réelle
    document.getElementById('effectiveDateContainer').innerHTML = 'Date effective : ' + effectiveDate.toDateString();
});
