<?php

if (!function_exists('convertArrayToString')) {
    function convertArrayToString(Array $string)
    {
        return implode(', ', $string);
    }
}
