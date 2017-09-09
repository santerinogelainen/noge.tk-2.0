$(document).ready(function(){

  /* ORDER IN EDIT MODE */
  $(".editmenu_order").sortable();
  $(".editmenu_order").disableSelection();

  /*ANIMATE EDIT AND ADD BUTTONS*/
  $(".editbutton, .addbutton").mouseover(function(){
    $(this).animate({
      opacity: 1
    }, 300);
  });

  $(".editbutton, .addbutton").mouseleave(function(){
    $(this).animate({
      opacity: 0.5
    }, 300);
  });


  //HERE
  $(".site_settings_form").bind("click", ".settings_add_css", function(e){
    var name = $(e.target).attr("data-key");
    if ($(e.target).hasClass("settings_add_css")) {
      if ($(e.target).parent().find(".css_selector_input").length) {
        var val = $(e.target).parent().find(".css_selector_input").val();
        $(e.target).parent().find(".css_selector_input").remove();
        $(e.target).before(val + " ");
      }
      $(e.target).parent().after("<label class='settings_css'><span class='setting_name'><input class='setting_name_input' type='text'/></span><input name='css[" + name + "]' class='setting_input setting_input_insert' type='text'/><span class='settings_remove_css' title='Remove'>&#x2715;</span></label>");
    }
  });
  
  $(".site_settings_form").bind("click", ".settings_remove_css", function(e){
    if ($(e.target).hasClass("settings_remove_css")) {
      $(e.target).parent().remove();
    }
  });
  
  $(".site_settings_form").bind("input change", ".setting_name_input", function(e){
    if ($(e.target).hasClass("setting_name_input")) {
      var name = $(e.target).parent().next().attr("name");
      var value = $(e.target).val();
      value = value.replace(":", "");
      name = name.split("[");
      if (name.length > 2) {
        name.splice(-1, 1);
      }
      name = name.join("[");
      name = name + "[" + value + "]";
      $(e.target).parent().next().attr("name", name);
    }
  });
  
  $(".site_settings_form").bind("click", ".settings_remove_css_all", function(e){
    if ($(e.target).hasClass("settings_remove_css_all")) {
      $(e.target).parent().nextUntil("h5, .settings_new_css").remove();
      $(e.target).parent().remove();
    }
  });
  
  $(".site_settings_form").bind("click", ".settings_new_css", function(e){
    if ($(e.target).hasClass("settings_new_css")) {
      $(e.target).before("<h5><input type='text' class='css_selector_input' /><span class='settings_add_css'>(Add)</span><span class='settings_remove_css_all'>(Remove)</span></h5>");
    }
  });
  
  $(".site_settings_form").bind("input change", ".css_selector_input", function(e){
    if ($(e.target).hasClass("css_selector_input")) {
      var name = $(e.target).val();
      $(e.target).next().attr("data-key", name);
    }
  });

  $(".add_back_to_start").click(function(){
    $(".add_hide").hide();
    $(".add_choose").show();
  });
  
  $(".add_title").on("input change", function(){
    var val = $(this).val();
    $(".add_key input").val(val.replace(/[\s]/g, "_").replace(/[^a-z0-9_]/gi, '').toLowerCase());
  });

  $(".add_post_next").click(function(){
    var chosen = $(".add_post_table input[name=posts]:checked");
    var href = chosen.val();
    var title = chosen.attr("data-title");
    $(".add_title").val(title);
    $(".add_href").val(href);
    $(".add_key input").val(title.replace(/[\s]/g, "_").replace(/[^a-z0-9_]/gi, '').toLowerCase());
  });

});



//------------------------------------------//
//                                          //
//         DISABLE CELLS IN EDIT TABLE      //
//                                          //
//------------------------------------------//

function disableCells(element){

  //get clicks
  clicks = $(element).data('clicks');

  //get col class name
  var data;
  if ($(element).is('[data-col]')) {
    data = "data-col=" + $(element).attr("data-col");
  } else {
    data = "data-row=" + $(element).attr("data-row");
  }

  //if clicks are odd (second click)
  if (clicks) {
    //get parent table element of the cols and for each of them
    $(element).parent().parent().find("td[" + data + "]").each(function(){
      
      //if the col does not have the class 'already_disabled'
      if ( !$(this).hasClass('already_disabled')) {
        //set background color to white
        $(this).css("background-color", "transparent");
        //remove attribute 'disabled'
        $(this).find("textarea").removeAttr('disabled');
        //if col has the 'already disabled' class
      } else {
        //remove 'already disabled' class
        $(this).removeClass('already_disabled');
      }
      
    });
    //if clicks are even (first click)
  } else {
    //get parent table element of the cols and for each of them
    $(element).parent().parent().find("td[" + data + "]").each(function() {
      
      //if col is already disabled
      if ($(this).find("textarea").is(":disabled")) {
        //add class 'already_disabled'
        $(this).addClass('already_disabled');
      } else {
        //set background to gray
        $(this).css("background-color", "#eee");
        //disable it
        $(this).find("textarea").attr('disabled', 'disabled');
      }
      
    });
  }

  //calc odd and even
  $(element).data("clicks", !clicks);
}


//------------------------------------------//
//                                          //
//         ADD COLUMN IN EDIT TABLE         //
//                                          //
//------------------------------------------//

function addCol(element) {

  var column = $(element).parent().parent().children();
  var row = $(element).parent().parent().parent().children();
  var name = $(element).parent().attr("data-name");
  var nextColumn = column.length - 1;
  
  console.log(nextColumn);
  
  row.each(function(index){
    if (index == 0) {
      $(this).children().last().before("<td onclick='disableCells(this);' data-col='" + nextColumn + "' class='remove_col editmenu_table_remove'>(Disable)</td>");
    } else {
      $(this).children().last().before("<td data-col='" + nextColumn + "' data-row='" + index + "'><textarea name='" + name + "[" + index + "][" + nextColumn + "]'></textarea></td>");
    }
  });

}

//------------------------------------------//
//                                          //
//         ADD ROW IN EDIT TABLE            //
//                                          //
//------------------------------------------//

function addRow(element, table) {
  
  var column = $(element).parent().parent().children();
  var row = $(element).parent().parent().parent().children();
  var name = $(element).parent().attr("data-name");
  var nextRow = row.length;
  
  row.last().after("<tr data-row='" + nextRow + "'></tr>");
  row = $(element).parent().parent().parent().children();
  
  column.each(function(index){
    if (index == (column.length - 1)) {
      row.last().append("<td onclick='disableCells(this);' data-row='" + nextRow + "' class='remove_col editmenu_table_remove'>(Disable)</td>");
    } else {
      row.last().append("<td data-col='" + index + "' data-row='" + nextRow + "'><textarea name='" + name + "[" + nextRow + "][" + index + "]'></textarea></td>");
    }
  });
  
}


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
