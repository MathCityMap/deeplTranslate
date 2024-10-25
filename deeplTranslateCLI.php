<?php

$params = array();
for ($i = 1; $i < count($argv); $i++) {
    list($key, $val) = explode("=", $argv[$i]);
    $params[$key] = $val;
}

if ((!isset($params['text']) && !isset($params['file']))|| !isset($params['to']) || !isset($params['apiKey'])) {
    //necessary params missing
    echo "required params missing, required params are: \n";
    echo "text (text to be translated) || file (path to file containing json of texts)\n";
    echo "to (target language code e.g. de)\n";
    echo "apiKey (key to use Deepl API with)\n";
    exit(1);
}

require_once "deeplTranslate.php";

$translator = new DeeplTranslate($params["apiKey"]);
$texts = [];

if (isset($params['text'])) {
    $texts = [$params['text']];
} else {
    $texts = json_decode(file_get_contents($params["file"]));
}

echo json_encode($translator->translateTexts($texts, $params["to"], $params["from"] ?? null), JSON_UNESCAPED_UNICODE)."\n";
exit(0);
