function dataShow(element) {
  var data = $(element).attr("data-show");
  $("[data-show=" + data + "]").show();
  $(element).parent().hide();
  switch ($(element).parent().attr("data-show")) {
    case 'url':
      $(".add_href").val($(element).prev().prev().val());
      break;
    case 'file':
      $(".add_href").val($(element).parent().find("input[name=choose]:checked").val());
      break;
    case 'post':
      //TODO
      break;
    default:
    //nothing
  }
}

$(document).ready(function(){
  
  $(".add_back_to_start").click(function(){
    $(".add_hide").hide();
    $(".add_choose").show();
  });
  
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
  
  $(".add_title").on("input change", function(){
    var val = $(this).val();
    $(".add_key input").val(val.replace(/[\s]/g, "_").replace(/[^a-z0-9_]/gi, '').toLowerCase());
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
  
  //new section
  $(".new_section").click(function(){
    var html = "<div class='post_section'><img title='Remove this section' class='remove_section' src='/svg/close_white.svg'><input type='text' class='post_title' placeholder='Title'><div spellcheck='false' contenteditable class='section_desc'></div><div class='section_content'></div></div>";
    $(this).before(html);
  });
  
  //remove section / content
  $(".post_sections").on("click", ".remove_section, .remove_content", function(e){
    $(this).parent().remove();
  });
  
  /*ADD BUTTON CLICK OPEN WINDOW*/
  $(".addbutton").click(function(){
    var html = "";
    $(".post_section").each(function(index){
      html += "<option value='" + index + "'>" + (index + 1) + ". " + $(this).find(".post_title").val() + "</option>";
    });
    $(".window_section select").html(html);
  });
  
  
  $(".add_to_section").click(function(){
    var section = $(".post_section").eq(parseInt($(".window_section select").val()));
    var href = $(".add_href").val();
    var title = $(".add_title").val();
    var image = $(".file_system_file input[name='image[]']:checked").val();
    var desc = $(".add_description").val();
    if (!href ||  !title) {
      alert("Title or link target not set.");
      return;
    }
    var html = "<div class='content_block' data-title='" + title + "' data-href='" + href + "'><img src='https://" + window.location.hostname + "/svg/close_white.svg' class='remove_content'/>";
    if (desc) {
      html += "<img src='https://" + window.location.hostname + "/svg/add_white.svg' class='cb_info_button'/>";
      html += "<span class='cb_info_desc'><h3>" + title + "</h3><img class='cb_info_button cb_black' src='https://" + window.location.hostname + "/svg/add_white_thin.svg'/>" + desc + "</span>";
    }
    html += "<a href='" + href + "'><div class='content_pic'></div><h3 class='content_name'>" + title + "</h3></a></div>";
    var block = $(html).appendTo(section.find(".section_content"));
    if (desc) {
      block.attr("data-desc", desc);
    }
    if (image) {
      block.find(".content_pic").append("<div class='content_loader'></div>");
      $.ajax({
        url: "https://" + window.location.hostname + "/ajax/getthumbnail.php",
        type: "POST",
        data: {"path": image, "size": 200},
        success: function(thumbnail){
          block.find(".content_pic").css("background-image", "url('" + thumbnail + "')");
          block.attr("data-thumbnail", thumbnail);
          block.find(".content_loader").remove();
        }
      });
    } else {
      block.find(".content_pic").css("background-image", "url('https://" + window.location.hostname + "/img/no_image.png')");
      block.attr("data-thumbnail", "https://" + window.location.hostname + "/img/no_image.png");
    }
    $(".add.window").hide();
    $(".add_href").val("");
    $(".add_title").val("");
    $(".file_system_file input[type=radio]:checked").prop("checked", false);
    $(".add_description").val("");
    $(".add_hide").hide();
    $(".add_choose").show();
    $(".add_post_table input[name=posts]:checked").prop("checked", false);
    startScroll();
  });
  
  $(".add_post_next").click(function(){
    var chosen = $(".add_post_table input[name=posts]:checked");
    var href = chosen.val();
    var title = chosen.attr("data-title");
    $(".add_title").val(title);
    $(".add_href").val(href);
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
  if (preventdefault) {
    e.preventDefault();
  }
  var id = $(".post_data").attr("data-id");
  var post = [];
  $(".post_section").each(function(){
    var section = {
      title: "",
      desc: "",
      content: []
    };
    section.title = $(this).find(".post_title").val();
    section.desc = $(this).find(".section_desc").html();
    $(this).find(".content_block").each(function(){
      var block = {
        title: "",
        thumbnail: "",
        href: ""
      };
      block.title = $(this).attr("data-title");
      if ($(this).attr("data-desc")) {
        block.desc = $(this).attr("data-desc");
      }
      block.href = $(this).attr("data-href");
      block.thumbnail = $(this).attr("data-thumbnail");
      section.content.push(block);
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
    url: "save_contentpage",
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