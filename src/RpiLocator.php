#!/usr/bin/env php
<?php

declare(strict_types=1);

class RpiLocator
{
    private const DATA_PATH = '/data/data.json';

    private stdClass $data;

    public function __construct()
    {
        echo 'Checking for PI availability!' . PHP_EOL;
        $this->data = json_decode(@file_get_contents(self::DATA_PATH) ?: '{}');
    }

    public function run(): void
    {
        $path = sprintf('https://rpilocator.com/feed/?cat=%s', urlencode($_ENV['PI_CATEGORY']));
        $rpilocator = simplexml_load_string(file_get_contents($path));

        foreach ($rpilocator->xpath('//item') as $item) {
            if (in_array($item->guid, $this->data->seenBefore ?? [])) {
                break;
            }
            $this->data->seenBefore[] = (string)$item->guid;

            $this->whatsApp(sprintf(
                "%s\n%s\nPublished at %s",
                $item->title,
                $item->link,
                $this->localDateTime((string)$item->pubDate),
            ));

        }
    }

    private function whatsApp(string $message): void
    {
        file_get_contents(sprintf(
            'https://api.callmebot.com/whatsapp.php?phone=%d&text=%s&apikey=%d',
            urlencode($_ENV['WHATSAPP_PHONE']),
            urlencode($message),
            urlencode($_ENV['WHATSAPP_API_KEY']),
        ));
    }

    public function __destruct()
    {
        file_put_contents(self::DATA_PATH, json_encode($this->data));
    }

    private function localDateTime(string $dateTime): string
    {
        $dateTimeImmutable = new DateTimeImmutable($dateTime);
        $dateTimeImmutable->setTimezone(new DateTimeZone(date_default_timezone_get()));
        $formatter = new IntlDateFormatter(locale_get_default(), IntlDateFormatter::SHORT, IntlDateFormatter::SHORT);

        return $formatter->format($dateTimeImmutable);
    }
}

(new RpiLocator)->run();
