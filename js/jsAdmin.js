t0ggle("#LesEnseignants","#Senseignants");
t0ggle("#classes","#Sclasses");
t0ggle("#Presponsables","#SPresponsables");
t0ggle("#gestionnaires","#Sgestionnaires");
t0ggle("#etudiants","#Setudiants");
t0ggle("#deconnexion","#enseignants");
t0ggle("#btn-ajout-enseignant","#form-enseignant");
t0ggle("#btn-Liste-enseignant","#tab_Les_enseignant")
t0ggle("#btn_ajout_classe","#form_ajout_classe")
t0ggle("#btn_Listes_classes","#tab_Les_classes")
t0ggle("#btn_ajout_ges","#form_ajout_ges")
t0ggle("#btn_Listes_Gest","#tab-gest")   
t0ggle("#btn_Les_Edemandeur","#tab_Les_Edemandeur")
t0ggle("#btn_divListEtudiant","#divListEtudiant")
$('.close-modal').click(function(){
    $('#login-modal_modifier').css('display','none');
})
$('#LesEnseignants').click(function(){
    $('#Sclasses').css('display','none');
    $('#SPresponsables').css('display','none');
    $('#Sgestionnaires').css('display','none');
    $('#Setudiants').css('display','none');
})
$('#etudiants').click(function(){
    $('#Sclasses').css('display','none');
    $('#SPresponsables').css('display','none');
    $('#Sgestionnaires').css('display','none');
    $('#Senseignants').css('display','none');
})
$('#classes').click(function(){
    $('#Senseignants').css('display','none');
    $('#SPresponsables').css('display','none');
    $('#Sgestionnaires').css('display','none');
    $('#Setudiants').css('display','none');
})
$('#Presponsables').click(function(){
    $('#Senseignants').css('display','none');
    $('#Sclasses').css('display','none');
    $('#Sgestionnaires').css('display','none');
    $('#Setudiants').css('display','none');
})
$('#gestionnaires').click(function(){
    $('#Senseignants').css('display','none');
    $('#Sclasses').css('display','none');
    $('#SPresponsables').css('display','none');
    $('#Setudiants').css('display','none');
})
//JS POUR AFFIHER LES DETAILS D'UN PROF RESPONSABLE

var det_prof = document.querySelectorAll('.detail_responsable');
det_prof.forEach(spanDet => {
    $(spanDet).click(function() {
        let xhr = new XMLHttpRequest();
        xhr.open('GET', 'traitement.php?matricule_prof_responsable=' + spanDet.getAttribute('value'), true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                    $('#login-modal_modifier').css('display', 'flex');
                if (xhr.status == 200) {
                    let divvv = document.querySelector('.login-form');
                    divvv.innerHTML = xhr.responseText;
            }
        }
        
    }
        xhr.send()})}
);
// js pour ajouter ou modifier un prof responsable
$('#Presponsables').click(function() {
    $.ajax({
        url: 'traitement.php',
        method: 'GET',
        data: { ListClassProf: 'ok' },
        success: function(response) {
            $('#tab_classe_profResp').html(response);
            
            // Gestion de la modification du professeur responsable
            $('.btn_mod_prof').click(function() {
                var $parentRow = $(this).closest('tr');
                var $detailRespo = $parentRow.find('.detail_responsable');
                var idClasss = $(this).val();
                
                $.ajax({
                    url: 'traitement.php',
                    method: 'GET',
                    data: { modal_prof: 'ok' },
                    success: function(response) {
                        $('#modal_prof').css('display', 'flex');
                        $('#modal_prof').html(response);
                            // Gestion de la fermeture du modal
                        $('.close-modal').click(function() {
                            $('#modal_prof').css('display', 'none');
                        });
                        setupModalActions(idClasss, $detailRespo);
                    }
                });
            });

            // Gestion de la suppression du professeur responsable
            $('.btn_suprimer_prof').click(function() {
                var $btnsup = $(this);
                $.ajax({
                    url: 'traitement.php',
                    method: 'GET',
                    data: {
                        suprimer_prof_resp: $btnsup.val()
                    },
                    success: function(response) {
                        $btnsup.closest('tr').find('.detail_responsable').html(response);
                    }
                });
            });
        }
    });
});

// Fonction pour gérer les actions dans le modal
function setupModalActions(idClasss, $detailRespo) {
    $('.P_prof_resp').click(function() {
        var selectedProfId = $(this).attr('id');
        
        $.ajax({
            url: 'traitement.php',
            method: 'GET',
            data: {
                Confprof_resp: selectedProfId,
                idclasseChoisi: idClasss
            },
            success: function(response) {
                $detailRespo.html(response);
                $('#modal_prof').css('display', 'none');
            }
        });
    });


}



