<?php

function firstLink($string)
{
    $regex = '/https?\:\/\/[^\" ]+/i';
    $found = preg_match($regex, $string, $matches);
    if ($found)
        return $matches[0];

    return null;
}