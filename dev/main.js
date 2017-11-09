function input_solar_system_click(){
	get_data_solar_system(SSnameToID($('#input_solar_system').val()));
	console.log($('#input_solar_system').val());
	console.log(SSnameToID($('#input_solar_system').val()));
}

function input_ship_type_click(){
	get_data_ship_type(STnameToID($('#input_ship_type').val()));
	console.log($('#input_ship_type').val());
	console.log(STnameToID($('#input_ship_type').val()));
}

function get_kill_details(killmail_id){
	get_data_kill(killmail_id);
}

var list_solar_systems = [];
solarSystems.forEach(function(ssObj){
	list_solar_systems.push(ssObj.N);
});
var list_ship_type = [];
ship_type.forEach(function(stObj){
	list_ship_type.push(stObj.N);
});

function SSnameToID(solar_system_name){
	var solar_system_id = 0;
	for(var i=0;i<solarSystems.length;i++){
		if(solarSystems[i].N.toLowerCase()==solar_system_name.toLowerCase()){
			solar_system_id = solarSystems[i].I;
			break;
		}
	}
	return solar_system_id;
}

function STnameToID(ship_type_name){
	var ship_type_id = 0;
	for(var i=0;i<ship_type.length;i++){
		if(ship_type[i].N.toLowerCase()==ship_type_name.toLowerCase()){
			solar_system_id = ship_type[i].I;
			break;
		}
	}
	return solar_system_id;
}

$( function() {
  $( "#input_solar_system" ).autocomplete({
    source: function( request, response ) {
        var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( list_solar_systems, function( item ){
          if(request.term.length>1){
            return matcher.test( item );
          }
        }) );
    }
  });
  $( "#input_ship_type" ).autocomplete({
    source: function( request, response ) {
        var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( list_ship_type, function( item ){
          if(request.term.length>1){
            return matcher.test( item );
          }
        }) );
    }
  });
} );

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
  	var killmail_id = data_rows[0];
  	data_rows.forEach(function(element){
	  	table_string += '<td>';
	  	table_string += '<a onclick="get_kill_details('+ killmail_id +')">' + element + '</a>';
	  	table_string += '</td>';
  	});
  	table_string += '</tr>';
	});
	table_string += '</table>';
	return table_string;
}

function draw_table_result(result_array){
	$('#table_result').empty();
	$('#table_result').append(generate_html_table(result_array));
}

function draw_table_kill_detail(result_array){
	$('#table_kill_detail').empty();
	$('#table_kill_detail').append(generate_html_table(result_array));
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
	xmlhttp.open("GET", "https://www.eve-nerd.com/dev/searchKills.php?solar_system_id="+solar_system_id, true);
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
	xmlhttp.open("GET", "https://www.eve-nerd.com/dev/searchKills_ship_type.php?ship_type_id="+solar_system_id, true);
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