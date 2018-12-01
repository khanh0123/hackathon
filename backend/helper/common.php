<?php 

function get_content_web($url)
{
	try {
		return file_get_contents($url);
	} catch (Exception $e) {
		return null;
	}
}
function innerHTML($node) {
    return implode(array_map([$node->ownerDocument,"saveHTML"], 
                             iterator_to_array($node->childNodes)));
}