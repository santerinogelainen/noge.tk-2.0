
$(document).ready(function(){
  $(".section_content").on("mouseenter", ".cb_info_button:not(.cb_black)", function(){
    $(this).animate({
      top: "2px",
      right: "2px",
      width: "31px",
      height: "31px",
      opacity: 1
    }, 200);
  });
  $(".section_content").on("mouseleave", ".cb_info_button:not(.cb_black)", function(){
    $(this).animate({
      top: "5px",
      right: "5px",
      width: "25px",
      height: "25px",
      opacity: 0.7
    }, 200);
  });
  
  $(".section_content").on("click", ".cb_info_button:not(.cb_black)", function(){
    var block = $(this).parent();
    block.css("transform", "none");
    block.css("transition", "none");
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
  
  
  $(".section_content").on("click", ".cb_black", function(){
    var block = $(this).parent().parent();
    block.css("transform", "none");
    block.css("transition", "none");
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
           },
           complete: function(){
            block.css("transform", "");
            block.css("transition", "");
           }
         });
       }
     });
  });
});

//AUTOMATICALLY PLAY SLIDER
setInterval(function(){
  $(".autoplay").trigger("click");
}, 7000);


//param direction: (bool) true = left, false = right
//animate the slider
function animateSlider(element, direction) {
  var next;
  var parent = $(element).parent();
  var current = parent.find(".slider_current_image");
  if (direction) {
    next = parent.find("img").last();
    if (parseFloat(next[0].style.left) >= 50) {
      next.css("left", "-50%");
    }
  } else {
    next = current.next();
  }
  next.stop().animate({
    left: "50%"
  }, 500);
  if (direction) {
    current.stop().animate({
      left: "150%"
    }, 500, function(){
      $(this).before(next);
      $(this).removeClass("slider_current_image");
      next.addClass("slider_current_image");
    });
  } else {
    current.stop().animate({
      left: "-50%"
    }, 500, function(){
      $(this).appendTo(parent);
      $(this).css("left", "150%");
      $(this).removeClass("slider_current_image");
      next.addClass("slider_current_image");
    });
  }
}

//load the image if its a thumbnail
function loadImage(image) {
  $(image).prepend("<div class='image_loader'></div>");
  $(image).prepend("<div class='image_post_full_size'></div>");
  $(image).css("background-image", "none");
  $(image).find(".image_post_full_size").css("background-image", "url('" + $(image).find(".image_post_thumbnail").prop("src") + "')");
  var src = $(image).find(".image_post_thumbnail").attr("data-path");
  var img = new Image();
  img.src = src;
  img.onload = function() {
    $(image).attr("data-loaded", 1);
    $(image).find(".image_loader").remove();
    $(image).find(".image_post_full_size").css("background-image", "url('" + img.src + "')");
  }
}


//OPEN THE THUMBNAIL IMAGE
function openImage(image, e, animate, after) {
  if ($(e.target).is(".remove_image")) {
    return;
  }
  animate = (typeof animate !== 'undefined') ?  animate : true;
  after = (typeof after !== 'undefined') ?  after : true;
  if (!$(image).hasClass("lightbox_image")) {
    $(image).css("transition", "none");
    $(image).css("transform", "scale(1)");
    if (animate) {
      var curpos = $(image).offset();
      var scroll = $(window).scrollTop();
      var fixedpos = curpos.top - scroll;
    }
    if ($(image).find(".image_post_thumbnail").length) { //if its an image post image
      $(".lightbox_desc").text($(image).attr("data-desc") + "");
      $(".lightbox_title").val($(image).attr("data-title") + "");
      $(image).find(".remove_image").hide();
      if (parseInt($(image).attr("data-loaded")) != 1) { //if not loaded
        loadImage(image);
      } else {
        $(image).find(".image_post_full_size").show();
        $(image).css("background-image", "none");
      }
      $(image).find(".image_post_thumbnail").css("opacity", 0);
      if (after) {
        $(image).after("<div class='thumbnail_image temp_thumbnail image_post_temp' style='width: " + $(image).width() + "px'></div>");
      } else {
        $(image).before("<div class='thumbnail_image temp_thumbnail image_post_temp' style='width: " + $(image).width() + "px'></div>");
      }
    } else {
      if (after) {
        $(image).after("<div class='thumbnail_image temp_thumbnail'></div>");
      } else {
        $(image).before("<div class='thumbnail_image temp_thumbnail'></div>");
      }
    }
    if (animate) {
      $(image).css({
        top: fixedpos + "px",
        left: curpos.left + "px"
      });
    }
    $(image).css("cursor", "move");
    $(image).draggable();
    $(image).draggable("enable");
    $(image).addClass("lightbox_image");
    
    if (animate) {
      $(".lightbox, .lightbox_button").show();
      $(".lightbox").animate({
        opacity: "0.8"
      }, 500);
      $(".lightbox_button").animate({
        opacity: "1"
      }, 500);
    
      $(image).stop().animate({
        width: "100%",
        height: "100%",
        top: 0,
        left: 0
      }, 500, function(){
        if ($(image).find(".image_post_thumbnail").length) {
          $(image).find(".image_post_thumbnail").hide();
          $(image).find(".image_post_thumbnail").css("opacity", 1);
        }
      });
    } else {
      $(image).css({
        width: "100%",
        height: "100%",
        top: 0,
        left: 0
      });
      if ($(image).find(".image_post_thumbnail").length) {
        $(image).find(".image_post_thumbnail").hide();
        $(image).find(".image_post_thumbnail").css("opacity", 1);
      }
    }
  }
}

