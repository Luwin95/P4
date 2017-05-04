$(function(){
        //Gestion de l'ajout du formulaire lors de la création d'un commentaire enfant
        $('#submit').click(function()
        {
            $('#comment-content').val('');
        });
        $('.repondre').click(function()
        {
            //Dissimulation des boutons répondre du document au clic sur l'un d'entre eux
            $('.repondre').hide();
            //Ajout du formulaire
            $(this).parent().append('<div id="reponse"></div>');
            var formDOM = $('#commentForm').html();
            $('#reponse').append(formDOM);
            $('#reponse form').append('<input type="hidden" name="parent"/>');
            $("input[name='parent']").val($(this).attr('id'));
            $('#reponse').append('<button type="button" class="btn btn-danger" id="annulerReponse">Annuler</button>');
            //On supprime le formulaire de réponse au clic sur un bouton annuler et on affiche les boutons répondre
            $('#annulerReponse').click(function()
            {
                $('#reponse').remove();
                $('.repondre').show();
            });
        });
});
