// eve-nerd.com
// Demo code
// Authors: SmallFatCat, Kenkyuu
// GitHub Repo: https://github.com/smallfatcat/eve-nerd
// Date: 9th November 2017
// Licence: MIT

var region_id = 10000001;

var g_map_data = new Array();
var g_highlight_solar_system = 0;

// Document Ready
$( function() {
	get_map_data(region_id);
} );

function save_map_data(map_data){
	g_map_data = map_data;
	draw_table_result(g_map_data);
	draw_map(g_map_data);
}

function draw_map(map_data){
	

	var canvas = document.getElementById("map_canvas");
  var ctx = canvas.getContext("2d");

  ctx.font="12px Cuprum";
  
  // Clear
  ctx.fillStyle='rgb(255,255,255)';
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  //var headings = map_data.shift();
  map_data.forEach(function(solar_systems){
  	var scale_factor = 1/10e16*500; 
  	var x = solar_systems[6]*scale_factor+600;
  	var y = solar_systems[7]*scale_factor+600;
  	var z = solar_systems[8]*scale_factor+600;
  	var name = solar_systems[1]
  	console.log('solar_system_name: ' + name + ' x: ' + x + ' y: ' + y + ' z: ' + z);
  	if(g_highlight_solar_system == solar_systems[0]){
  		ctx.fillStyle = 'rgb(255, 64, 64)';
  	}else{
  		ctx.fillStyle = 'rgb(64, 255, 64)';
  	}
  	ctx.fillRect(x, z, 5, 5);
  	ctx.fillStyle='rgb(0, 0, 0)';
  	ctx.fillText(name, x - ctx.measureText(name).width/2, z);
  });
 

}

function get_map_data(region_id){
if (window.XMLHttpRequest) {
  // code for modern browsers
  xmlhttp = new XMLHttpRequest();
	} else {
  // code for old IE browsers
  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    var map_data = JSON.parse(this.responseText);
	    save_map_data(map_data);
		}
	};
	xmlhttp.open("GET", "https://www.eve-nerd.com/dev/getMapData.php?region_id="+region_id, true);
	xmlhttp.send();
}

function make_autocomplete_list(bigvar){
	var list = [];
	bigvar.forEach(function(bigVarObject){
		list.push(bigVarObject.N);
	});
	return list;
}

function nameToID(name, list){
	var id = 0;
	for(var i=0;i<list.length;i++){
		if(list[i].N.toLowerCase()==name.toLowerCase()){
			id = list[i].I;
			break;
		}
	}
	return id;
}

function attach_autocomplete(id, list){
	$( id ).autocomplete({
    source: function( request, response ) {
        var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( list, function( item ){
          if(request.term.length>1){
            return matcher.test( item );
          }
        }) );
    }
  });
}

function input_solar_system_click(){
	var solar_system_name = $('#input_solar_system').val();
	var solar_system_id = nameToID(solar_system_name, solarSystems)
	get_data_solar_system(solar_system_id);
	console.log(solar_system_name);
	console.log(solar_system_id);
}

function input_ship_type_click(){
	var ship_name = $('#input_ship_type').val();
	var ship_id = nameToID(ship_name, ship_types);
	get_data_ship_type(ship_id);
	console.log(ship_name);
	console.log(ship_id);
}

function input_character_name_click(){
	var character_name = $('#input_character_name').val();
	//var character_id = nameToID(ship_name, ship_types);
	get_data_character_name(character_name);
	console.log(character_name);
	//console.log(character_id);
}

function handle_character_name_keypress(event){
	var key=event.keyCode || event.which;
	if (key==13){
    input_character_name_click();
	}
}

function handle_ship_type_keypress(event){
	var key=event.keyCode || event.which;
	if (key==13){
    input_ship_type_click();
	}
}

function handle_solar_system_keypress(event){
	var key=event.keyCode || event.which;
	if (key==13){
    input_solar_system_click();
	}
}

