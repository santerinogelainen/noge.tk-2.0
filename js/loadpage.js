var interval;

function beginAnimation(el, el2) {
  el.style.opacity = 1;
  var regWidth = 210;
  var regHeight = 210;
  interval = setInterval(function () {
      el.style.opacity -= 0.05;
      regWidth += 10;
      regHeight += 10;
      el2.style.width = regWidth + 'px';
      el2.style.height = regHeight + 'px';
  }, 25);
  setTimeout(function () {
    clearInterval(interval);
    el.style.display = 'none';
  }, 500);
}

window.onload = function () {
  var ele = document.getElementById("page_loading");
  var ele2 = document.getElementsByClassName("loader")[0];
  var ele3 = document.getElementsByClassName("loader_img")[0];
  ele2.style.display = 'none';
  ele3.style.display = 'block';
  beginAnimation(ele, ele3);
  document.getElementsByTagName("body")[0].style.overflowY = 'scroll';
};
