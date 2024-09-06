<?php
/**
 * Here is your custom functions.
 */
function lunarToSolar($lunarDate) {
    $formatter = new IntlDateFormatter(
        'zh_CN@calendar=chinese',
        IntlDateFormatter::FULL,
        IntlDateFormatter::NONE,
        'Asia/Shanghai',
        IntlDateFormatter::TRADITIONAL
    );
    $lunarDateTime = DateTime::createFromFormat('md', $lunarDate);
    $lunarYear = (new DateTime())->format('Y');
    $lunarStr = "{$lunarYear}-{$lunarDateTime->format('m-d')}";
    $timestamp = $formatter->parse($lunarStr);

    $solarDate = DateTime::createFromFormat('U', $timestamp / 1000);
    if ($solarDate < new DateTime()) {
        $lunarYear++;
        $lunarStr = "{$lunarYear}-{$lunarDateTime->format('m-d')}";
        $timestamp = $formatter->parse($lunarStr);
        $solarDate = DateTime::createFromFormat('U', $timestamp / 1000);
    }

    return $solarDate;
}
