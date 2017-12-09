var g_location_data;
var character_id = g_character_id;
var access_token = g_access_token;
var expires = Date.createFromMysql(g_expires).valueOf();
var now = Date.now();

var myVar = setInterval(function(){ getLocation() }, 10000);

function getLocation(){
  if(expires<now){
    console.log('access_token expired at '+ expires);
    refresh_access_token(character_id, access_token);
  }
  else{
    esi_get_location(character_id, access_token);
  }
}

function refresh_access_token(character_id, access_token){
  var xmlhttp = getXMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var json_data = JSON.parse(this.responseText);
      g_access_token = json_data.access_token;
      g_expires = json_data.expires;
      expires = Date.createFromMysql(g_expires).valueOf();
      console.log(this.responseText);
      esi_get_location(character_id, g_access_token);
    }
  };
  xmlhttp.open("GET", "https://www.eve-nerd.com/dev/refresh_token.php", true);
  xmlhttp.send();
}

function esi_get_location(character_id, access_token){
  var loc_http = getXMLHttpRequest();
  loc_http.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var location_data = JSON.parse(this.responseText);
      g_location_data = location_data;
      document.getElementById("text_location").innerHTML = IDtoName(g_location_data.solar_system_id, solarSystems);
      console.log(g_location_data);
    }
  };
  loc_http.open("GET", "https://esi.tech.ccp.is/latest/characters/" + character_id + "/location/?datasource=tranquility", true);
  loc_http.setRequestHeader('accept', 'application/json');
  loc_http.setRequestHeader('authorization', 'Bearer ' + access_token);
  loc_http.send();
}

function getXMLHttpRequest(){
  if (window.XMLHttpRequest) {
    // code for modern browsers
    httpReq = new XMLHttpRequest();
  } else {
    // code for old IE browsers
    httpReq = new ActiveXObject("Microsoft.XMLHTTP");
  }
  return httpReq;
}

function IDtoName(ID, list){
  var name = '';
  for(var i=0;i<list.length;i++){
    if(list[i].I==ID){
      name = list[i].N;
      break;
    }
  }
  return name;
}

