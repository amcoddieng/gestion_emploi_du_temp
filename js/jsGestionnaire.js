t0ggle("#ajout_salle","#Sajout_salle");
t0ggle("#ajoutSalle","#formSalle");
t0ggle("#attribuer_salle","#Sattribuer_salle");
t0ggle("#edt","#Sedt");
// t0ggle("#logout","#Sprofil");

// ajouter une salle
$('#formSalle').on('submit', function(event) {
    
    event.preventDefault(); // Empêche le formulaire de se soumettre de manière traditionnelle
    var formElement = this;

    var formData = new FormData(formElement); 

    formData.append("ajoutSalle", "ok");
    $.ajax({
        url: 'traitementGest.php', 
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        success: function(response) {
            $('.divListeSalle').css('display','none');
            $('#result').html('<div class="alert alert-success">'+response+'</div>');
            $('#formSalle')[0].reset();
        },
        error: function(xhr, status, error) {
            $('#result').html('<div class="alert alert-danger">Une erreur est survenue. Veuillez réessayer.</div>');
        }
    });
});
$('#listeSalle').click(function(){
    $.ajax({
        url: 'traitementGest.php', 
        type: 'POST',
        data: {listesalle:'ok'},
        success: function(response) {
            $('#formSalle').css('display','none');
            $('.divListeSalle').html(response);
            $('.divListeSalle').css('display','flex');
            $('.delete-btn').click(function(){
                let id = $(this).data('id')
                var parent =$(this).closest('td')
                // alert(id)
                $.ajax({
                    url: 'traitementGest.php', 
                    type: 'POST',
                    data: {supsalle:id},
                    success: function(response) {
                     alert(response)
                     parent.text('supprimé')
                    parent.css('background-color','red')
                    }})
            })

        }
    })
})
// attribuer les salles
$('#attribuer_salle').click(function(){
    $.ajax({
        url: 'traitementGest.php', 
        type: 'POST',
        data: {edt_classes:'ok'},
        success: function(response) {
            $('#edt_des_classe').html(response);
            // modifier les salle a attribuer
            $('.jourMod').click(function(){
                let nom = $(this).find('.salleMoment');
                let classe = $(this).data('classe');
                let jour = $(this).data('jour');
                let heure = $(this).closest('tr').data('heure');
                // console.log(nom);
                $.ajax({
                    url: 'traitementGest.php', 
                    type: 'POST',
                    data: {SalleDispo:'ok',
                        classe:classe,
                        jour:jour,
                        heure:heure
                    },
                    success: function(response) {
                        // console.log(response);
                        $('.modalSalle').html(response);
                        $('.closemodal').click(function(){
                            $('.modal-content').css('display','none');
                        });
                        // selection de la salle 
                        $('.liSalle').click(function(){
                            let salle = $(this).data('salle');
                            let nomsalle = $(this).text();
                            $.ajax({
                                url: 'traitementGest.php', 
                                type: 'POST',
                                data: {attribuerSalle:'ok',
                                    classe:classe,
                                    jour:jour,
                                    heure:heure,
                                    salle:salle,
                                    nom:nomsalle
                                },
                                success: function(response1) {
                                nom.text(response1);
                                $('.modal-content').css('display','none');
                            }})
                        })

                    }
                })
            })

        }
    })
})
$('#edt').click(function(){
    $.ajax({
        url: 'traitementGest.php', 
        type: 'POST',
        data: {edt_des_salles:'ok',
            
        },
        success: function(response1) {
            $('#edt_des_salles').html(response1);
            $('.vidersalle').click(function(){
                var parent =$(this).closest('td')
                let cl =$(this).data('classe')
                let h =$(this).data('horaire')
                let j =$(this).data('jour')
                // alert(cl +''+h+''+j)
                $.ajax({
                    url: 'traitementGest.php', 
                    type: 'POST',
                    data: {viderSalle:'ok',
                        cl:cl,
                        j:j,
                        h:h
                        
                    },
                    success: function(response) {
                    alert(response);
                    parent.text('supprimé')
                    parent.css('background-color','red')
                    }})

            })
        }
    })
})