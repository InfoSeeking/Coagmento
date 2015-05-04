/* Gets timezone without daylight savings */
Date.prototype.stdTimezoneOffset = function() {
  var jan = new Date(this.getFullYear(), 0, 1);
  var jul = new Date(this.getFullYear(), 6, 1);
  return Math.max(jan.getTimezoneOffset(), jul.getTimezoneOffset());
}

function realDate(dateStr){
  var date = new Date(dateStr);
  if(isNaN(date.getTime())){
    return "?";
  }
  var m = date.getMinutes();
  if(m < 10){
    m = "0" + m;
  }
  var h = date.getHours();
  var h12 = h > 12 ? h - 12 : h;
  h12 = h12 == 0 ? 12 : h12;
  return (date.getMonth()+1) + "/" + date.getDate() + "/" + date.getFullYear() + " " + h12 + ":" + m + (h >= 12 ? "pm" : "am");
}
function prettyDate(dateStr){
  function pluralize(time, type){
    if(time != 1){
      return time + " " + type + "s" + " ago";
    }
    else{
      return time + " " + type + " ago";
    }
  }
  var pretty = "just now";
  var date = new Date(dateStr);
  if(isNaN(date.getTime())){
    return "at an unknown time";
  }
  var now = new Date();
  var hourOff = now.stdTimezoneOffset()/60;
  //hourOff is positive if the UTC offset is negative...ok...
  now.setHours(now.getHours() - hourOff + 5);

  var diff = now.getTime() - date.getTime();

  if(diff < 1000){
    pretty = "just now";
  }
  else if(diff < 60 * 1000){
    pretty = pluralize(Math.round(diff/1000), "second");
  }
  else if(diff < 60 * 60 * 1000){
    pretty = pluralize(Math.round(diff/(60 * 1000)), "minute");
  }
  else if(diff < 24 * 60 * 60 * 1000){
    pretty = pluralize(Math.round(diff/(60 * 60 * 1000)), "hour");
  }
  else if(diff < 30 * 24 * 60 * 60 * 1000){
    pretty = pluralize(Math.round(diff/(24 * 60 * 60 * 1000)), "day");
  }
  else if(diff < 12 * 30 * 24 * 60 * 60 * 1000){
    pretty = pluralize(Math.round(diff/(30 * 24 * 60 * 60 * 1000)), "month");
  }
  else{
    pretty = "over a year ago";
  }
  return pretty;
}
