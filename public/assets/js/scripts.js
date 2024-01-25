const smiley = {
    buttonSmiley: null,
    openSmiley: null,

    openListSmiley: function () {
        // Show the list of smileys when the button is clicked
        smiley.buttonSmiley = document.querySelector(".smiley-buttons");
        smiley.openSmiley = document.getElementById("openSmileyList");

        smiley.openSmiley.addEventListener("click", function () {
            // Use style.display to toggle visibility
            if (smiley.buttonSmiley.style.display === "none") {
                smiley.buttonSmiley.style.display = "";
            } else {
                smiley.buttonSmiley.style.display = "none";
            }
        });
    },

    addSmiley: function () {
        smiley.buttonSmiley.addEventListener("click", function (event) {
            // Check if the clicked element is a smiley button
            if (event.target.classList.contains("smiley")) {
                // Get the clicked button's smiley value
                const smiley = event.target.dataset.smiley;

                // Get the current comment from the input field
                const currentComment = document.querySelector('.smiley-input').value;

                // Add the selected smiley to the comment input
                const updatedComment = currentComment + ' ' + smiley + ' ';

                // Update the comment input with the new content
                document.querySelector('.smiley-input').value = updatedComment;
            }
        });
    },
};

// Call the functions to initialize
smiley.openListSmiley();
smiley.addSmiley();


//version jquery
//$(document).ready(function () {
    // Afficher la liste des smileys lorsque le bouton est cliqué
   // $("#openSmileyList").click(function () {
       // $(".smiley-buttons").toggle();
   // });

    // Fonction pour ajouter le smiley correspondant à l'image cliquée dans la zone de commentaire
   // $("button.smiley").click(function () {
      //  var smiley = $(this).data("smiley");
     //   var currentComment = $('.smiley-input').val();

        // Ajouter le smiley sélectionné à la zone de commentaire
     //   var updatedComment = currentComment + ' ' + smiley + ' ';

        // Mise à jour de la zone de commentaire avec le nouveau contenu
      //  $('.smiley-input').val(updatedComment).focus();
   // });
//});