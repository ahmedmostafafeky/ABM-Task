<?php

function readSchedule($filename) {
    $schedule = [];
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($date, $arrival, $departure) = array_pad(explode(',', $line), 3, null);
        $schedule[$date] = ['arrival' => $arrival, 'departure' => $departure];
    }
    return $schedule;
}

function readLogs($filename) {
    $logs = [];
    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($date, $time) = explode(' ', $line);
        if (!isset($logs[$date])) {
            $logs[$date] = [];
        }
        $logs[$date][] = $time;
    }
    return $logs;
}

function processAttendance($schedule, $logs) {
    $results = [];
    foreach ($schedule as $date => $times) {
        if (empty($times['arrival']) && empty($times['departure'])) {
            $results[$date] = 'Weekend/Holiday';
            continue;
        }
        
        $arrivalLogs = [];
        $departureLogs = [];

        if (isset($logs[$date])) {
            foreach ($logs[$date] as $log) {
                if (strcmp($log, '12:00') < 0) {
                    $arrivalLogs[] = $log;
                } else {
                    $departureLogs[] = $log;
                }
            }
        }

        $earliestArrival = !empty($arrivalLogs) ? min($arrivalLogs) : null;
        $latestDeparture = !empty($departureLogs) ? max($departureLogs) : null;

        $expectedArrival = $times['arrival'];
        $expectedDeparture = $times['departure'];

        if ($earliestArrival && $latestDeparture) {
            $results[$date] = "$earliestArrival, $latestDeparture";
        }  else if ($earliestArrival === null && $latestDeparture === null ) {
            $results[$date] = "Absence";
        } else if ($latestDeparture === null ) {
            $latestDeparture = 'n/a';
            $results[$date] = "$earliestArrival, $latestDeparture";
        } else if ($earliestArrival === null ){
            $earliestArrival = 'n/a';
            $results[$date] = "$earliestArrival, $latestDeparture";
        }
    }
    return $results;
}

function writeResults($filename, $results) {
    $output = fopen($filename, 'w');
    foreach ($results as $date => $result) {
        fwrite($output, "$date: $result" . PHP_EOL);
    }
    fclose($output);
}

$scheduleFile = 'schedule.txt';
$logsFile = 'logs.txt';
$outputFile = 'output.txt';

$schedule = readSchedule($scheduleFile);
$logs = readLogs($logsFile);
$results = processAttendance($schedule, $logs);
writeResults($outputFile, $results);
