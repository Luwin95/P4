$(function(){
    //Paramétrage du plugin bootstrap Switch
    var options = {
        onText: "Sombre",
        offText: "Clair",
        onColor: 'darkgray',
        offColor: 'default'
    };

    $('#toggle-switch').bootstrapSwitch(options);

    //Gestion du changement de la couleur du fond au click sur le bouton
    $('#toggle-switch').on('switchChange.bootstrapSwitch', function (event, state) {
        if(!state)
        {
            $('#page').css({'background-color':'#222222', 'color' : '#9B9D9D'});
            $('.panel-heading').css({'background-color':'black', 'color' : '#9B9D9D', 'border-color': '#9B9D9D'});
            $('.panel-body').css({'background-color':'#222222', 'color' : '#9B9D9D', 'border-color': '#9B9D9D'});
            $('.panel-footer').css({'background-color':'black', 'color' : '#9B9D9D', 'border-color': '#9B9D9D'});
            $('.chapterTitle').css('color' ,'#9B9D9D');
            $('a.chapterTitle').focus(function()
            {
                $('this').attr('style', 'color: black !important');
            });
            $('a.chapterTitle').hover(function()
            {
                $('this').attr('style', 'color: black !important');
            });
            //A corriger bug couleur lien onfocus et hover
        }
        else{
            $('#page').removeAttr('style');
            $('.panel-heading').removeAttr('style');
            $('.panel-body').removeAttr('style');
            $('.panel-footer').removeAttr('style');
            $('.chapterTitle').removeAttr('style');
        }
    });
});



