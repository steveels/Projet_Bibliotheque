

document.addEventListener('DOMContentLoaded', function() {
   

    document.getElementById('showEffectiveDateBtn').addEventListener('click', function() {
       
        var effectiveDate = new Date(); 
        document.getElementById('effectiveDateContainer').innerHTML = 'Date effective : ' + effectiveDate.toDateString();
    });
});