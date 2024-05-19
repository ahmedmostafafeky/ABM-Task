<?php

function readFromFile($filename) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return $lines;
}

function writeToFile($filename, $lines) {
    $output = fopen($filename, 'w');
    foreach ($lines as $line) {
        fwrite($output, $line . PHP_EOL);
    }
    fclose($output);
}

function generateTriangularFrame($lines) {
    $framedLines = [];
    $longestStringLength = max(array_map('strlen', $lines));
    $triangleWidth = ($longestStringLength + count($lines) + 1); 

    $topPartHeight = $longestStringLength + 4 ; 
    for ($i = 1; $i <= $topPartHeight; $i+=2) {
        $stars = str_repeat('*', $i);
        $paddingCount = $triangleWidth - strlen($stars) / 2;

        $padding = str_repeat(' ', ($paddingCount > 0 )? $paddingCount : 0);
        $framedLines[] = $padding . $stars . $padding;
    }

    $x = $paddingCount - 1;
    $s = 1;
    
    foreach ($lines as $index => $line) {
        $stars = str_repeat('*', $s);
        $s++;
        $paddedLine = "* " . $line . str_repeat(' ', $longestStringLength - strlen($line)  )  . " *";
        
        $padding = str_repeat(' ', $x);
        $x--;
        $framedLines[] = $padding .$stars.  $paddedLine .  $stars . $padding ;
    }

    return $framedLines;
}

$inputFilename = 'input.txt';
$outputFilename = 'output.txt';

$lines = readFromFile($inputFilename);
$framedLines = generateTriangularFrame($lines);
writeToFile($outputFilename, $framedLines);

?>


