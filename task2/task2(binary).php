<?php

function getOdd($A) {
    $result = "";
    for($i = 0; $i < strlen($A); $i = $i + 2) $result .= $A[$i];
    return $result;
}

function getEven($A) {
    $result = "";
    for($i = 1; $i < strlen($A); $i = $i + 2) $result .= $A[$i];
    return $result;
}

function alternatingSubsequence($A, $B) {

    //even subSequence 
    $evenString = getEven($A);
    $evenResulet = str_contains($evenString,$B);
    

    //odd subSequence
    $oddString = getOdd($A);
    $oddResulet = str_contains($oddString,$B);

    return $evenResulet || $oddResulet;

}



$input_file = "input.txt";
$output_file = "output.txt";

$handle_input = fopen($input_file, "r");
$handle_output = fopen($output_file, "w");



if ($handle_input && $handle_output) {
    while (($line = fgets($handle_input)) !== false) {
        $line = trim($line);
        list($A, $B) = explode(" ", $line);

        $result = alternatingSubsequence($A, $B) ;
        fwrite($handle_output, $result ? "1\n" :"0\n");
    }
    fclose($handle_input);
    fclose($handle_output);
} else {
    echo "Error opening files.";
}