$( ".kapat" ).click(function() {
  var durum = $(this).text(),chat=$("#chat"),yaz= $(this);
  if(durum=="-"){
  yaz.html("+");
  chat.animate({
    opacity: 1,
    bottom: "-490px"
  }, 1000);
    
  }else{
   yaz.html("-");
   chat.animate({
    opacity: 1,
    bottom: "0px"
  }, 1000);
  
  }

}); 
 

$( "#txt" ).bind( "click", function() {
  $(".feedback-chat").html("Maurício Santos está digitando");
});

  
$( ".btn-form-chat" ).click(function() {
    $( ".chat" ).show();
    $( ".form-chat").hide();
});