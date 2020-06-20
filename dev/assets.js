window.onload = function() {
	axios
	.get('http://eve-nerd.com/dev/getAssetJson.php')
	.then(response => {
	  getAssetdata(response.data);
	})
	.catch(error => console.error(error));

};

var g_asset_data;


function getAssetdata(data){
	console.log(data);
	g_asset_data = data;
}

/*
characterIDS.includes(attacker.character_id)

damageDone.sort(compareDamage)

function compareDamage(a, b) {
  return b.damage_done - a.damage_done;
}

var fittedValue = ZKILLdata.find(function(k){return k.killmail_id == kill.killmail_id}).zkb.fittedValue;

document.getElementById('assetTable').innerHTML = tableData;
*/
