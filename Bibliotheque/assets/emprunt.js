

document.addEventListener('DOMContentLoaded', function() {
   

    document.getElementById('showEffectiveDateBtn').addEventListener('click', function() {
       
        var effectiveDate = new Date(); 
        document.getElementById('effectiveDateContainer').innerHTML = 'Date effective : ' + effectiveDate.toDateString();
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const extensionButton = document.getElementById('extensionButton');
    if (extensionButton) {
        extensionButton.addEventListener('click', function() {
            const dateRestitutionEffectiveInput = document.getElementById('emprunt_livre_dateRestitutionEffective');
            if (dateRestitutionEffectiveInput) {
                const dateRestitutionEffective = new Date(dateRestitutionEffectiveInput.value);
                dateRestitutionEffective.setDate(dateRestitutionEffective.getDate() + 6);
                const formattedDate = formatDate(dateRestitutionEffective);
                dateRestitutionEffectiveInput.value = formattedDate;
            }
        });
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = ('0' + (date.getMonth() + 1)).slice(-2);
        const day = ('0' + date.getDate()).slice(-2);
        return year + '-' + month + '-' + day;
    }
});