t0ggle("#disponibilites","#Sdisponibilites");
t0ggle("#edt","#Sedt"); 
t0ggle("#notification","#Snotification"); 
t0ggle("#cOurs","#Suivicours");
t0ggle("#profil","#Sprofil");
t0ggle("#Gclasse","#SGclasse");
t0ggle("#div_emp_class_gerer",".edtClasseGerer");
t0ggle("#logout","#SPresponsables");
t0ggle("#ajout_cours","#form_ajout_classe");
t0ggle("#btn_liste_cours",".listeDesCours");
$('#profil').click(function(){
    $('#Sdisponibilites').css('display','none');
    $('#Suivicours').css('display','none');
    $('#Sedt').css('display','none');
    $('#Sedt').css('display','none');
    $('#Snotification').css('display','none');
})
$('#notification').click(function(){
    $('#Sdisponibilites').css('display','none');
    $('#Suivicours').css('display','none');
    $('#Sedt').css('display','none');
    $('#Sedt').css('display','none');
    $('#Sprofil').css('display','none');
})
$('#Gclasse').click(function(){
    $('#Sdisponibilites').css('display','none');
    $('#Suivicours').css('display','none');
    $('#Sedt').css('display','none');
    $('#Snotification').css('display','none');
    $('#Sprofil').css('display','none');
})
$('#edt').click(function(){
    $('#Sdisponibilites').css('display','none');
    $('#Suivicours').css('display','none');
    $('#SGclasse').css('display','none');
    $('#Snotification').css('display','none');
    $('#Sprofil').css('display','none');
})
$('#disponibilites').click(function(){
    $('#Sedt').css('display','none');
    $('#Suivicours').css('display','none');
    $('#SGclasse').css('display','none');
    $('#Snotification').css('display','none');
    $('#Sprofil').css('display','none');
})
$('#cOurs').click(function(){
    $('#Sedt').css('display','none');
    $('#Sdisponibilites').css('display','none');
    $('#SGclasse').css('display','none');
    $('#Snotification').css('display','none');
    $('#Sprofil').css('display','none');
})
$('#btn_liste_cours').click(function(){
    $('#form_ajout_classe').css('display','none');
    $('.edtClasseGerer').css('display','none');
})
$('#div_emp_class_gerer').click(function(){
    $('#form_ajout_classe').css('display','none');
    $('.listeDesCours').css('display','none');
})
$('#ajout_cours').click(function(){
    $('.edtClasseGerer').css('display','none');
    $('.listeDesCours').css('display','none');
})
//regler la disponibilite
$('#disponibilites').click(function(){
    // alert('ddd');
    $.ajax({
        url: 'traitement.php',
        method: 'GET',
        data: {
            tab_dispo_enseignant: 'ok'
        },
        success: function(response) {
            $('#tbody_disponibilite').html(response);
            $('.rrrr').click(function(){
                print($('#tbody_disponibilite').html());
             })
            // chnager de disponobilite
            $(document).ready(function() {
                $('.btn-dispo').click(function() {

                    var $btn = $(this);
                    var jour = $btn.data('jour');
                    var heure = $btn.data('heure');
                    var etat = $btn.data('etat');
                    
                    // Alterne l'état entre 'dispo' et 'occupé'
                    etat = (etat === 'dispo') ? 'occupé' : 'dispo';
            
                    // alert(etat);
            
                    var formData = new FormData();
                    formData.append("mod_etat", "ok");
                    formData.append("etat", etat);
                    formData.append("heure", heure);
                    formData.append('jour', jour);
            
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'traitement.php', true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            // Met à jour le texte de l'état après la réponse du serveur
                            $btn.closest('td').find('#etat').text(etat);
                            // Met à jour l'état dans les données du bouton pour les prochains clics
                            $btn.data('etat', etat);
                            $btn.closest('td').css('background-color','yellow')
                           
                        }
                    };
                    xhr.send(formData);
                });
            });
            
        },
        error: function() {
            alert('Une erreur est survenue lors du chargement des données.');
        }
    });
})

