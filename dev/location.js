var g_location_data;
var g_ship_data;
var g_corp_asset_data;
var g_corporation_id = 98015080;
var expires = Date.createFromMysql(g_expires).valueOf();
var now = Date.now();

//var myVar = setInterval(function(){ getLocation() }, 10000);

function get_all_data(){
  esi_get_data("https://esi.evetech.net/latest/characters/" + g_character_id + "/location/?datasource=tranquility", g_access_token, esi_get_location_handler);
  esi_get_data("https://esi.evetech.net/latest/characters/" + g_character_id + "/ship/?datasource=tranquility", g_access_token, esi_get_ship_handler);
  //esi_get_data("https://esi.evetech.net/v1/characters/" + g_character_id + "/fleet/?datasource=tranquility", g_access_token, esi_get_fleet_handler);
}

function get_corp_asset_data(){
  esi_get_data("https://esi.evetech.net/latest/corporations/" + g_corporation_id + "/assets/?datasource=tranquility&page=1", g_access_token, esi_get_ship_handler);
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

function store_location_data(solar_system_id, ship_type_id){
  var xmlhttp = getXMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      console.log('Location data stored in DB');
      console.log(this.responseText);
    }
  };
  xmlhttp.open("GET", "http://eve-nerd.com/dev/store_location_data.php?ship_type_id="+ship_type_id+"&solar_system_id="+solar_system_id, true);
  xmlhttp.send();
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
  xmlhttp.open("GET", "http://eve-nerd.com/dev/refresh_token.php", true);
  xmlhttp.send();
}

function esi_get_data(url, access_token, cFunction){
  var httpReq = getXMLHttpRequest();
  httpReq.onreadystatechange = function (){
    if (this.readyState == 4 && this.status == 200) {
      cFunction(this);
    }
    if(this.readyState == 4 && this.status == 403) {
      console.log('refresh required');
      refresh_access_token(g_character_id);
    }
  };
  httpReq.open("GET", url, true);
  httpReq.setRequestHeader('accept', 'application/json');
  httpReq.setRequestHeader('authorization', 'Bearer ' + access_token);
  httpReq.send();
}

function esi_post_data(url, access_token, cFunction, fleet_id, character_id, role, wing_id, squad_id){
  var httpReq = getXMLHttpRequest();
  httpReq.onreadystatechange = function (){
    if (this.readyState == 4 && this.status == 200) {
      cFunction(this);
    }
  };
  httpReq.open("POST", url, true);
  httpReq.setRequestHeader('accept', 'application/json');
  httpReq.setRequestHeader('authorization', 'Bearer ' + access_token);
  var invite_data = '{"character_id": '+character_id+', '+'"role": "'+role+'", '+'"wing_id": '+wing_id+', '+'"squad_id": '+squad_id+'}';
  httpReq.send(invite_data);
}

function test_fleet_invite(fleet_id, character_id, role, wing_id, squad_id){
  var url = "https://esi.evetech.net/v1/fleets/" + fleet_id + "/members/?datasource=tranquility";
  esi_post_data(url, g_access_token, esi_get_fleet_invite_handler, fleet_id, character_id, role, wing_id, squad_id)
}

function esi_get_location_handler(httpReq){
  var location_data = JSON.parse(httpReq.responseText);
  if(g_location_data != undefined){
    if(location_data.solar_system_id != g_location_data.solar_system_id){
      store_location_change(location_data);
    }
  }
  g_location_data = location_data;
  document.getElementById("text_location").innerHTML = IDtoName(g_location_data.solar_system_id, solarSystems);
  console.log(g_location_data);
}

function esi_get_ship_handler(httpReq){
  var ship_data = JSON.parse(httpReq.responseText);
  if(g_ship_data != undefined){
    if(ship_data.ship_type_id != g_ship_data.ship_type_id){
      store_ship_change(ship_data);
    }
  }
  g_ship_data = ship_data;
  var ship_img =  '<img src="https://imageserver.eveonline.com/Render/'+ g_ship_data.ship_type_id + '_32.png">';
  document.getElementById("text_ship").innerHTML =  ship_img + IDtoName(g_ship_data.ship_type_id, ship_types);
  console.log(g_ship_data);
}

function store_location_change(location_data){
  var solar_system_id = location_data.solar_system_id;
  console.log('Location change detected: ' + solar_system_id);
  store_location_data(location_data.solar_system_id, g_ship_data.ship_type_id);
}

function store_ship_change(ship_data){
  var ship_type_id = ship_data.ship_type_id;
  console.log('Ship change detected: ' + ship_type_id);
  store_location_data(g_location_data.solar_system_id, ship_data.ship_type_id);
}

function esi_get_corp_assets_handler(httpReq){
  var corp_asset_data = JSON.parse(httpReq.responseText);
  g_corp_asset_data = corp_asset_data;
  console.log(g_corp_asset_data);
}

function esi_get_fleet_handler(httpReq){
  var fleet_data = JSON.parse(httpReq.responseText);
  console.log(fleet_data);
  if(fleet_data.fleet_id != undefined){
    esi_get_data("https://esi.evetech.net/v1/fleets/" + fleet_data.fleet_id + "/?datasource=tranquility", g_access_token, esi_get_fleet_info_handler);
    esi_get_data("https://esi.evetech.net/v1/fleets/" + fleet_data.fleet_id + "/members/?datasource=tranquility", g_access_token, esi_get_fleet_member_handler);
    esi_get_data("https://esi.evetech.net/v1/fleets/" + fleet_data.fleet_id + "/wings/?datasource=tranquility", g_access_token, esi_get_fleet_wings_handler);
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

function esi_get_fleet_invite_handler(httpReq){
  var fleet_invite_data = JSON.parse(httpReq.responseText);
  console.log(fleet_invite_data);
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

