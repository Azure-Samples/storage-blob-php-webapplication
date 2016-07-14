<?php
require_once '../vendor/autoload.php';

use MicrosoftAzure\Storage\Common\ServicesBuilder;

$accountName = ACCOUNT_NAME;
$accountKey = ACCOUNT_KEY;
$connectionString = "DefaultEndpointsProtocol=http;AccountName={$accountName};AccountKey={$accountKey}";

$trialPrefix = 'init-';
$trialContainer = 'container';
$containerName = $trialPrefix . $trialContainer;
const DELIMITER = '/';

$blobClient = ServicesBuilder::getInstance()->createBlobService($connectionString);