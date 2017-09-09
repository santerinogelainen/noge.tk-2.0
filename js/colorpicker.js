/*UPDATE COLOR PICKER INPUT*/
function updateColorpicker(val) {
  var curHue = val.parentNode.getElementsByClassName('colorpicker_hue')[0].value;
  var curSat = val.parentNode.getElementsByClassName('colorpicker_sat')[0].value;
  var curLight = val.parentNode.getElementsByClassName('colorpicker_light')[0].value;
  var curAlpha = val.parentNode.getElementsByClassName('colorpicker_alpha')[0].value;
  if (val.type == 'number') {
    val.nextSibling.nextSibling.value = val.value;
  } else {
    val.previousSibling.previousSibling.value = val.value;
  } if (val.classList.contains('hue')) {
    val.parentNode.firstChild.style.backgroundColor = "hsla(" + val.value + "," + curSat + "%," + curLight + "%," + curAlpha + ")";
  } else if (val.classList.contains('sat')) {
    val.parentNode.firstChild.style.backgroundColor = "hsla(" + curHue + "," + val.value + "%," + curLight + "%," + curAlpha + ")";
  } else if (val.classList.contains('light')) {
    val.parentNode.firstChild.style.backgroundColor = "hsla(" + curHue + "," + curSat + "%," + val.value + "%," + curAlpha + ")";
  } else if (val.classList.contains('alpha')) {
    val.parentNode.firstChild.style.backgroundColor = "hsla(" + curHue + "," + curSat + "%," + curLight + "%," + val.value + ")";
  }
}
