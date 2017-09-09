
$(document).ready(function(){

/*DONT CLOSE WINDOW ON FORM CLICK*/
$(".window form").click(function(e) {
  e.stopPropagation();
});

/*CLOSE WINDOW*/
$(".close").click(function(){
  $(this).parent().parent().hide();
  startScroll();
});

/*EDIT BUTTON CLICK OPEN WINDOW*/
$(".editbutton").click(function(){
  $(this).next(".edit").show();
  stopScroll();
});

/*CONTENT BLOCK EDIT BUTTON CLICK (slightly different) OPEN WINDOW*/
$(".cb_edit").click(function(){
  $(this).parent().next().next().show();
});

/*ADD BUTTON CLICK OPEN WINDOW*/
$(".addbutton").click(function(){
  $(this).next(".add").show();
  stopScroll();
});

/*ADD BUTTON CLICK OPEN WINDOW*/
$(".choose_images_btn").click(function(){
  $(".choose_images_window").show();
  var html = "";
  $(".post_section").each(function(index){
    html += "<option value='" + index + "'>" + (index + 1) + ". " + $(this).find(".post_title").val() + "</option>";
  });
  $(".window_section select").html(html);
  stopScroll();
});

$(".post_settings_btn").click(function(){
  $("#post_settings.window").show();
  stopScroll();
});

/*SITE SETTINGS BUTTON CLICK OPEN WINDOW*/
$(".site_settings_button").click(function(){
  $(".site_settings_window").show();
  stopScroll();
});

//close window
$(".close_window").click(function(){
  var prev = $(".post_visual").find(".replace").html();
  $(".post_visual").find(".replace").replaceWith(prev);
  $(".file_checkbox:checkbox").prop("checked", false);
  $(".link_images").html("<input type='url' />");
  $(".link_text").val("");
  $(".image_bg").hide();
  $(".image_autoplay").hide();
  $(".image_title").hide();
  $(this).parent().parent().hide();
  startScroll();
});

//background click close window
$(".window").click(function(){
    var prev = $(".post_visual").find(".replace").html();
    $(".post_visual").find(".replace").replaceWith(prev);
    $(".file_checkbox:checkbox").prop("checked", false);
    $(".link_images").html("<input type='url' />");
    $(".image_bg").hide();
    $(".image_autoplay").hide();
    $(".image_title").hide();
    $(".link_text").val("");
    $(this).hide();
    startScroll();
});

//close window on esc
$(document).keydown(function(e){
    if (e.keyCode === 27)  {
      var prev = $(".post_visual").find(".replace").html();
      $(".post_visual").find(".replace").replaceWith(prev);
      $(".file_checkbox:checkbox").prop("checked", false);
      $(".link_images").html("<input type='url' />");
      $(".image_bg").hide();
      $(".image_autoplay").hide();
      $(".image_title").hide();
      $(".window").hide();
      $(".link_text").val("");
      startScroll();
    }
});

});
