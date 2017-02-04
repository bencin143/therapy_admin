$('#clicko').click(function() {

 $.ajax({
  type: "POST",
  url: "share.php",
  data: {"file_id":file_id,"article_id":article_id},
}).done(function( msg ) {
  alert( "Data Saved: " + msg );
});    

    });