<?php
# get some data
# Turns the provided value from key-value into { value : { $exists: true } } and returns the 
# complete row

$mongo = new MongoClient();
$collection = $mongo->video->default;

# POST parameters text=value;

$key = $_GET['key'];

$find = array( $key => array( '$exists' => true) );
$cursor = $collection->find($find);
if ($cursor->hasNext()) {
	$document = $cursor->getNext();
	echo( json_encode( $document ) );
}
else
echo "Not FOUND";

?>
