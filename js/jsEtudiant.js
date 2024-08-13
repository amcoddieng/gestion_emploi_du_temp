t0ggle("#inscriptions","#Sinscriptions");
t0ggle("#edt","#Sedt");
t0ggle("#notifications","#Snotifications");
t0ggle("#profil","#Sprofil");
t0ggle("#demande","#Sdemande");
t0ggle("#logout","#SPresponsables"); 
$('#Form_demamde_ins').on('submit', function(event) {
    event.preventDefault(); 

    // demande de changement de classe
    let donnee = new FormData(this);
    donnee.append("demande_inscription_a_une_classe", "ok");

    // Envoyer les données avec AJAX
    $.ajax({
        type: 'POST',
        url: 'traitementEtudiant.php',
        data: donnee,
        processData: false, // Empêche jQuery de traiter les données
        contentType: false, // Empêche jQuery de définir l'en-tête Content-Type
        success: function(response) {
            // Traitez la réponse du serveur ici
            alert(response);
            console.log(response); // Affiche la réponse du serveur dans la console
        },
        error: function(xhr, status, error) {
            // Traitez les erreurs ici
            alert(xhr.responseText);
            console.log(xhr.responseText); // Affiche le message d'erreur dans la console
        }
    });
});
$('#inscriptions').click(function(){
    $.ajax({
        url: 'traitementEtudiant.php',
        method: 'GET',
        data: {
            les_classes_list: 'ok'
        },
        success: function(response) {
            $('#classe').html(response);
        }
    })
})


// emploie du temp de l'etudiant
$('#edt').click(function(){
    // alert('ddd');
    $.ajax({
        url: 'traitementEtudiant.php',
        method: 'GET',
        data: {
            tab_edt_etudiant: 'ok'
        },
        success: function(response) {
            $('#calendar').html(response);
        }
    })
})
// Regarder les notifications
$('#notifications').click(function(){
    // alert('ddd');
    $.ajax({
        url: 'traitementEtudiant.php',
        method: 'GET',
        data: {
            notification: 'ok'
        },
        success: function(response) {
            $('#notifs').html(response);
        }
    })
})
// Regarder les demandes en cours de traitement
$('#demande').click(function(){
    // alert('ddd');
    $.ajax({
        url: 'traitementEtudiant.php',
        method: 'GET',
        data: {
            demande_encour_traitement: 'ok'
        },
        success: function(response) {
            $('#Sdemande').html(response);
        }
    })
})
// deconnexion
$('#logout').click(function(){
    // alert('ddd');
    $.ajax({
        url: 'traitementEtudiant.php',
        method: 'GET',
        data: {
            deconnexion: 'ok'
        },
        success: function(response) {
            location.reload();
        }
    })
})








// modification du profil
document.getElementById('profile-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formElement = this;
    var formData = new FormData(formElement); // Serialize the form data correctly
    formData.append("mod_user_profil", "ok");

    $.ajax({
        type: 'POST',
        url: 'traitementEtudiant.php',
        data: formData,
        processData: false, // Prevent jQuery from automatically transforming the data into a query string
        contentType: false, // Prevent jQuery from setting the Content-Type header
        success: function(response) {
            // Optionally handle the response from the server
           alert(response);
            showProfileView(); // Return to profile view
            location.reload();
        },
        error: function() {
            // Optionally handle any errors
            alert('Erreur lors de la mise à jour du profil.');
        }
    });
});
function showEditForm() {
    document.getElementById('profile-view').style.display = 'none';
    document.getElementById('profile-edit').style.display = 'block';
}

function showProfileView() {
    document.getElementById('profile-view').style.display = 'block';
    document.getElementById('profile-edit').style.display = 'none';
}