//CLOSE THE THUMBNAIL IMAGE
function closeImage(closeall) {
    
    if (closeall) {
      $(".lightbox, .lightbox_button").css({
        opacity: "0",
        display: "none"
      });
    }
    
    $(".temp_thumbnail").remove();
    $(".lightbox_image").css({
      top: "",
      left: "",
      cursor: "",
      transform: "",
      transition: "",
      width: "",
      height: "",
      opacity: ""
    });
    
    $(".lightbox_image").draggable("disable");
    if ($(".lightbox_image").find(".image_post_thumbnail").length) {
      $(".lightbox_image").attr("data-desc", $(".lightbox_desc").html());
      $(".lightbox_image").attr("data-title", $(".lightbox_title").val());
      $(".lightbox_image").find(".image_post_thumbnail").show();
      $(".lightbox_image").find(".image_post_full_size").hide();
      $(".lightbox_image").css("background-image", "url('../img/background.png')");
      $(".lightbox_image").find(".remove_image").show();
    }
    $(".lightbox_image").removeClass("lightbox_image");
    
  }


//SLIDE TO THE NEXT IMAGE ON THE RIGHT IN THUMBNAIL
  function rightImage() {
    if ($(".lightbox_image").next().hasClass("thumbnail_image")) {
      if (!$(".lightbox_image").next().hasClass("temp_thumbnail")) {
        var next = $(".lightbox_image").next();
      } else {
        var next = $(".lightbox_image").next().next();
      }
      closeImage(false);
      openImage(next[0], false, false, false);
    }
  }

//SLIDE TO THE NEXT IMAGE ON THE LEFT IN THUMBNAIL
  function leftImage() {
    if ($(".lightbox_image").prev().hasClass("thumbnail_image")) {
      if (!$(".lightbox_image").prev().hasClass("temp_thumbnail")) {
        var next = $(".lightbox_image").prev();
      } else {
        var next = $(".lightbox_image").prev().prev();
      }
      closeImage(false);
      openImage(next[0], false, false, true);
    }
  }

  //ZOOM IN ON ThE IMAGE ON SCROLL
  function zoomImage(image) {
    if ($(image).hasClass("lightbox_image")) {
      var e = window.event || e;
      var amount = e.wheelDelta / 4;
      e.preventDefault();
      if (amount > 0) {
        $(image).css("width", "+=" + amount);
        $(image).css("height", "+=" + amount);
        $(image).css("top", "-=" + (amount / 2));
        $(image).css("left", "-=" + (amount / 2));
      } else {
        $(image).css("width", "-=" + (amount * -1));
        $(image).css("height", "-=" + (amount * -1));
        $(image).css("top", "+=" + ((amount * -1) / 2));
        $(image).css("left", "+=" + ((amount * -1) / 2));
      }
    }
  }

//SET THE BACKGROUND OF THE LIGHTBOX
  function setBackgroundImage(element) {
    if ($(element).hasClass("lightbox_background_grid")) {
      $(".lightbox").css("background-image", "url('/img/background.png')");
      $(".lightbox").css("opacity", "0.95");
    } else if ($(element).hasClass("lightbox_background_white")) {
      $(".lightbox").css("background-color", "white");
      $(".lightbox").css("opacity", "0.95");
      $(".lightbox").css("background-image", "");
    } else {
      $(".lightbox").css("background-color", "black");
      $(".lightbox").css("background-image", "");
      $(".lightbox").css("opacity", "0.8");
    }
  }

//ZOOM IN ON THE IMAGE (BUTTON CLICK)
function zoominImage() {
  $(".lightbox_image").stop().animate({
    width: "+=200px",
    height: "+=200px",
    top: "-=100px",
    left: "-=100px"
  }, 250);
}

//ZOOM OUT OF THE IMAGE (BUTTON CLICK)
function zoomoutImage() {
  $(".lightbox_image").stop().animate({
    width: "-=200px",
    height: "-=200px",
    top: "+=100px",
    left: "+=100px"
  }, 250);
}
