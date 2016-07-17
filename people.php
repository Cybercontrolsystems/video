<?php
# Page to show and edit participants
# 2013-11-13 0.0 Initial version

# Open MondoDB connection
$mongo = new MongoClient();
$collection = $mongo->video->default;

# Get pulldown items
function getpulldown($name) {
	global $collection;
	$cursor = $collection->find( array ( $name => array ('$exists' => 'true') ) );
	// Only want one row
	if ($cursor->hasNext()) {
		$document = $cursor->getNext();
		foreach($document[$name] as $value)
			$values[] = $value;
		return $values;
	}
	else return array();
}
?>
<html>
<head><title>People Page</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript">
"use strict";
// This adds an item to the named array for example addvalue('colourselect', 'colours', 'red') and appends it to the selection
function addValue(selectName, arrayName, id) {
	var text = $('#' + id).val();
	// Send by HTTPRequest
	var http = new XMLHttpRequest();
	http.open("POST", "addpulldown.php", false);	 //Wait for it ...
	http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	http.send("key=" + arrayName + "&value=" + text);
	if (http.status != 200)
		alert(http.responseText);
	$('#' + selectName).append( $('<option></option>').val('fred').html('Fred') );
}

// Add another selection box
function makeSelection(type, number) {
	var id = type + number;
	var text = '<div class="' + type + '" id="' + id + '">' + 
	 '<select>' +
	  '<option value="s-one">S-One</option>' +
	  '<option value="s-two">S-Two</option>' +
	 '</select><br/>' +
	 '<input type="text" size="10" value="' + type + '"/><br/>' +
	 '<input type="button" value="+"/>' +
	'</div><!--end-->';
	return text;
}

function addGarment() {
	var text = makeSelection('colour', 1);
	alert(text);
	$('#garment1').after(text);
	$('#colour1').after( makeSelection('material', 1));
	$('#material1').after( makeSelection('item', 1));
}

function getData(data) {
	var http = new XMLHttpRequest();
	http.open("GET", "get.php?key=" + data, false);
	http.send();
	if (http.readyState == 4 && http.status == 200) {
		// alert (http.responseText);
		var colours = eval ('(' + http.responseText + ')');
		// At this point colours = { colours : [ "red", "black"] }
		// alert( colours.colour[0] + " " + colours.colour[1]);
		
	}
	else
		alert("ERROR " + http.status + " " + http.responseText);
	$(".colour").empty();
	for (var c in colours.colour)
		$(".colour").append( $("<option></option>").val(colours.colour[c]).html(colours.colour[c]));
}
</script>
</head>
<body>
<input type="button" value="Get Colours" onclick="getData('colour')"/>
<br>Colour:
<select id="colourselect" class="colour">
<?php
$colours = getpulldown('colour');
foreach( getpulldown('colour') as $colour) 
	echo "<option value=\"$colour\">$colour XXX</option>\n";
?>
</select>
<form method="post" action="addpulldown.phpPPP">
<input id ="colourtext" type="text" name="value"/>
<input type="hidden" name="key" value="colours"/>
<input type="button" value="+" onclick="addValue('colourselect', 'colours', 'colourtext')"/>
</form>
<hr>
<div class="video" id ="video0">
Video
<div class="episode" id ="episode0">
Episode
<div class="participant" id ="particpant0">
Participant
<div class="outfit" id ="outfit0">
Outfit
<div class="garment" id ="garment0" >
&nbsp;<span id="garment1" style="float:left">GARMENT</span>
<input type="button" value="ADD" onclick="addGarment()"/>
<br style="clear:both"/>
</div><!--end garment-->
</div><!--end outfit-->
</div><!--end participant-->
</div><!--end episode-->
</div><!--end video-->
</body>
</html>

