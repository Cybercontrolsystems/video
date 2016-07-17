<?php
# Append an item into an array in the database

$mongo = new MongoClient();
$collection = $mongo->video->default;

# POST parameters text=value;

$key = $_POST['key'];
$value = $_POST['value'];

# Append provided value to the named array.  If called with key=colours value=pink it will append 'pink' to the 'colors' array.

$selection = array( $key => array ( '$exists' => 'true'));
$action = array ( '$push' => array ( $key => $value) );
$collection->update( $selection, $action );

?>
OK
