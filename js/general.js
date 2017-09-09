$(document).ready(function(){
  var loginopen = false;

//close login window
$("#login-close").click(function(){
  $("#login-form").animate({
    bottom: "-250px"
  }, 500);
  loginopen = false;
});

//open login window
$("#locked").click(function(){
  $("#login-form").animate({
    bottom: "0"
  }, 500);
  loginopen = true;
});

//on enter keypress; login
$(".login-input#password").keyup(function(event){
  if(loginopen === true && event.keyCode == 13){
      $("#login-button").click();
  }
});

//show contact window if user clicks a button in the footer
$(".footer_contact").click(function(){
  $(".contact_window").show();
  stopScroll();
});

//hide contact window if user clicks the cross
$(".contact_close").click(function(){
  $(".contact_window").hide();
  startScroll();
});

});

//show a toast message to the user
function toastMsg(string) {
  $("body").append("<div class='toast'>" + string + "</div>");
  $(".toast").stop().animate({
    opacity: 1
  }, 500);
  setTimeout(function(){
    $(".toast").stop().animate({
      opacity: 0
    }, 500, function(){
      $(".toast").remove();
    });
  }, 5000);
}

//stop body scrolling
function stopScroll() {
  $('body').addClass('noscroll');
  $('body').bind('touchmove', function(e){e.preventDefault()});
}


//start body scrolling
function startScroll() {
  $('body').removeClass('noscroll');
  $('body').unbind('touchmove');
}


//-------------------------------------//
//                                     //
//                                     //
//              COOKIES                //
//                                     //
//                                     //
//-------------------------------------//

//read a cookie
var cookie = readCookie('cookie_approval');
//if cookie is not set or the user has not yet agreed to cookie usage
if (cookie != null && cookie == 0) {
//after 2 seconds of page being loaded
setTimeout(function(){
  //make ajax request to cookies.php
  $.ajax({
    type: "POST",
    url: "site/cookies.php"
  }).done(function(msg){
    //add the cookie message to document flow
    $("body").append(msg);
    //animate it to appear from the bottom
    $(".cookie_notice").animate({
      bottom: 0
    }, 500);
  });
}, 2000);
}

//disagree to cookies
function disagreeCookies() {
  //ajax request to unset_cookies.php
  //(to remove set cookies)
  $.ajax({
    type: "POST",
    url: "../site/unset_cookies.php"
  });
  //redirect user to google.com
  oldURL = "http://www.google.com";
  window.location.href = oldURL;
}


//NOT MY FUNCTION
//see:
//http://stackoverflow.com/questions/14573223/set-cookie-and-get-cookie-with-javascript
function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

//hide the cookie window
function hideCookieWindow() {
  $(".cookie_window").hide();
  startScroll();
}

//show the cookie window
function showCookieWindow() {
  $(".cookie_window").show();
  stopScroll();
}

//agree to cookies
function agreeCookies() {
  //new date
  var d = new Date();
  //set date to 10 years forward
  d.setTime(d.getTime() + (3600 * 1000 * 24 * 365 * 10));
  //set date to UTC
  var expires = d.toUTCString();
  //animate cookie notice
  $(".cookie_notice").animate({
    bottom: "-500px"
  }, 500, function () {
    $(".cookie_notice").hide();
  });
  //update the cookie value
  document.cookie = "cookie_approval=1; expires=" + expires + "; path=/";
}
