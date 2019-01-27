<!-- Form change variable must be global -->
var chgFlag = 0;

$(document).ready(function() {
  $('.updb').prop('disabled', true);
  $("#Xmsg").fadeOut(2000);
  $("#help").hide();
  $('.updb').prop('disabled', true);

$("#helpbtn").click(function() {
  $("#help").toggle();
  });

$("#reset").click(function() {
  chgFlag = 0;
  $(".updb").css({"background-color": "grey", "color":"black"});
  $('.updb').prop('disabled', true);    
  });  

// add of bootstrap modal for error messages
$('body').append('<div class="hidden-print modal fade" id="msgdialog"> \
  <div class="modal-dialog"> \
    <div class="modal-content"> \
      <div class="modal-header"> \
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> \
        <h4 id="msgdialogtitle" class="modal-title"></div> \
      <div id="msgdialogcontent" class="modal-body"></div> \
      <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div> \
    </div> \
  </div> \
 </div>');
 
// does case insensitive search in 'filterbtn1'
$.extend($.expr[":"], {
  "containsNC": function(elem, i, match, array) {
  return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
  }
  });

$("#filterbtn2").click(function() {
  $("#filter").val("");
  $("#filter").focus();
  $('tr').show();
  chgFlag = 0;
  });
  
$("#filter").keyup(function() {
  var filter = $("#filter").val();
  if (filter.length) {
    // alert("filter button clicked:" + filter);
    $('tr').hide().filter(':containsNC('+filter+')').show();
    $("#head").show();
    chgFlag = 0;
    return;
    }
  $('tr').show();
  chgFlag = 0;
  });

});

