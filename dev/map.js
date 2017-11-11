// eve-nerd.com
// Demo code
// Authors: SmallFatCat, Kenkyuu
// GitHub Repo: https://github.com/smallfatcat/eve-nerd
// Date: 9th November 2017
// Licence: MIT

var region_id = 10000001;

var g_map_data = {};
var g_raw_map_data = [];
var g_jump_data_array = [];
var g_highlight_solar_system = 0;
var g_highlight_index = 0;
var canvas;
var ctx;


// Document Ready
$( function() {
	get_map_data(region_id);
	//$('#map_canvas').onclick(update_map_data);
	canvas = document.getElementById("map_canvas");
	ctx = canvas.getContext("2d");

	ctx.canvas.addEventListener('click', function(event){
  	update_map_data(event.clientX-ctx.canvas.offsetLeft, event.clientY-ctx.canvas.offsetTop);
  });
} );

function update_map_data(x,y){
	g_map_data[g_highlight_solar_system].x_view = x;
	g_map_data[g_highlight_solar_system].y_view = y;
	//console.log(g_map_data[highlight_index]);
	draw_map(g_map_data);
	draw_table_result(g_map_data);
}

function save_map_data(raw_map_data){
	process_raw_map_data(raw_map_data);
	scale_xy();
	draw_map(g_map_data);
	draw_table_result(g_map_data);
}

function process_raw_map_data(raw_map_data){
	
	g_raw_map_data = raw_map_data;

	var map_data = {};
	raw_map_data.forEach(function(rm){
		map_data[ rm[0] ] = {
			'x': rm[6],
			'y': rm[7],
			'z': rm[8],
			'name': rm[1],
			'id': rm[0],
			'x_view': rm[9],
			'y_view': rm[10]
		};
	});
	g_map_data = map_data;
	
	// Create jump array
	var jump_data_array = [];
	raw_map_data.forEach(function(ss){
		var from = ss[0];
		ss[11].forEach(function(to){
			var jump = {
				'from': from,
				'to':  to[0]
			};
			jump_data_array.push(jump);
		});
	});
	g_jump_data_array = jump_data_array;
	
	
}

function make_ss_view_array(map_data){
	var ss_view_array = [];
	for(var ss in map_data){
		var ss_view = {
			'x': map_data[ss].x_view,
			'y': map_data[ss].y_view,
			'name': map_data[ss].name,
			'id': map_data[ss].id
		};
		ss_view_array.push(ss_view);
	}
	return ss_view_array;
}

/*function set_highlight_index(){
	var highlight_index = 0;
	for(var i=1;i<g_map_data.length;i++){
		if(g_highlight_solar_system == g_map_data[i][0]){
			highlight_index = i;
			break;
		}
	}
	g_highlight_index = highlight_index;
}*/

function draw_map(map_data){
  var ss_view_array = make_ss_view_array(g_map_data);
  ctx.font="12px Cuprum";
  
  // Clear
  ctx.fillStyle='rgb(255,255,255)';
  ctx.fillRect(0, 0, canvas.width, canvas.height);
  //var headings = map_data.shift();
  ss_view_array.forEach(function(solar_systems){
  	var x = solar_systems.x;
  	var y = solar_systems.y;
  	var name = solar_systems.name;
  	//console.log('solar_system_name: ' + name + ' x: ' + x + ' y: ' + y + ' z: ' + z);
  	if(g_highlight_solar_system == solar_systems.id){
  		ctx.fillStyle = 'rgb(255, 64, 64)';
  	}else{
  		ctx.fillStyle = 'rgb(64, 255, 64)';
  	}
  	ctx.fillRect(x, y, 5, 5);
  	ctx.fillStyle='rgb(0, 0, 0)';
  	ctx.fillText(name, x - ctx.measureText(name).width/2, y);
  });
  //map_data.unshift(headings);
}

function scale_xy(){
	var scale_factor = 1/10e16*500;
	for(ss in g_map_data){
		var x = Math.round(g_map_data[ss].x*scale_factor+600);
		var z = Math.round(g_map_data[ss].z*scale_factor+600);
		g_map_data[ss].x_view = x;
		g_map_data[ss].y_view = z;
	}
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


function highlight_solar_system(solar_system_id){
	console.log(solar_system_id);
	g_highlight_solar_system = solar_system_id;
	//set_highlight_index();
	draw_map(g_map_data);
}

function draw_table_result(result_array){
	$('#table_result').empty();
	$('#table_result').append(generate_html_table(result_array));
}

function generate_html_table(result_array){
	var table_string = '';
	table_string += '<table>';
	table_string += '<tr>';
	var header_row = ['solar_system_id','solar_system_name','constellation_id','constellation_name','region_id','region_name','x','y','z','render_x','render_y'];
	for(i in result_array){
		for(element in result_array[i]){
			table_string += '<th>';
			table_string += element;
			table_string += '</th>';
		}
		break;
	}
	table_string += '</tr>';
	for(data_rows in result_array){
		table_string += '<tr>';
  	var solar_system_id = result_array[data_rows].id;
  	for(element in result_array[data_rows]){
  		table_string += '<td>';
	  	table_string += '<a onclick="highlight_solar_system('+ solar_system_id +')">' + result_array[data_rows][element] + '</a>';
	  	table_string += '</td>';
  	}
  	table_string += '</tr>';
	}
	table_string += '</table>';
	return table_string;
}