// emploie du temp du prf
$(document).ready(function() {
    $('#edt').click(function() {
        $.ajax({
            url: 'traitement.php',
            method: 'GET',
            data: { tab_edt_prof: 'ok' },
            success: function(response) {
                $('#calendar').html(response);

                // Attachez l'événement click aux nouveaux éléments après la réponse AJAX
                $('.confirmerCour').off('click').on('click', function() {
                    var jours = $(this);
                    var jour = jours.data('jour');
                    var heures = jours.closest('tr');
                    var heure = heures.data('heure');

                    $.ajax({
                        url: 'traitement.php',
                        method: 'POST',
                        data: { MODconfirmerCours: 'ok' },
                        success: function(response) {
                            $('.modalcours').html(response).css('display', 'flex');

                            // Attachez les événements click aux boutons dans la modal
                            $('.sup').off('click').on('click', function() {
                                $('.modalcours').css('display', 'none');
                            });

                            $('.val').off('click').on('click', function() {
                                $.ajax({
                                    url: 'traitement.php',
                                    method: 'POST',
                                    data: {
                                        confirmerCours: 'ok',
                                        jour: jour,
                                        heure: heure
                                    },
                                    success: function(response) {
                                        alert(response);
                                        jours.css('background-color','red')
                                        $('.modalcours').css('display', 'none');
                                    }
                                });
                            });
                        }
                    });
                });
            }
        });
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
// modification du profil
document.getElementById('profile-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var formElement = this;
    var formData = new FormData(formElement); // Serialize the form data correctly
    formData.append("mod_user_profil", "ok");

    $.ajax({
        type: 'POST',
        url: 'traitement.php',
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
// deconnexion
$('#logout').click(function(){
    // alert('ddd');
    $.ajax({
        url: 'traitement.php',
        method: 'GET',
        data: {
            deconnexion: 'ok'
        },
        success: function(response) {
            location.reload();
        }
    })
})


// emploie du temp de la classe
$('#div_emp_class_gerer').click(function() {
    $.ajax({
        url: 'traitementProfResp.php',
        method: 'GET',
        data: { tab_edt_classe: 'ok' },
        success: function(response) {
            $('.edtClasseGerer').html(response);
            
            // Gestion de l'emploi du temps d'une classe
            $('.jourMod').click(function() {
                let jours = $(this);
                let jour = jours.data('jour');
                let heures = jours.closest('tr');
                let heure = heures.data('heure');
                
                $.ajax({
                    url: 'traitementProfResp.php',
                    method: 'GET',
                    data: {
                        modal_modifie_cours: 'ok',
                        jour: jour,
                        heure: heure
                    },
                    success: function(response) {
                        $('#modal_modifie_cours').html(response);
                        $('#modal_modifie_cours').css('display', 'flex');
                        
                        $('.close-modal').click(function() {
                            $('#modal_modifie_cours').css('display', 'none');
                        });
                        
                        $('#creerCours').click(function() {
                            let cours = $('.choixCours').val();
                            let prof = $('.choisirprof').val();
                            
                            $.ajax({
                                url: 'traitementProfResp.php',
                                method: 'GET',
                                data: {
                                    changer_le_planing: 'ok',
                                    cours: cours,
                                    prof: prof,
                                    jour: jour,
                                    heure: heure
                                },
                                success: function(response) {
                                    console.log(response);
                                    $('#modal_modifie_cours').css('display', 'none');
                                    $('.edtClasseGerer').css('display','none');
                                }
                            });
                        });
                        $('#suppCours').click(function() {
                            let cours = $('.choixCours').val();
                            let prof = $('.choisirprof').val();
                            
                            $.ajax({
                                url: 'traitementProfResp.php',
                                method: 'GET',
                                data: {
                                    supprimer_de_edt: 'ok',
                                    cours: cours,
                                    prof: prof,
                                    jour: jour,
                                    heure: heure
                                },
                                success: function(response) {
                                    console.log(response);
                                    $('#modal_modifie_cours').css('display', 'none');
                                    $('.edtClasseGerer').css('display','none');
                                }
                            });
                        });
                    }
                });
            });
        }
    });
});

// ajouter une classe
document.getElementById('form_ajout_classe').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche le rafraîchissement de la page

    var formData = new FormData(this);
    formData.append('soumettre-classe','ok');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'traitementProfResp.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // alert('classe ajoute avec succès!');
            console.log(xhr.responseText);
            document.getElementById('form_ajout_classe').reset(); // Réinitialise les champs du 
        }
    };
    xhr.send(formData);
});
// liste des cours
$('#btn_liste_cours').click(function(){
    $.ajax({
        url: 'traitementProfResp.php',
        method: 'GET',
        data: {
            liste_des_cours: 'ok'
        },
        success: function(response) {
            $('.listeDesCours').html(response);
            // $('.listeDesCours').css('display','flex');
            $('.btn_suprimer_ocurs').click(function(){
                let id = $(this).val();
                // alert (id)
                $.ajax({
                    url: 'traitementProfResp.php',
                    method: 'GET',
                    data: {
                        supCLm: id
                    },
                    success: function(response) {
                        alert(response)
                        $('.listeDesCours').css('display','none');
                    }})
            })
        }})
})
// notification
$('#notification').click(function(){
    $.ajax({
        url: 'traitement.php',
        method: 'GET',
        data: {
            notification: 'ok'
        },
        success: function(response) {
            
            $('#Snotification').html(response);
        }})
})
$('#cOurs').click(function(){
    $.ajax({
        url: 'traitement.php',
        method: 'POST',
        data: {
            afficheSuiviCours: 'ok'
        },
        success: function(response) {
            
            $('#Suivicours').html(response);
        }})
})
