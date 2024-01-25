$(document).ready(function () {
    // Afficher la liste des smileys lorsque le bouton est cliqué
    $("#openSmileyList").click(function () {
        $(".smiley-buttons").toggle();
    });

    // Fonction pour ajouter le smiley correspondant à l'image cliquée dans la zone de commentaire
    $("button.smiley").click(function () {
        var smiley = $(this).data("smiley");
        var currentComment = $('.smiley-input').val();

        // Ajouter le smiley sélectionné à la zone de commentaire
        var updatedComment = currentComment + ' ' + smiley + ' ';

        // Mise à jour de la zone de commentaire avec le nouveau contenu
        $('.smiley-input').val(updatedComment).focus();
    });
});
