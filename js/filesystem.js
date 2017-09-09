//------------------------------------------//
//                                          //
//    OPEN A NEW FOLDER ON FOLDER CLICK     //
//                                          //
//------------------------------------------//



//on folder click
$(window).ready(function(){
  
  //move to the clicked folder on double click
  $(".file_selection").on("dblclick", ".file_system_dir", function(){
    var curfolder = $(this).parent();
    var path = $(this).attr("data-path");
    var name = $(this).attr("data-name");
    var type = $(this).attr("data-type");
    var dirs = $(this).attr("data-dirs");
    var thumbnail = curfolder.attr("data-thumbnail");
    
    //if we have already been there then the folder content is still in dom, so we can use that
    //prevents unnecessary ajax requests
    if (curfolder.parent().find("div[data-folder='" + path + "']").length) {
      curfolder.hide();
      curfolder.parent().find("div[data-folder='" + path + "']").children().css("opacity", "1");
      curfolder.parent().find("div[data-folder='" + path + "']").show();
    } else { //get the content from the server with ajax
      
      hideFolder(curfolder);
      
      $.ajax({
        url: "https://" + window.location.hostname + "/ajax/getfilesystem.php",
        type: "POST",
        data: {"path": path, "name": name, "type": type, "dirs": dirs, "thumbnail": thumbnail},
        success: function(data) {
          curfolder.after(data);
          curfolder.next().css("opacity", "0");
          curfolder.next().animate({
            opacity: 1
          }, 250);
          $(".file_system_loader").animate({
            opacity: 0
          }, 250, function(){
            $(".file_system_loader").hide();
          });
          curfolder.hide();
          curfolder.children().css("opacity", "1");
        }
      });
    }
  });
  
  
  //when we click the plus icon to add a new folder
  $(".file_selection").on("click", ".fs_new_folder", function(){
    $(this).hide();
    var html = "<label class='file_system_file'><div class='file_system_border'><img src='/svg/dir.svg' class='file_system_thumbnail'><button type='button' class='fs_create_folder_button'>Create</button><span class='file_system_name'><input type='text'/></span></div></label>";
    $(this).parent().parent().find(".file_system_folder:visible").prepend(html);
    $(this).parent().parent().find(".file_system_name input").focus();
  });
  
  
  //when we click create after we have clicked the plus icon
  $(".file_selection").on("click", ".fs_create_folder_button", function(){
    var name = $(this).parent().parent().find(".file_system_name input").val();
    var htmlname = $(this).parent().parent().parent().attr("data-name");
    var type = $(this).parent().parent().parent().attr("data-type");
    var folder = $(this).parent().parent().parent().attr("data-folder");
    var thumbnail = $(this).parent().parent().parent().attr("data-thumbnail");
    var dirs = $(this).parent().parent().parent().attr("data-dirs");
    
    if (name == "") { //cant be empty
      alert("Folder name can't be empty.");
      return;
    }
    if (!/[A-Za-z0-9_-]/.test(name)) { //only file name safe characters, does not prevent everything but this is enough
      alert("A-Z, a-z, 0-9, _ and - only!");
      return;
    }
    
    hideFolder($(this).parent().parent().parent());
    
    $.ajax({
      url: "https://" + window.location.hostname + "/ajax/newfolder.php",
      data: {"type": type, "name": name, "folder": folder, "html_name": htmlname, "dirs": dirs, "thumbnail": thumbnail},
      method: "POST",
      success: function(){
        reloadFolder(folder, htmlname, type, dirs);
      }
    });
    $(".fs_new_folder").show();
  });
  
  
  
  
  //on reload folder button click
  $(".file_selection").on("click", ".fs_reload_folder", function(){
    var folder = $(this).parent().parent().find(".file_system_folder:visible");
    reloadFolder(folder.attr("data-folder"), folder.attr("data-name"), folder.attr("data-type"), folder.attr("data-dirs"), folder.attr("data-thumbnail"));
  });
  
  
  //stop browsers from doing stupid shit
  //disable image dragging
  $(".file_selection").on('dragstart', ".file_system_thumbnail",function(event) { event.preventDefault(); });
  
  
  
  /****************************************************\
  *                                                    *
  *                                                    *
  *                                                    *
  *            UPLOAD                                  *
  *                                                    *
  *                                                    *
  *                                                    *
  *****************************************************/
  
  //prevent browser bullshit
  $(window).on("dragexit dragleave dragend dragenter dragover drop", function(e){
    e.preventDefault();
    e.stopPropagation();
  });
  
  //show upload helper
  $(window).on("dragenter", function(e) {
    $(".file_selection").addClass("upload_info");
  });
  
  //remove upload helper
  $(window).on("drop", function(e) {
    $(".file_selection").removeClass("upload_info");
  });
  
  //remove upload helper if it bugs out
  $(".file_selection").on("click", function(e) {
    if ($(this).hasClass("upload_info")) {
      $(".file_selection").removeClass("upload_info");
    }
  });
  
  //drop files
  $(".file_selection").on("drop", function(e){
    if (e.originalEvent.dataTransfer) {
      if (e.originalEvent.dataTransfer.files.length) {
        var curfolder = $(this).find(".file_system_folder:visible");
        hideFolder(curfolder);
        var folder = curfolder.attr("data-folder");
        var name = curfolder.attr("data-name");
        var type = curfolder.attr("data-type");
        var thumbnail = curfolder.attr("data-thumbnail");
        var dirs = curfolder.attr("data-dirs");
        var files = e.originalEvent.dataTransfer.files;
        console.log(files);
        uploadFiles(files, folder, function(){}, function(){
          reloadFolder(folder, name, type, dirs, thumbnail);
        });
      }
    }
  });
  
  //on input change
  $(".file_selection").on("change", ".upload_input", function(e){
    if ($(this)[0].files.length) {
      var curfolder = $(this).parent().parent().parent().find(".file_system_folder:visible");
      hideFolder(curfolder);
      var folder = curfolder.attr("data-folder");
      var name = curfolder.attr("data-name");
      var type = curfolder.attr("data-type");
      var thumbnail = curfolder.attr("data-thumbnail");
      var dirs = curfolder.attr("data-dirs");
      var files = $(this)[0].files;
      uploadFiles(files, folder, function(){}, function(){
        reloadFolder(folder, name, type, dirs, thumbnail);
      });
    }
  });
  
  
});

