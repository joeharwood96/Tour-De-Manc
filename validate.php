<?php

function sanitise($str, $connection)
{
	if (get_magic_quotes_gpc())
	{
		$str = stripslashes($str);
	}
	$str = mysqli_real_escape_string($connection, $str);	// escape any dangerous characters
	$str = htmlentities($str);	// ensure any html code is safe by converting reserved characters to entities
	return $str;
}

function validateString($field, $minlength, $maxlength) 
{
    if (strlen($field)<$minlength) 
    {
        return "Minimum length: " . $minlength; //string too short
    }
	elseif (strlen($field)>$maxlength) 
    { 
        return "Maximum length: " . $maxlength; //string too large
    }
    return ""; //if returned data is empty then string is assumed valid
}

function validateInt($field, $min, $max) 
{ 
	$options = array("options" => array("min_range"=>$min,"max_range"=>$max));
    
	if (!filter_var($field, FILTER_VALIDATE_INT, $options)) 
    { 
        return "Not a valid number (must be whole and in the range: " . $min . " to " . $max . ")"; 
    }
    return ""; //if returned data is empty then string is assumed valid
}

?>