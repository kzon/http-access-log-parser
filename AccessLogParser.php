<?php

use Jaybizzle\CrawlerDetect\CrawlerDetect;

class AccessLogParser
{

    /**
     * Анализирует лог и возвращает статистику по нему
     * @param string $log
     * @return array
     */
    public function getStatistics(string $log): array {
        $lines = explode(PHP_EOL, $log);

        $result = [
            'views'       => 0,
            'urls'        => 0,
            'traffic'     => 0,
            'crawlers'    => [],
            'statusCodes' => [],
        ];

        $urls = [];
        $lineParts = [];
        $crawlerDetect = new CrawlerDetect;

        foreach ($lines as $line) {
            preg_match(
                '/"\w+ (?<url>[\S]+).+" (?<statusCode>\d+) (?<contentLength>\d*)(.+".*" "(?<userAgent>.*)")?/',
                $line,
                $lineParts
            );

            if (!empty($lineParts['url'])) {
                $result['views']++;

                if (!array_key_exists($lineParts['url'], $urls)) {
                    $urls[$lineParts['url']] = true;
                    $result['urls']++;
                }
            }

            if (!empty($lineParts['statusCode'])) {
                $result['statusCodes'][$lineParts['statusCode']]++;
            }

            if (!empty($lineParts['contentLength'])) {
                $result['traffic'] += $lineParts['contentLength'];
            }

            if (!empty($lineParts['userAgent']) && $crawlerDetect->isCrawler($lineParts['userAgent'])) {
                $crawler = $crawlerDetect->getMatches();
                $result["crawlers"][$crawler]++;
            }
        }

        return $result;
    }
}