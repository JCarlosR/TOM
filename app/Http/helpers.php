<?php

function firstLink($string)
{
    $regex = '/https?\:\/\/[^\" \n]+/i'; // end with spaces or line-breaks
    $found = preg_match($regex, $string, $matches);
    if ($found)
        return $matches[0];

    return null;
}