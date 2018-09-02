<?php

require_once 'vendor/autoload.php';

try {
    if (!isset($argv[1])) {
        throw new Exception('Не указано имя файла');
    }

    $fileName = $argv[1];
    if (!file_exists($fileName)) {
        throw new Exception("Файл \"$fileName\" не найден");
    }

    $fileContents = file_get_contents($fileName);
    if ($fileContents === false) {
        throw new Exception("Ошибка при чтении файла \"$fileName\"");
    }

    $accessLogParser = new AccessLogParser;
    echo json_encode($accessLogParser->getStatistics($fileContents)) . PHP_EOL;
} catch (Exception $exception) {
    echo "Ошибка! {$exception->getMessage()}" . PHP_EOL;
}
