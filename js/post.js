



//ALERT USER IF THEY TRY TO LEAVE THE PAGE
$(document).ready(function(){
  $(window).bind("beforeunload", function(){
    return "Are you sure you want to leave? Changes will be lost.";
  });
});



/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*                 RANGE MODIFICATION                 *
*                                                    *
*                                                    *
*                                                    *
*****************************************************/



//ES7 where are you
//https://github.com/benjamingr/RegExp.escape
RegExp.escape = function(text) {
  return text.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
};

//surround the hightlighted text with html
function surroundHighlight(element) {
  if (window.getSelection) { //if there is a selection
    var selection = window.getSelection(); //get selection
    if (selection.rangeCount > 0) {
      range = selection.getRangeAt(0); //get range of selection
      var el = $(element);
      //we always want the deepest element (most nested element) if there are multiple elements
      if (el.children().length > 0) {
        var $target = $(el).children();
        while( $target.length ) {
          $target = $target.children();
        }
        $target.html(range.extractContents());
      } else {
        el.html(range.extractContents());
      }

      range.deleteContents(); //delete the range
      range.insertNode(el[0]); //replace it with the cloned range and the created element
      selection.removeAllRanges();
      //set selection back to the original
      selection.addRange(range);
    }
  }
}

//add html/text to caret position
function htmlToCaret(html) {
  var range;
  if (window.getSelection) { //if there is a selection
    var selection = window.getSelection(); //get selection
    if (selection.getRangeAt && selection.rangeCount) {
      range = selection.getRangeAt(0); //get range of selection
      range.deleteContents(); //delete the range
      var content = html;
      if ($(".post_html_btn").hasClass("selected")) {
        content = spanifyText(html);
      }
      var frag = range.createContextualFragment(content); //create the html fragment from parameter
      range.insertNode(frag); //insert the fragment to range

    }
  }
}

//get the tag the caret is currently in contenteditable
function selectionParentTag() {
  var node = document.getSelection().anchorNode;
  return node.parentNode;
}

