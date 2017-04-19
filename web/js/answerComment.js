$(function(){
        $('#submit').click(function()
        {
            $('#comment-content').val('');
        });
        $('.repondre').click(function()
        {
            $('.repondre').hide();
            $(this).parent().append('<div id="reponse"></div>');
            var formDOM = $('#commentForm').html();
            $('#reponse').append(formDOM);
            $('#reponse form').append('<input type="hidden" name="parent"/>');
            $("input[name='parent']").val($(this).attr('id'));
            $('#reponse').append('<button type="button" class="btn btn-danger" id="annulerReponse">Annuler</button>');
            $('#annulerReponse').click(function()
            {
                $('#reponse').remove();
                $('.repondre').show();
            });
            
        });
});