function uploadFiles(files, folder, during, after) {
  var form = new FormData();
  form.append("folder", folder);
  $.each(files, function(index, file) {
    form.append(index, file, file.name);
  });
  $(".file_selection").before("<div class='fs_upload_progress'><div class='fs_upload_remaining'></div></div>");
  $(".fs_upload_progress").animate({
    height: "5px"
  }, 200);
  $.ajax({
    url: "https://" + window.location.hostname + "/ajax/upload.php",
    type: "POST",
    processData: false,
    contentType: false,
    data: form,
    xhr: function() {
      var xhr = $.ajaxSettings.xhr();
      xhr.upload.onprogress = function(e) {
        var percent = Math.floor(e.loaded / e.total * 100);
        $(".fs_upload_progress").css("padding-left", percent + "%");
        during(percent);
      };
      return xhr;
    },
    success: function(response) {
      after(response);
      $(".fs_upload_progress").animate({
        height: "0"
      }, 200, function(){
        $(".fs_upload_progress").remove();
      });
    }
  });
}



//animate and hide folder content
function hideFolder(folder) {
  folder.children().animate({
    opacity: 0
  }, 250);
  $(".file_system_loader").show();
  $(".file_system_loader").animate({
    opacity: 1
  }, 250);
}


//reload the current folder from the server, aka replace the dom version with an up to date version if we add folder or files etc
function reloadFolder(path, name, type, dirs, thumbnail) {
  dirs = typeof dirs !== 'undefined' ? dirs : '¯\_(ツ)_/¯';
  thumbnail = typeof thumbnail !== 'undefined' ? thumbnail : 75;
  
  var folderdiv = $(".file_selection").find(".file_system_folder[data-folder='" + path + "']");
  
  hideFolder(folderdiv);
  
  $.ajax({
    url: "https://" + window.location.hostname + "/ajax/getfilesystem.php",
    type: "POST",
    data: {"path": path, "name": name, "type": type, "dirs": dirs, "thumbnail": thumbnail},
    success: function(data) {
      folderdiv.replaceWith(data);
      folderdiv.children().animate({
        opacity: 1
      }, 250);
      $(".file_system_loader").animate({
        opacity: 0
      }, 250, function(){
        $(".file_system_loader").hide();
      });
    }
  });
}