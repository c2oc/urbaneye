<?php
function costCalculator(): int
{
    $timeTarget = 0.05; // 50 milliseconds

    $cost = 8;
    do {
        $cost++;
        $start = microtime(true);
        password_hash("test", PASSWORD_DEFAULT, ["cost" => $cost]);
        $end = microtime(true);
    } while (($end - $start) < $timeTarget);
    return $cost;
}
function isNull($n){
    if ($n !== null){
        return $n;
    }
    return 0;
}