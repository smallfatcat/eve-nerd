var g_location_data;
var character_id = g_character_id;
var access_token = g_access_token;
var expires = Date.createFromMysql(g_expires).valueOf();
var now = Date.now();

if(expires<now){
  console.log('access_token expired at '+ expires);
  if (window.XMLHttpRequest) {
    // code for modern browsers
    xmlhttp = new XMLHttpRequest();
  } else {
    // code for old IE browsers
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      //var location_data = JSON.parse(this.responseText);
      //g_location_data = location_data;
      console.log(this.responseText);
      return this.responseText;
    }
  };
  xmlhttp.open("GET", "https://www.eve-nerd.com/dev/refresh_token.php", true);
  //xmlhttp.setRequestHeader('accept', 'application/json');
  //xmlhttp.setRequestHeader('authorization', 'Bearer ' + access_token);
  xmlhttp.send();
}
else{
  if (window.XMLHttpRequest) {
    // code for modern browsers
    xmlhttp = new XMLHttpRequest();
  } else {
    // code for old IE browsers
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var location_data = JSON.parse(this.responseText);
      g_location_data = location_data;
      console.log(g_location_data);
      return location_data;
    }
  };
  xmlhttp.open("GET", "https://esi.tech.ccp.is/latest/characters/" + character_id + "/location/?datasource=tranquility", true);
  xmlhttp.setRequestHeader('accept', 'application/json');
  xmlhttp.setRequestHeader('authorization', 'Bearer ' + access_token);
  xmlhttp.send();
}

