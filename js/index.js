
//animate h1 scroll event in empty spaces
function animateEmptySpace(element, stop) {
  //first empty space has already some margin, so this is for that
  var position = element[0].getBoundingClientRect(); //empty space position on the page
  var amount = -position.top;
  //check empty space margin top in chrome dev tools
  
  if (Math.abs(position.top) < (position.height - stop) && position.bottom > stop) {
    element.find("h1").css("margin-top", amount + "px");
  }
}

function initEmptySpace(element, stop) {
  var position = element[0].getBoundingClientRect(); //empty space position on the page
  if (position.top > 0) {
    var amount = -position.height + stop;
  } else {
    var amount = position.height - stop;
  }
  element.find("h1").css("margin-top", amount + "px");
}



$(document).ready(function(){

  initEmptySpace($(".front_page"), 200);
  animateEmptySpace($(".front_page"), 200);
  initEmptySpace($(".work_empty_space"), 200);
  animateEmptySpace($(".work_empty_space"), 200);
  initEmptySpace($(".photography_art_empty_space"), 200);
  animateEmptySpace($(".photography_art_empty_space"), 200);
  initEmptySpace($(".social_media_empty_space"), 200);
  animateEmptySpace($(".social_media_empty_space"), 200);

  //header link hover
  $(".hl").hover(function(){
    //get original values
    bgColor = $(this).css("background-color");
    color = $(this).find("a").css("color");
    fWeight = $(this).find("a").css("font-weight");
    //change values
    $(this).css("background-color", "rgba(255, 255, 255, 1)");
    $(this).find("a").css("color", "#000");
    $(this).find("a").css("font-weight", "700");
  }, function (){ //header link mouseleave
    //set values back to original values
    $(this).css("background-color", bgColor);
    $(this).find("a").css("color", color);
    $(this).find("a").css("font-weight", fWeight);
  });

  //header link click
  $(".hl").click(function(){
    attr = $(this).find("a").attr("class"); //get class of the header link link
    //if the class is not undefined and the class ends with "_link"
    if (attr !== undefined && attr.substr(attr.length - 5, attr.length) == "_link") {
      //get id of the element we are scrolling to (example: #information from #information_link)
      var substr = "#" + attr.substring(0, attr.length - 5);
      //how far away the element is from the document top
      var anitop = $(substr).offset().top - 25;
      //animate scrolling
      $("html, body").animate({ scrollTop: anitop + "px"}, 500);
    }
  });

  //header link click (in hamburger menu)
  $(".hl").click(function(){
    attr = $(this).find("a").attr("class"); //get class of the header link link
    //if the class is not undefined and the class ends with "_hm"
    if (attr !== undefined && attr.substr(attr.length - 3, attr.length) == "_hm") {
      //get id of the element we are scrolling to (example: #information from #information_hm)
      var substr = "#" + attr.substring(0, attr.length - 3);
      //how far away the element is from the document top
      var anitop = $(substr).offset().top + 1;
      //animate scrolling
      $("html, body").animate({ scrollTop: anitop + "px"}, 500);
    }
  });

  //on document scroll
  $(document).scroll(function(){
    
    animateEmptySpace($(".front_page"), 200);
    animateEmptySpace($(".work_empty_space"), 200);
    animateEmptySpace($(".photography_art_empty_space"), 200);
    animateEmptySpace($(".social_media_empty_space"), 200);
    
  });

function hideMe(element) {
  var hidemeState = false;
  //animate opening of hidden stuff
  $(element).click(function(){
    if (hidemeState) {
      $(this).parent().find("h3.hide").animate({opacity: 0, height: 0, lineHeight: 0}, 500);
      $(this).parent().find(".hide").animate({opacity: 0, height: 0}, 500);
      hidemeState = false;
      return hidemeState;
    }
    tableH = $(this).parent().find("table.hide tbody").css("height");
    $(this).parent().find("h3.hide").animate({height: "60px", lineHeight: "60px", opacity: 1}, 500);
    $(this).parent().find("table.hide").animate({height: tableH, opacity: 1}, 500, function(){
      hidemeState = true;
      return hidemeState;
    });
  });
}

hideMe("#me");
hideMe("#web");

  var hmState = false;

  //on hamburger menu click
  $("#hamburger").click(function(){
    //show the hamburger menu
    $("#hamburger-menu").show(500, function(){
      hmState = true;
      return hmState;
    });
  });
  //on body click
  $("body").click(function(){
    //if hamburger menu is open
    if (hmState) {
    //hide it
    setTimeout(function(){
      $("#hamburger-menu").hide(500);
      hmState = false;
      return hmState;
    }, 50);
    }
  });
  
  
  
  $(".content").on("mouseenter", ".content_block", function(){
    $(this).stop().animate({
      margin: "10px",
      maxWidth: "220px"
    }, 200);
    $(this).find(".cb_info_img img").stop().animate({
      width: "109px",
      height: "109px"
    }, 200);
    $(this).find(".cb_info_button:not(.cb_black)").animate({
      opacity: 1
    }, 200);
    $(this).find(".content_name").stop().animate({
      maxWidth: "210px"
    }, 200);
    $(this).find(".content_pic").stop().animate({
      height: "220px"
    }, 200);
  });
  $(".content").on("mouseleave", ".content_block", function(){
    $(this).stop().animate({
      margin: "20px",
      maxWidth: "200px"
    }, 200);
    $(this).find(".cb_info_img img").stop().animate({
      width: "99px",
      height: "99px"
    }, 200);
    $(this).find(".cb_info_button:not(.cb_black)").animate({
      opacity: 0.7
    }, 200);
    $(this).find(".content_name").stop().animate({
      maxWidth: "190px"
    }, 200);
    $(this).find(".content_pic").stop().animate({
      height: "200px"
    }, 200);
  });
  
  $(".content").on("mouseenter", ".cb_info_button:not(.cb_black)", function(){
    $(this).animate({
      top: "2px",
      right: "2px",
      width: "31px",
      height: "31px"
    }, 200);
  });
  $(".content").on("mouseleave", ".cb_info_button:not(.cb_black)", function(){
    $(this).animate({
      top: "5px",
      right: "5px",
      width: "25px",
      height: "25px"
    }, 200);
  });
  
  $(".content").on("click", ".cb_info_button:not(.cb_black)", function(){
    var block = $(this).parent();
    var content = block.find(".cb_info_desc");
     $({deg: 0}).animate({deg: 90}, {
       duration: 250,
       step: function(now) {
         block.css("transform", "rotateY(" + now + "deg)");
       },
       complete: function(){
         content.show();
         $({deg: 0}).animate({deg: 45}, {
           duration: 250,
           step: function(now) {
            content.find(".cb_black").css("transform", "rotate(" + now + "deg)");
           }
         });
         $({deg: 90}).animate({deg: 0}, {
           duration: 250,
           step: function(now) {
            block.css("transform", "rotateY(" + now + "deg)");
           }
         });
       }
     });
  });
  
  $(".content").on("click", ".cb_black", function(){
    var block = $(this).parent().parent();
    var content = block.find(".cb_info_desc");
     $({deg: 45}).animate({deg: 0}, {
       duration: 250,
       step: function(now) {
        content.find(".cb_black").css("transform", "rotate(" + now + "deg)");
       }
     });
     $({deg: 0}).animate({deg: 90}, {
       duration: 250,
       step: function(now) {
         block.css("transform", "rotateY(" + now + "deg)");
       },
       complete: function(){
         content.hide();
         $({deg: 90}).animate({deg: 0}, {
           duration: 250,
           step: function(now) {
            block.css("transform", "rotateY(" + now + "deg)");
           }
         });
       }
     });
  });

  //-------------------------------------//
  //                                     //
  //                                     //
  //        SOCIAL MEDIA CONTENT         //
  //                                     //
  //                                     //
  //-------------------------------------//


  $.ajax({
    type: "GET",
    url: "site/social_media_content.php?data=names"
  }).done(function(data){
    var medias = JSON.parse(data);
    $.each(medias, function(index, value) {
      $.ajax({
        type: "GET",
        url: "site/social_media_content.php?data=" + value,
        success: function(html) {
          if (index == 0) {
             $(".sm_content .loader").remove();
          }
          var append = $(html).appendTo(".sm_content");
          append.animate({
            opacity: 1
          }, 500);
        }
      });
    });
  });

  setInterval(function(){
    $(".scroll_initiative").animate({
      paddingBottom: "10px"
    }, 1000, function(){
      $(".scroll_initiative").animate({
        paddingBottom: "0"
      }, 1000);
    });
  }, 2000);

});