// Consulter une liste de classe
$('.ChoixClasse').change(function(){
let idF = this.value;
let xhr = new XMLHttpRequest();
xhr.open('GET','traitement.php?getEtudeFormation='+idF,true);
xhr.onreadystatechange = function(){
    if (xhr.readyState==4 && xhr.status==200) {
        let bb = document.getElementById('tab_Les_etudiantDemander');
        bb.innerHTML = xhr.responseText;
        // console.log(this.responseText);
}
}
xhr.send();

})

// accepter la demande d'un etudiant
$('.BtnOui').click(function(){
    let idEtudiant = this.value;
    let nouvVal = $(this).parent();
    let idClasse = $('.BtnOui').data('classe');
    let xhr = new XMLHttpRequest();
    xhr.open('GET','traitement.php?AcceptEtudiant='+idEtudiant+'&IDclassE='+idClasse,true);
    xhr.onreadystatechange = function(){
        if (xhr.readyState==4 && xhr.status==200) {
            nouvVal.text('ajoute')
   }
    }
    xhr.send();
})
// refuser la demande d'un etudiant
$('.BtnNon').click(function(){
    let idEtudiant = this.value;
    let nouvVal = $(this).parent();
    let idClasse = $('.BtnNon').data('classe');
    let xhr = new XMLHttpRequest();
    xhr.open('GET','traitement.php?refuserEtudiant='+idEtudiant+'&IDclassE='+idClasse,true);
    xhr.onreadystatechange = function(){
        if (xhr.readyState==4 && xhr.status==200) {
            nouvVal.text('refuse')
   }
    }
    xhr.send();
})

// ajoute un enseignant`
document.getElementById('form-enseignant').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche le rafraîchissement de la page

    var donnee = new FormData(this);
    donnee.append('soumettre-prof','ok');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'traitement.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            document.getElementById('result').innerHTML = xhr.responseText;
            document.getElementById('form-enseignant').reset(); // Réinitialise les champs du 
        }
    };
    xhr.send(donnee);
});

// liste des enseignants
$('#btn-Liste-enseignant').click(function() {
    $.ajax({
        url: 'traitement.php',
        method: 'GET',
        data: {
            Liste_des_prof: 'ok'
        },
        success: function(response) {
            $('#enseignantsTableBody').empty();
            $('#enseignantsTableBody').html(response);
            $('.supProf').click(function(){
                let val = $(this).val();
                // alert(val)
                $.ajax({
                    url: 'traitement.php',
                    method: 'POST',
                    data: {
                        supProfs: 'ok',
                        val:val
                    },
                    success: function(response1) {
                        alert(response1);
                    }})
            })
        },
        error: function() {
            alert('Une erreur est survenue lors du chargement des données.');
        }
    });
});
// liste des classe
$('#btn_Listes_classes').click(function() {
    $.ajax({
        url: 'traitement.php',
        method: 'GET',
        data: {
            Liste_des_classs: 'ok'
        },
        success: function(response) {
            // $('#tbody_Listes_classes').empty();
             
            $('#tbody_Listes_classes').html(response);
            $('.supCl').click(function(){
                let id = $(this).data('id');
                // alert($(this).data('id'))
                $.ajax({
                    url: 'traitement.php',
                    method: 'POST',
                    data: {
                        supClasse: 'ok',
                        id:id
                    },
                    success: function(response) {
                        alert(response)
                    }})
            })
        },
        error: function() {
            alert('Une erreur est survenue lors du chargement des données.');
        }
    });
});
// ajouter classe
document.getElementById('form_ajout_classe').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche le rafraîchissement de la page

    var formData = new FormData(this);
    formData.append('ajoutclasse','ok');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'traitement.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert(xhr.responseText);
            document.getElementById('form_ajout_classe').reset(); // Réinitialise les champs du 
        }
    };
    xhr.send(formData);
});
// ajouter gestionnaire
document.getElementById('form_ajout_ges').addEventListener('submit', function(event) {
    event.preventDefault(); // Empêche le rafraîchissement de la page

    var formData = new FormData(this);
    formData.append('soumettre-gest','ok');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'traitement.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert('gestionnaire ajoute avec succès!');
            document.getElementById('form_ajout_ges').reset(); // Réinitialise les champs du 
        }
    };
    xhr.send(formData);
});
// liste des getionnaire
$('#btn_Listes_Gest').click(function() {
    // alert('dddd');
    $.ajax({
        url: 'traitement.php',
        method: 'GET',
        data: {
            Liste_des_gest: 'ok'
        },
        success: function(response) {
            $('#tbody_Listes_gest').html(response);
            $('.supGest').click(function(){
                // alert('lll')
                id = $(this).data('id');
                $.ajax({
                    url: 'traitement.php',
                    method: 'POST',
                    data: {
                        supGest: 'ok',
                        id:id
                    },
                    success: function(response) {
                        alert(response)
                    }})
            })
        },
        error: function() {
            alert('Une erreur est survenue lors du chargement des données.');
        }
    });
});
