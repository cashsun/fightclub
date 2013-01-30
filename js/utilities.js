$(function(){
  UTIL_TimeConvert("2013-01-29 23:48:00");
});

/* CONVERT SERVER TIME TO LOCAL TIME */
/* RETURN A DATE OBJECT */
function UTIL_TimeConvert(timeStr){

  /* MUST BE IN YYYY-MM-DD HH:MM:SS FORMAT */
  var parts = String(timeStr).split(/[- :]/);
  var local = new Date(parts[0],parts[1]-1,parts[2],parts[3],parts[4],parts[5]);
  var offset = new Date().getTimezoneOffset();
  local.setMinutes(local.getMinutes() - offset);
  return local;
}