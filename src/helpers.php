<?php

//Converting characters into entities are often 
//used to prevent browsers from using it as an HTML element
function e(string $string)
{
    return htmlentities($string);
}
