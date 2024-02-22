<?php
function missingRoute()
{

    return response(['message' => "Not found", 'code' => 404], 404);

}


function firstWord(string $str): string
{
    return strtok($str, ' ');


}


// return response(['message' => "Not found", 'code' => 404], 404);
