<?php
preg_match_all("/@[a-zA-Z0-9.]*/",
    "@cashsun @plutoless @test.user",
    $out, PREG_PATTERN_ORDER);
$atstr = implode(",", $out[0]);
            $atstr = str_replace("@", "", $atstr);
            echo $atstr;
?>