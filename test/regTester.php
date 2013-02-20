<?php
preg_match_all("/@[a-zA-Z0-9]+/",
    "dd dd@abc dd @dd @",
    $out, PREG_PATTERN_ORDER);
echo sizeof($out[0]);
echo $out[0][0] . ", " . $out[0][1] . "\n";
echo $out[1][0] . ", " . $out[1][1] . "\n";
?>