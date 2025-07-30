<?php

require_once('library/Telegram.php');
$bot_token = getenv("BOT_TOKEN");
$siteUrl = "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

$telegram = new Telegram($bot_token, true);

$webhook = $telegram->setWebhook(str_replace('webhook.php', '', $siteUrl));
echo str_replace('webhook.php', '', $siteUrl);
var_dump($webhook);
