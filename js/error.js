var element = document.getElementById("animate");
var pos = element.getBoundingClientRect();
var rngLR = Math.round(Math.random()); //0 = left, 1 = right
var rngTB = Math.round(Math.random()); //0 = top, 1 = bottom;
var amountLB = 1;
var amountTB = 1;
element.style.position = "fixed";
element.style.left = pos.left + "px";
element.style.top = pos.top + "px";

setInterval(function(){
  if (rngLR === 0) {
    element.style.left = (pos.left - amountLB) + "px";
  } else {
    element.style.left = (pos.left + amountLB) + "px";
  }

  if (rngTB === 0) {
    element.style.top = (pos.top - amountTB) + "px";
  } else {
    element.style.top = (pos.top + amountTB) + "px";
  }
  pos = element.getBoundingClientRect();
  if (pos.left <= 0) {
    rngLR = 1;
  }
  if (pos.left >= (window.innerWidth - pos.width)) {
    rngLR = 0;
  }
  if (pos.top <= 0) {
    rngTB = 1;
  }
  if (pos.top >= (window.innerHeight - pos.height)) {
    rngTB = 0;
  }
}, 20);
