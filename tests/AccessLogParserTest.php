<?php

use PHPUnit\Framework\TestCase;

final class AccessLogParserTest extends TestCase
{
    public function testStatistics() {
        $inputCasesDirectory = dir('tests/cases/input');
        $accessLogParser = new AccessLogParser;

        while ($caseFileName = $inputCasesDirectory->read()) {
            if ($caseFileName != '.' && $caseFileName != '..') {
                $input = file_get_contents("tests/cases/input/$caseFileName");
                $result = $accessLogParser->getStatistics($input);
                $this->assertJsonStringEqualsJsonFile(
                    "tests/cases/output/$caseFileName",
                    json_encode($result),
                    "$caseFileName failed"
                );
            }
        }

        $inputCasesDirectory->close();
    }
}