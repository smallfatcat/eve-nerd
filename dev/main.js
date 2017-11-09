var list_solar_systems = make_autocomplete_list(solarSystems);
var list_ship_types = make_autocomplete_list(ship_types);;

// Document Ready
$( function() {
  attach_autocomplete('#input_solar_system', list_solar_systems);
  attach_autocomplete('#input_ship_type', list_ship_types);
} );

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

function get_kill_details(killmail_id){
	get_data_kill(killmail_id);
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

function draw_table_result(result_array){
	$('#table_result').empty();
	$('#table_result').append(generate_html_table(result_array));
}

function draw_table_kill_detail(result_array){
	$('#table_kill_detail').empty();
	$('#table_kill_detail').append(generate_html_table(result_array));
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