function get_kill_details(killmail_id){
	get_data_kill(killmail_id);
	get_items(killmail_id);
}

function highlight_solar_system(solar_system_id){
	console.log(solar_system_id);
	g_highlight_solar_system = solar_system_id;
	draw_map(g_map_data);
}

function get_data_character_name(character_name){
if (window.XMLHttpRequest) {
  // code for modern browsers
  xmlhttp = new XMLHttpRequest();
	} else {
  // code for old IE browsers
  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    var search_data = JSON.parse(this.responseText);
	    draw_table_result(search_data)
	    return search_data;
		}
	};
	xmlhttp.open("GET", "https://www.eve-nerd.com/dev/searchKills_character_name.php?character_name="+character_name, true);
	xmlhttp.send();
}

function get_data_solar_system(solar_system_id){
if (window.XMLHttpRequest) {
  // code for modern browsers
  xmlhttp = new XMLHttpRequest();
	} else {
  // code for old IE browsers
  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    var search_data = JSON.parse(this.responseText);
	    draw_table_result(search_data)
	    return search_data;
		}
	};
	xmlhttp.open("GET", "https://www.eve-nerd.com/dev/searchKills_solar_system.php?solar_system_id="+solar_system_id, true);
	xmlhttp.send();
}

function get_data_ship_type(ship_type_id){
if (window.XMLHttpRequest) {
  // code for modern browsers
  xmlhttp = new XMLHttpRequest();
	} else {
  // code for old IE browsers
  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    var search_data = JSON.parse(this.responseText);
	    draw_table_result(search_data)
	    return search_data;
		}
	};
	xmlhttp.open("GET", "https://www.eve-nerd.com/dev/searchKills_ship_type.php?ship_type_id="+ship_type_id, true);
	xmlhttp.send();
}

function get_data_kill(killmail_id){
	if (window.XMLHttpRequest) {
	  // code for modern browsers
	  xmlhttp = new XMLHttpRequest();
		} else {
	  // code for old IE browsers
	  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    var search_data = JSON.parse(this.responseText);
	    draw_table_kill_detail(search_data)
	    return search_data;
		}
	};
	xmlhttp.open("GET", "https://www.eve-nerd.com/dev/getKillDetails.php?killmail_id="+killmail_id, true);
	xmlhttp.send();
}

function get_items(killmail_id){

	if (window.XMLHttpRequest) {
	  // code for modern browsers
	  xmlhttp = new XMLHttpRequest();
		} else {
	  // code for old IE browsers
	  xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
	    var search_data = JSON.parse(this.responseText);
	    draw_table_kill_items(search_data)
	    return search_data;
		}
	};
	xmlhttp.open("GET", "https://www.eve-nerd.com/dev/getItems.php?killmail_id="+killmail_id, true);
	xmlhttp.send();
}

function draw_table_result(result_array){
	$('#table_result').empty();
	$('#table_result').append(generate_html_table(result_array));
}

function draw_table_kill_detail(result_array){
	$('#table_kill_detail').empty();
	$('#table_kill_detail').append(generate_html_table(result_array));
}

function draw_table_kill_items(result_array){
	$('#table_kill_items').empty();
	$('#table_kill_items').append(generate_html_table(result_array));
}

function generate_html_table(result_array){
	var table_string = '';
	table_string += '<table>';
	table_string += '<tr>';
	var header_row = result_array.shift();
	header_row.forEach(function(element){
		table_string += '<th>';
		table_string += element;
		table_string += '</th>';
	});
	table_string += '</tr>';
	result_array.forEach(function(data_rows){
  	table_string += '<tr>';
  	var solar_system_id = data_rows[0];
  	data_rows.forEach(function(element){
	  	table_string += '<td>';
	  	table_string += '<a onclick="highlight_solar_system('+ solar_system_id +')">' + element + '</a>';
	  	table_string += '</td>';
  	});
  	table_string += '</tr>';
	});
	table_string += '</table>';
	return table_string;
}