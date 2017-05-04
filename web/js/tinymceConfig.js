$(function(){
    //Configuration du système mce tinymce
    tinymce.init({
        selector:'#chapter_content',
        menubar:false,
        height:'400',
        entity_encoding : "raw",
        encoding: "UTF-8",
        setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });

    } });
});
    
