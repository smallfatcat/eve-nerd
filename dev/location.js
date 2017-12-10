var g_location_data;
var g_ship_data;
var expires = Date.createFromMysql(g_expires).valueOf();
var now = Date.now();

//var myVar = setInterval(function(){ getLocation() }, 10000);

function get_all_data(){
  esi_get_data("https://esi.tech.ccp.is/latest/characters/" + g_character_id + "/location/?datasource=tranquility", g_access_token, esi_get_location_handler);
  esi_get_data("https://esi.tech.ccp.is/latest/characters/" + g_character_id + "/ship/?datasource=tranquility", g_access_token, esi_get_ship_handler);
  esi_get_data("https://esi.tech.ccp.is/v1/characters/" + g_character_id + "/fleet/?datasource=tranquility", g_access_token, esi_get_fleet_handler);
}

function getLocation(){
  now = Date.now();
  if(expires<now){
    console.log('access_token expired at '+ expires);
    refresh_access_token(g_character_id);
  }
  else{
    get_all_data();
  }
}

function refresh_access_token(character_id){
  var xmlhttp = getXMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var json_data = JSON.parse(this.responseText);
      g_access_token = json_data.access_token;
      g_expires = json_data.expires;
      expires = Date.createFromMysql(g_expires).valueOf();
      console.log(this.responseText);
      get_all_data();
    }
  };
  xmlhttp.open("GET", "https://www.eve-nerd.com/dev/refresh_token.php", true);
  xmlhttp.send();
}

function esi_get_data(url, access_token, cFunction){
  var httpReq = getXMLHttpRequest();
  httpReq.onreadystatechange = function (){
    if (this.readyState == 4 && this.status == 200) {
      cFunction(this);
    }
  };
  httpReq.open("GET", url, true);
  httpReq.setRequestHeader('accept', 'application/json');
  httpReq.setRequestHeader('authorization', 'Bearer ' + access_token);
  httpReq.send();
}

function esi_get_location_handler(httpReq){
  var location_data = JSON.parse(httpReq.responseText);
  g_location_data = location_data;
  document.getElementById("text_location").innerHTML = IDtoName(g_location_data.solar_system_id, solarSystems);
  console.log(g_location_data);
}

function esi_get_fleet_handler(httpReq){
  var fleet_data = JSON.parse(httpReq.responseText);
  console.log(fleet_data);
  if(fleet_data.fleet_id != undefined){
    esi_get_data("https://esi.tech.ccp.is/v1/fleets/" + fleet_data.fleet_id + "/?datasource=tranquility", g_access_token, esi_get_fleet_info_handler);
    esi_get_data("https://esi.tech.ccp.is/v1/fleets/" + fleet_data.fleet_id + "/members/?datasource=tranquility", g_access_token, esi_get_fleet_member_handler);
    esi_get_data("https://esi.tech.ccp.is/v1/fleets/" + fleet_data.fleet_id + "/wings/?datasource=tranquility", g_access_token, esi_get_fleet_wings_handler);
  }
}

function esi_get_fleet_info_handler(httpReq){
  var fleet_info_data = JSON.parse(httpReq.responseText);
  console.log(fleet_info_data);
}

function esi_get_fleet_member_handler(httpReq){
  var fleet_member_data = JSON.parse(httpReq.responseText);
  console.log(fleet_member_data);
}

function esi_get_fleet_wings_handler(httpReq){
  var fleet_wings_data = JSON.parse(httpReq.responseText);
  console.log(fleet_wings_data);
}

function esi_get_ship_handler(httpReq){
  var ship_data = JSON.parse(httpReq.responseText);
  g_ship_data = ship_data;
  var ship_img =  '<img src="https://imageserver.eveonline.com/Render/'+ g_ship_data.ship_type_id + '_32.png">';
  document.getElementById("text_ship").innerHTML =  ship_img + IDtoName(g_ship_data.ship_type_id, ship_types);
  console.log(g_ship_data);
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

