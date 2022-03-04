$('.selectBtn input').change( function(event) {
    var tmppath = URL.createObjectURL(event.target.files[0]);
    $(".profile-selectImg img").fadeIn("fast").attr('src',URL.createObjectURL(event.target.files[0]));
   
});