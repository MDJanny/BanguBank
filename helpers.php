<?php

function getInitialsFromName($name)
{
    $initials = '';
    $name = strtoupper($name);

    // Remove prefixes from the name
    $remove = ['.', 'MR', 'MRS', 'MISS', 'DR', 'MD'];
    $nameWithoutPrefix = str_replace($remove, " ", $name);

    $words = explode(" ", $nameWithoutPrefix);

    if (count($words) == 1)
        $initials = $words[0][0];
    else
        $initials = $words[0][0] . $words[count($words) - 1][0];

    return $initials;
}