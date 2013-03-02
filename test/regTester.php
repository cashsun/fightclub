<?php
preg_match_all("/@\w*/",
    "@cashsun @plutoless @testuser",
    $out, PREG_PATTERN_ORDER);
$atstr = implode(",", $out[0]);
            $atstr = str_replace("@", "", $atstr);
            echo $atstr;
?>