//MESSY MIGHT CHANGE, NOT RELIABLE
//add span tags around a piece of text with a class to add styling to spesific words and return the html
function spanifyText(text, regex, cls) {
  if (regex === undefined) {
    regex = "<.*?>";
  } if (cls === undefined) {
    cls = "tag";
  }
  var reg = new RegExp(regex, "g");
  var instances = text.match(reg);
  if (instances !== null) {
  var i = 0;
  var newText = text;
  while (i < instances.length) {
    var instance = instances[i].replace(/</g, '&#60;').replace(/>/g, '&#62;').replace(/"/g, '&#34;').replace(/'/g, '&#39;').replace(/\//g, '&#47;');

    //for escape see top of this document
    //escaping allows attributes like onclick="myFunction(blabla)" to be used
    //                                                   ^      ^
    //if we do not espace the string, regex thinks that the function parenthesis are a part of the regex and the search will fail on replace
    var rx = new RegExp(RegExp.escape(instances[i]));
    if (instances[i].substr(0, 4) == "<!--") {
      newText = newText.replace(rx, "<span class='html_editor_color comment'>" + instance + "</span   >");
    } else {
      newText = newText.replace(rx, "<span class='html_editor_color " + cls + "'>" + instance + "</span   >");
    }
    i++;
  }
  return newText;
  }
}





/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*              CONTENT EDITABLE TABLES               *
*                                                    *
*                                                    *
*                                                    *
*****************************************************/



//create a table to the editor
function createTable() {
  var tables = []; //create array for current amount of tables
  //check if any tables exist
  last = $(".post_visual table[class*='t-']").last().attr('class');
  if (last !== undefined) {
  //get all table class names
  last = $(".post_visual table[class*='t-']").each(function(){
    //get the number from class name (example: "t-5" will get 5)
    n = parseInt($(this).attr('class').split("-")[1]);
    tables.push(n); //push to the array
  });
  //sort the array low to high
  tables.sort(function (a, b) { return a - b; });
  //make the new class name by getting the last number and adding 1 to it
  current = "t-" + (tables.slice(-1)[0] + 1);
  } else {
  //if there are no tables yet make class name "t-1"
  current = "t-1";
  }
  htmlToCaret("<br /><table class='" + current + "'><tbody><tr><td class='c-1'> </td><td class='c-2'> </td></tr><tr><td class='c-1'> </td><td class='c-2'> </td></tr></tbody></table><br />");
}

//add a row to the table
function addRow() {
  if ($(selectionParentTag()).is('[class*="c-"]')) {
  var fullrow = "<tr>";
  var row = selectionParentTag().parentNode;
  $(row).find("td").each(function(index){
    fullrow += "<td class='c-" + (index + 1) + "'> </td>";
  });
  fullrow += "</tr>";
  $(row).after(fullrow);
  }
}

//remove a row from the table
function removeRow() {
  if ($(selectionParentTag()).is('[class*="c-"]')) {
    var row = selectionParentTag().parentNode;
    $(row).replaceWith("");
  }
}

//add a column to the table
function addCol() {
  if ($(selectionParentTag()).is('[class*="c-"]')) {
    var table = selectionParentTag().parentNode.parentNode.parentNode;
    var col = $(selectionParentTag()).attr('class');
    $(table).find("." + col).after("<td> </td>");
    $(table).find("tr").each(function(){
      $(this).find("td").each(function(index){
        $(this).removeClass();
        $(this).addClass("c-" + (index + 1));
      });
    })
  }
}

//remove a column from the table
function removeCol() {
  if ($(selectionParentTag()).is('[class*="c-"]')) {
    var table = selectionParentTag().parentNode.parentNode.parentNode;
    var col = $(selectionParentTag()).attr('class');
    $(table).find("." + col).replaceWith("");
  }
}




/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*              PREVENT BROWSER FROM DOING            *
*                   STUPID SHIT                      *
*                                                    *
*                                                    *
*****************************************************/


//prevent the focus from going away from the visual area
$(".post_edit").click(function(){
  if (!$(this).hasClass("post_edit_long")) {
    $(".post_visual")[0].focus();
  }
});

//prevent tab behaviour and add li behaviour
$('div[contenteditable]').on('keydown', function(e) {
  if(e.keyCode == 9) {
    e.preventDefault();
    if (window.getSelection) { //if there is a selection
      var selection = window.getSelection(); //get selection
      if (selection.rangeCount > 0) {
        range = selection.getRangeAt(0); //get range of selection
      }
    }
    if ($(selectionParentTag()).prop('tagName') == "LI") {
      $(selectionParentTag()).wrap("<ul></ul>");
    } else if ($(document.getSelection().anchorNode).prop('tagName') == "LI") {
      var anchorNode = document.getSelection().anchorNode;
      $(anchorNode).wrap("<ul></ul>");
    }
    selection.removeAllRanges();
    selection.addRange(range);
  }
});


//prevent chrome from creating a div on enter click
//see http://stackoverflow.com/questions/18552336/prevent-contenteditable-adding-div-on-enter-chrome
$('div[contenteditable]').keydown(function(e) {
  // trap the return key being pressed
  if (e.keyCode === 13 && ($(selectionParentTag()).prop('tagName') == "DIV" || $(selectionParentTag()).prop('tagName') == "BLOCKQUOTE")) {
    // insert 2 br tags (if only one br tag is inserted the cursor won't go to the next line)
    document.execCommand('insertHTML', false, '<br><br>');
    // prevent the default behaviour of return key pressed
    return false;
  }
});

//prevent chrome from creating a div on enter click
//see http://stackoverflow.com/questions/18552336/prevent-contenteditable-adding-div-on-enter-chrome
$('pre[contenteditable]').keydown(function(e) {
  // trap the return key being pressed
  if (e.keyCode === 13) {
    // insert 2 br tags (if only one br tag is inserted the cursor won't go to the next line)
    document.execCommand('insertHTML', false, '\r\n');
    // prevent the default behaviour of return key pressed
    return false;
  }
});





/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*            MODIFY THE CONTENT EDITABLE             *
*                 ON BUTTON CLICKS                   *
*                                                    *
*                                                    *
*****************************************************/


var buttonState = true;
var ele = $(".post_buttons")[0].getBoundingClientRect();
var amountStart = $(window).scrollTop();

//check for the button class that is clicked and do stuff specific to the button
$(".post_edit").mousedown(function() {
  var button = $(this).attr('class').split(' ')[1];
  switch (button) {
    case "post_settings":
      //show settings window
      $("#" + button).show();
      stopScroll();
      break;
    case "post_bold":
      //bold text
      surroundHighlight('<strong></strong>');
      break;
    case "post_underline":
      //underline text
      surroundHighlight('<u></u>');
      break;
    case "post_italicize":
      //italicize text
      surroundHighlight('<i></i>');
      break;
    case "post_overline":
      //"overline" text
      surroundHighlight('<s></s>');
      break;
    case "post_hr":
      //add line break
      htmlToCaret("<hr>");
      break;
    case "post_quote":
      //quote text
      surroundHighlight("<blockquote style='background-color: #eee;'></blockquote>");
      break;
    case "post_image":
      //show image selection window
      surroundHighlight('<span class="image replace"></span>');
      $("#" + button).show();
      stopScroll();
      break;
    case "post_file":
      //show file selection window
      surroundHighlight('<span class="file replace"></span>');
      $("#" + button).show();
      stopScroll();
      break;
    case "post_color":
      //show color selection window
      surroundHighlight('<span class="color replace"></span>');
      $("#" + button).show();
      stopScroll();
      break;
    case "post_highlight":
      //show color selection window
      surroundHighlight('<span class="highlight replace"></span>');
      $("#" + button).show();
      stopScroll();
      break;
    case "post_text_left":
      //align text to left
      surroundHighlight('<div style="text-align: left;"></div>');
      break;
    case "post_text_center":
      //align text to center
      surroundHighlight('<div style="text-align: center;"></div>');
      break;
    case "post_text_right":
      //align text to right
      surroundHighlight('<div style="text-align: right;"></div>');
      break;
    case "post_link":
      //show link paste window
      surroundHighlight('<span class="link replace"></span>');
      $(".link_text").val($(".post_visual .link.replace").html());
      $("#" + button).show();
      stopScroll();
      break;
    case "post_hide":
      //hide the buttons to "preview" the post without edit buttons
      if (amountStart > ele.top) {
        amount = $(".left").height();
        $(".left").stop().animate({
            top: "-" + amount
        }, 500);
      } else {
        amount = $(".post_buttons").height();
      }
      $(".post_show").show();
      $(".post_textarea").stop().animate({
        top: "-" + amount
      }, 500);
      $(".post_show").stop().animate({
        opacity: 1
      }, 500);
      buttonState = false;
      break;
    case "post_show":
      //show the buttons if they were hidden^
      $(".post_textarea").stop().animate({
        top: 0
      }, 500);
      $(".post_show").stop().animate({
        opacity: 0
      }, 500, function(){
        $(".post_show").hide();
      });
      buttonState = true;
      break;
    case "post_table":
      //create a new table to the contenteditable
      createTable();
      break;
    case "post_add_row":
      //add a row to a table
      addRow();
      break;
    case "post_add_col":
      //add a column to a table
      addCol();
      break;
    case "post_remove_row":
      //remove a row from a table
      removeRow();
      break;
    case "post_remove_col":
      //remove a column from a table
      removeCol();
      break;
    case "post_list":
      //add an unordered list
      surroundHighlight("<ul><li></li></ul>");
      break;
    case "post_olist":
      //add an ordered list
      surroundHighlight("<ol><li></li></ol>");
      break;
    case "post_video":
      //add a video to a post
      surroundHighlight('<span class="video replace"></span>');
      $("#" + button).show();
      stopScroll();
      break;
    default:
    //NOTHING :), should not ever happen
  }
});

//if post_font_size dropdown value changes surround the current selection
$('.post_font_size').change(function(){
  var value = $(this).val();
  var css = "font-size: " + value + "px";
  surroundHighlight('<span style="' + css + '"></span>');
  $(this).val('first');
});

//if post_font_family dropdown value changes surround the current selection
$('.post_font_family').change(function(){
  var value = $('option:selected', this).attr('style');
  surroundHighlight('<span style="' + value + '"></span>');
  $(this).val('first');
});

//if post_heading dropdown value changes surround the current selection
$('.post_heading').change(function(){
  var value = $(this).val();
  surroundHighlight("<" + value + "></" + value + ">");
  $(this).val('first');
});





/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*            SWITCH HTML AND VISUAL VIEWS            *
*                                                    *
*                                                    *
*                                                    *
*****************************************************/

//update contenteditable text on HTML / VISUAL button click aswell but only if its not selected already


function copyHtmlContents(from, to) {
  var html = $(from).text().replace(/(?:\r\n|\r|\n)/g, '<br>');
  $(to).html(html);
}



//switch to visual view
$(".post_visual_btn").mousedown(function(){
  if (!$(".post_visual_btn").hasClass("selected")) {
  copyHtmlContents(".post_html", ".post_visual");
  //add and remove selected classes
  $(".post_visual_btn").addClass("selected");
  $(".post_html_btn").removeClass("selected");
  //hide and show text areas
  $(".post_visual").show();
  $(".post_html").hide();
  }
});

//switch to html view
$(".post_html_btn").mousedown(function(){
  if (!$(".post_html_btn").hasClass("selected")) {
  var html = $(".post_visual").html().replace(/<br>/g, '\r\n');
  $(".post_html").html(html);
  $(".post_html").html(spanifyText($(".post_html").html(), "<.*?>", "tag"));
  //add and remove selected classes
  $(".post_html_btn").addClass("selected");
  $(".post_visual_btn").removeClass("selected");
  //hide and show text areas
  $(".post_html").show();
  $(".post_visual").hide();
  }
});



/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*            BUTTONS WITH WINDOWS SUBMIT             *
*                                                    *
*                                                    *
*                                                    *
*****************************************************/

//submit downloadable files to a post
$(".submit_file").click(function(){
  var fullhtml = "<br /><!-- DOWNLOAD --><table class='download'><tbody>";
  $("input[name=file]:checked").each(function(){
    html = "<tr><td><img src='../svg/file_black.svg' /></td><td><a href='" + $(this).val() + "' download><span class='download_name'>" + $(this).next().next().text() + "</span></a></td><td><a href='" + $(this).val() + "' download><span class='download_button'>Download</span></a></td></tr>";
    $(this).prop("checked", false);
    fullhtml += html;
  });
  fullhtml += "</tbody></table><!-- DOWNLOAD --><br /><br />";
  $(".post_visual").find(".file").replaceWith(fullhtml);
  $(this).parent().parent().hide();
  startScroll();
});


//submit a video to the post
$(".submit_video").click(function(){
  var fullhtml = "<!--VIDEO/IFRAME--><iframe src='";
  fullhtml += $(".video_link").val();
  fullhtml += "'";
  if ($(".video_ignore_padding").is(":checked")) {
    fullhtml += "' class='ignorepadding_video'";
  }
  if ($(".video_width").val() !== "" || $(".video_height").val() !== "") {
    fullhtml += " style='";
    if ($(".video_width").val() !== "") {
      fullhtml += "width: " + $(".video_width").val() + ";";
    }
    if ($(".video_height").val() !== "") {
      fullhtml += "height: " + $(".video_height").val() + ";";
    }
    fullhtml += "'";
  }
  fullhtml += "/><!--VIDEO/IFRAME-->";
  $(".post_visual").find(".video").replaceWith(fullhtml);
  $(".video_link").val("");
  $(this).parent().parent().hide();
  startScroll();
});


//change color of the text
$(".submit_color").click(function(){
  var fullhtml = "<span style='color: ";
  if (!$(".color_text").val()) {
    fullhtml += "hsla(" + $(this).parent().find(".colorpicker .hue").val() + ", " + $(this).parent().find(".colorpicker .sat").val() + "%, " + $(this).parent().find(".colorpicker .light").val() + "%, " + $(this).parent().find(".colorpicker .alpha").val() + ")";
  } else {
    fullhtml += $(".color_text").val();
  }
  fullhtml += ";'>" + $(".post_visual").find(".color").html() + "</span>";
  $(".post_visual").find(".color").replaceWith(fullhtml);
  $(this).parent().parent().hide();
  startScroll();
});


//submit a link
$(".submit_link").click(function(){
  var fullhtml = "<a ";
  if (!$(".link_url").val()) {
    alert("Link is required!");
    return;
  } else {
    fullhtml += "href='" + $(".link_url").val() + "' ";
  }
  if ($(".link_new_tab")[0].checked) {
    fullhtml += "target='_blank' ";
  }
  fullhtml += ">" + $(".link_text").val() + "</a>";
  $(".post_visual").find(".link").replaceWith(fullhtml);
  $(".link_url").val("");
  $(".link_new_tab").prop("checked", true);
  $(".link_text").val("");
  $(this).parent().parent().hide();
  startScroll();
});


//highlight text
$(".submit_highlight").click(function(){
  var fullhtml = "<span style='background-color: ";
  if (!$(".highlight_text").val()) {
    fullhtml += "hsla(" + $(this).parent().find(".colorpicker .hue").val() + ", " + $(this).parent().find(".colorpicker .sat").val() + "%, " + $(this).parent().find(".colorpicker .light").val() + "%, " + $(this).parent().find(".colorpicker .alpha").val() + ")";
  } else {
    fullhtml += $(".highlight_text").val();
  }
  fullhtml += ";'>" + $(".post_visual").find(".highlight").html() + "</span>";
  $(".post_visual").find(".highlight").replaceWith(fullhtml);
  $(this).parent().parent().hide();
  startScroll();
});


//submit images to a post
//MESS MIGHT CHANGE
$(".submit_image").click(function(){
  var fullhtml, ignorepadding, width, height, background, title;
  var style = $("input[name=image_style]:checked").val();
  //overwrite width
  if (!$(".image_width").val()) {
    width = "";
  } else {
    width = "width: " + $(".image_width").val() + ";";
  }
  //overwrite height
  if (!$(".image_height").val()) {
    height = "";
  } else {
    height = "height: " + $(".image_height").val() + ";";
  }
  //ignore padding of parent
  if ($(".parent_padding").is(":checked")) {
    ignorepadding = "ignorepadding";
  } else {
    ignorepadding = "";
  }
  //ignore padding of parent
  if ($(".parent_padding_top").is(":checked")) {
    ignorepaddingtop = "ignorepaddingtop";
  } else {
    ignorepaddingtop = "";
  }
  //overwrite background color
  if ($("input[name='slider_bg']:checked").val()) {
    background = "background-color: " + $("input[name='slider_bg']:checked").val() + ";";
  } else {
    background = "";
  }
  //thumbnail or banner title
  if (!$(".image_title").val()) {
    title = "";
  } else {
    title = "<h1>" + $(".image_title").val() + "</h1>";
  }
  //create a slider
  if (style == "slider") {
    var autoplay;
    var i = 0;

    //check slider only settings

    //set slider to autoplay
    if ($(".slider_autoplay").is(":checked")) {
      autoplay = "autoplay";
    } else {
      autoplay = "";
    }

    //start tags
    fullhtml = "<br /><!-- SLIDER --><div style='" + width + height + background + "' contenteditable='false' class='post_slider " + ignorepadding + " " + ignorepaddingtop + "'><img class='slider_left' src='/svg/left_black.svg' onclick='animateSlider(this, true)' /><img class='slider_right " + autoplay + "' src='/svg/right_black.svg' onclick='animateSlider(this, false)' />";
    //foreach image
    $(".link_images input").each(function(index){
      if ($(this).val()) {
      if (index === 0) {
        html = "<img class='slider_image slider_current_image' style='left: 50%;' src='" + $(this).val() + "' />";
      } else {
        html = "<img class='slider_image' style='left: 150%;' src='" + $(this).val() + "' />";
      }
      $(this).val("");
      fullhtml += html;
      i++;
      }
    });
    $("input[name=image]:checked").each(function(index){
      if (index + i === 0) {
        html = "<img class='slider_image slider_current_image' style='left: 50%;' src='" + $(this).val() + "' />";
      } else {
        html = "<img class='slider_image' style='left: 150%;' src='" + $(this).val() + "' />";
      }
      $(this).prop("checked", false);
      fullhtml += html;
    });
    //end tags
    fullhtml += "</div><!-- SLIDER --><br /><br />";

  } else if (style == "thumbnail") { //create a thumbnail

    if ($("input[name='slider_bg']:checked").val() == "black") {
      title = "<h1 style='color: white;'>" + $(".image_title").val() + "</h1>";
    }

    //start tags
    fullhtml = "<br/><!-- THUMBNAIL(S) --><div style='padding-top: 10px; padding-bottom: 10px;" + background + "' class='" + ignorepadding + " " + ignorepaddingtop + "'>" + title;
    //foreach image
    $(".link_images input").each(function(){
      if ($(this).val()) {
      html = "<div class='thumbnail_image' onclick='openImage(this, event);' title='Show image' onwheel='zoomImage(this)' style='background-image:url(&apos;" + $(this).val() + "&apos;);" + width + height + "'/>";
      $(this).val("");
      fullhtml += html;
    }
    });
    $("input[name=image]:checked").each(function(){
      html = "<div class='thumbnail_image' onclick='openImage(this, event);' title='Show image' onwheel='zoomImage(this)' style='background-image:url(&apos;" + $(this).val() + "&apos;);" + width + height + "'/>";
      $(this).prop("checked", false);
      fullhtml += html;
    });
    //end tags
    fullhtml += "</div><!-- THUMBNAIL(S) --><br /><br />";

  } else if (style == "full") { //create a full image

    if (ignorepadding == "ignorepadding") {
      ignorepadding += " ignorepadding_full";
    }

    //start tags
    fullhtml = "<br/><!-- IMAGE(S) -->";
    //foreach image
    $(".link_images input").each(function(){
      if ($(this).val()) {
      html = "<img class='full_image " + ignorepadding + " " + ignorepaddingtop + "' style='" + width + height + "' src='" + $(this).val() + "' />";
      $(this).val("");
      fullhtml += html;
    }
    });
    $("input[name=image]:checked").each(function(){
      html = "<img class='full_image " + ignorepadding + " " + ignorepaddingtop + "' style='" + width + height + "' src='" + $(this).val() + "' />";
      $(this).prop("checked", false);
      fullhtml += html;
    });
    //end tags
    fullhtml += "<!-- IMAGE(S) --><br /><br />";

  } else if (style == "banner") { //create a banner

    if (background !== "") {
      if ($("input[name='slider_bg']:checked").val() == "black") {
        title = "<h1 style='" + background + " color: white;'>" + $(".image_title").val() + "</h1>";
      } else {
        title = "<h1 style='" + background + "'>" + $(".image_title").val() + "</h1>";
      }
    }

    //start tags
    fullhtml = "<br/><!-- BANNER -->";
    //foreach image
    $(".link_images input").each(function(){
      if ($(this).val()) {
      html = "<div class='banner ignorepadding ignorepadding_full " + ignorepaddingtop + "' style='background-image: url(&apos;" + $(this).val() + "&apos;); " + width + height + "'>" + title + "</div>";
      $(this).val("");
      fullhtml += html;
    }
    });
    $("input[name=image]:checked").each(function(){
      html = "<div class='banner ignorepadding ignorepadding_full " + ignorepaddingtop + "' style='background-image: url(&apos;" + $(this).val() + "&apos;); " + width + height + "'>" + title + "</div>";
      $(this).prop("checked", false);
      fullhtml += html;
    });
    //end tags
    fullhtml += "<!-- BANNER --><br /><br />";

  } else {
    alert("No Style Selected");
    return false;
  }
  $(".post_visual").find(".image").replaceWith(fullhtml);
  $("input[name=image_style]:checked").prop("checked", false);
  $("input[name=slider_bg]:checked").prop("checked", false);
  $(".parent_padding").prop("checked", false);
  $(".parent_padding_top").prop("checked", false);
  $(".slider_autoplay").prop("checked", false);
  $(".image_width").val("");
  $(".image_height").val("");
  $(".image_title").val("");
  $(".link_images").html("<input type='url' />");
  $(this).parent().parent().hide();
  startScroll();
});


/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*            IMAGE WINDOW SPECIFIC                   *
*                                                    *
*                                                    *
*                                                    *
*****************************************************/


//show / hide different options in image submit window
$(".choose_style label input").on("change", function(){
  if ($(this).val() == "slider") {
    $(".image_bg").show();
    $(".image_autoplay").show();
    $(".image_title").hide();
  }
  if ($(this).val() == "thumbnail") {
    $(".image_bg").show();
    $(".image_autoplay").hide();
    $(".image_title").show();
  }
  if ($(this).val() == "full") {
    $(".image_bg").hide();
    $(".image_autoplay").hide();
    $(".image_title").hide();
  }
  if ($(this).val() == "banner") {
    $(".image_bg").show();
    $(".image_autoplay").hide();
    $(".image_title").show();
  }
});

//add multiple lines to image url pasting
$(".add_image_link").click(function(){
  $(".link_images").append("<input type='url' />");
});

//fixed post edit buttons when we scoll past them
$(window).scroll(function(){
  amount = $(window).scrollTop();
  if (amount >= ele.top + amountStart) {
    $(".post_buttons").addClass("fixed_buttons");
    $(".post_textarea").css("margin-top", ele.height);
  } else {
    $(".post_buttons").removeClass("fixed_buttons");
    $(".post_textarea").css("margin-top", "0");
  }
});


/****************************************************\
*                                                    *
*                                                    *
*                                                    *
*            PAGE TITLE                              *
*                                                    *
*                                                    *
*                                                    *
*****************************************************/


//change the page title on input change
$(".page_title").change(function(){
  if ($(".page_title").val() === "") {
    document.title = "New post";
  } else {
    document.title = $(".page_title").val();
  }
});

//if user has checked custom title
$('.custom_page_title').change(function() {
     if(this.checked) {
       $(".page_title").prop("disabled", false);
     } else {
       $(".page_title").prop("disabled", true);
       $(".page_title").val($(".post_title").val());
       if ($(".page_title").val() === "") {
         document.title = "New post";
       } else {
         document.title = $(".page_title").val();
       }
     }
});

//custom title input on change update document title
$('.post_title').change(function() {
      if(!$(".custom_page_title")[0].checked) {
        $(".page_title").val($(".post_title").val());
        if ($(".page_title").val() === "") {
          document.title = "New post";
        } else {
          document.title = $(".page_title").val();
        }
      }
});

//on preview mode checkbox xlixk, stop / start edit possibilities
$(".preview_mode").change(function(){
    if (this.checked) {
      $(".post_visual").prop("contenteditable", false);
    } else {
      $(".post_visual").prop("contenteditable", true);
    }
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


function savePost(postPublic, preventdefault, e) {
  if (preventdefault) {
    e.preventDefault();
  }
  if ($(".post_visual").is(":hidden")) {
    copyHtmlContents(".post_html", ".post_visual");
  }
  var post = $(".post_visual").html();
  var title = $(".post_title").val();
  var id = $(".post_data").attr("data-id");
  if (post === "" || title === "") {
    toastMsg("Please fill in the title and add content to the post.");
    return;
  }
  var form = new FormData();
  form.append("id", id);
  form.append("title", title);
  form.append("post", post);
  form.append("public", postPublic);
  if ($(".custom_page_title").is(":checked")) {
    form.append("custom_title", $(".page_title").val());
  } else {
    form.append("custom_title", title);
  }

  $.ajax({
    url: "save_post",
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
