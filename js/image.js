
$(document).ready(function(){
  
  //prevent accidental leave
  $(window).bind("beforeunload", function(){
    return "Are you sure you want to leave? Changes will be lost.";
  });
  
  $(".post_title").eq(0).on("input change", function(){
    if (!$(".custom_page_title").is(":checked")) {
      document.title = $(this).val();
    }
  });
  
  $(".page_title").on("input change", function(){
    document.title = $(this).val();
  });
  
  $(".custom_page_title").on("change", function(){
    if ($(this).is(":checked")) {
      document.title = $(".page_title").val();
      $(".page_title").prop("disabled", false);
    } else {
      document.title = $(".post_title").eq(0).val();
      $(".page_title").prop("disabled", true);
    }
  });
  
  //prevent browser bs and animate stuff
  $(window).on("dragenter", function(e){
    e.preventDefault();
    e.stopPropagation();
    
    
    $(".upload_reminder").animate({
      color: "rgba(255, 255, 255, 1)",
      borderColor: "rgba(255, 255, 255, 1)"
    }, 250);
    if ($(".upload_reminder").is(":hidden")) {
      $(".section_images").animate({
        borderColor: "rgba(255, 255, 255, 1)"
      }, 250);
    }
  });
  
  //upload files
  $(".post_sections").on("drop", ".post_section", function(e){
    if (e.originalEvent.dataTransfer) {
      if (e.originalEvent.dataTransfer.files.length) {
        
        $(".upload_reminder").animate({
          color: "rgba(200, 200, 200, 0.5)",
          borderColor: "rgba(200, 200, 200, 0.5)"
        }, 250);
        
        $(".section_images").animate({
          borderColor: "rgba(255, 255, 255, 0)"
        }, 250);
        
        var id = $(".post_data").attr("data-id");
        var folder = "/img/post/" + id;
        var files = e.originalEvent.dataTransfer.files;
        var reminder = $(this).find(".upload_reminder");
        var imagesdiv = reminder.prev();
        
        $(".file_progress").animate({
          height: "7px"
        }, 200);
        
        uploadFiles(files, folder, function(percent){
          $(".file_progress").css("padding-left", percent + "%");
        }, function(){
          
          reminder.hide();
          
          $.each(files, function(index, file){
            if (file.type == "image/png" || file.type == "image/jpeg") {
              $.ajax({
                url: "https://" + window.location.hostname + "/ajax/getthumbnail.php",
                type: "POST",
                data: {"path": "/img/post/" + id + "/" + file.name.replace(/[^A-Za-z0-9_\-.]/g, ""), "size": 150},
                success: function(image) {
                  imagesdiv.append("<div data-desc='' data-title='' class='thumbnail_image' onclick='openImage(this, event)' onwheel='zoomImage(this)'><img src='" + image + "' class='image_post_thumbnail' data-path='https://" + window.location.hostname + "/img/post/" + id + "/" + file.name.replace(/[^A-Za-z0-9_\-.]/g, "") + "'><img src='/svg/close_white.svg' class='remove_image'></div>");
                }
              });
            } else if (file.type == "image/gif" || file.type == "image/svg+xml") {
              imagesdiv.append("<div data-desc='' data-title='' class='thumbnail_image' onclick='openImage(this, event)' onwheel='zoomImage(this)'><img src='https://" + window.location.hostname + "/img/post/" + id + "/" + file.name + "' class='image_post_thumbnail' data-path='https://" + window.location.hostname + "/img/post/" + id + "/" + file.name + "'><img src='/svg/close_white.svg' class='remove_image'></div>");
            }
          });
          
          $(".file_progress").animate({
            height: "0"
          }, 200, function(){
            $(".file_progress").css("padding-left", "0");
          });
        });
      }
    }
  });
  
  //new section
  $(".new_section").click(function(){
    var html = "<div class='post_section'><img title='Remove this section' class='remove_section' src='/svg/close_white.svg'><input type='text' class='post_title' placeholder='Title'><div spellcheck='false' contenteditable class='section_desc'></div><div class='section_images'></div><div class='upload_reminder noselect'>Drag images here.</div></div>";
    $(this).before(html);
  });
  
  //remove section / image
  $(".post_sections").on("click", ".remove_section, .remove_image", function(e){
    $(this).parent().remove();
  });
  
  
  //get files from the server
  $(".window_submit_images").click(function(){
    var section = $(".post_section").eq(parseInt($(".window_section select").val()));
    var images = $(".file_selection input[name=image]:checked");
    images.each(function(index){
      if ($(".upload_reminder").is(":visible")) {
        $(".upload_reminder").hide();
      }
      var thumbnail = $(this).parent().find(".file_system_thumbnail").attr("src");
      var url = "https://" + window.location.hostname + $(this).val();
      section.find(".section_images").append("<div data-desc='' data-title='' class='thumbnail_image' onclick='openImage(this, event)' onwheel='zoomImage(this)'><img src='" + thumbnail + "' class='image_post_thumbnail' data-path='" + url + "'><img src='/svg/close_white.svg' class='remove_image'></div>");
    });
    images.prop("checked", false);
    $(".window").hide();
    startScroll();
  });
  
});

/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*            SAVE                                    *
*                                                    *
*                                                    *
*                                                    *
*****************************************************/

//save function
//TO DO
function savePost(postPublic, preventdefault, e) {
  $(".lightbox_image").attr("data-desc", $(".lightbox_desc").html());
  $(".lightbox_image").attr("data-title", $(".lightbox_title").val());
  if (preventdefault) {
    e.preventDefault();
  }
  var id = $(".post_data").attr("data-id");
  var post = [];
  $(".post_section").each(function(){
    var section = {
      title: "",
      desc: "",
      images: []
    };
    section.title = $(this).find(".post_title").val();
    section.desc = $(this).find(".section_desc").html();
    $(this).find(".thumbnail_image:not(.image_post_temp)").each(function(){
      var image = {
        thumbnail: "",
        url: ""
      };
      if ($(this).attr("data-title")) {
        image.title = $(this).attr("data-title");
      }
      if ($(this).attr("data-desc")) {
        image.desc = $(this).attr("data-desc");
        
      }
      image.url = $(this).find(".image_post_thumbnail").attr("data-path");
      image.thumbnail = $(this).find(".image_post_thumbnail").attr("src");
      section.images.push(image);
    });
    post.push(section);
  });
  post = JSON.stringify(post);
  var form = new FormData();
  form.append("id", id);
  form.append("post", post);
  form.append("public", postPublic);
  if ($(".custom_page_title").is(":checked")) {
    form.append("custom_title", $(".page_title").val());
  } else {
    form.append("custom_title", $(".post_title").eq(0).val());
  }

  $.ajax({
    url: "save_imagepost",
    data: form,
    processData: false,
    contentType: false,
    type: "POST",
    success: function(data){
      toastMsg(data);
    }
  });
}



//save the post to the database
$(".save_publish, .save_draft").click(function(){
    var postPublic = $(this).attr("data-public");
    savePost(postPublic, false);
    $(".post_data").attr("data-public", postPublic);
});

//ctrl + s save the post to the database
$(document).on("keydown", function(e){
    if (e.ctrlKey && e.keyCode == 83) {
        savePost($(".post_data").attr("data-public"), true, e);
    }
});