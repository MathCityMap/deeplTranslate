<?php
class deeplTranslate {
    private string $rootUrl = "https://api-free.deepl.com/";
    public function __construct(private string $apiKey) {}

    public function translateTexts(array $texts, string $targetLang, string|null $sourceLang = null): stdClass {
        $data = array ('text' => $texts, 'source_lang' => $sourceLang, 'target_lang' => $targetLang);
        $data = json_encode($data);
        return $this->requestDeepl($data);
    }

    public function requestDeepl(string $data): stdClass {
        $context_options = array (
            'http' => array (
                'method' => 'POST',
                'header'=> "Authorization: DeepL-Auth-Key ".$this->apiKey."\r\n"
                    . "Content-type: application/json\r\n",
                'content' => $data
            )
        );

        $context = stream_context_create($context_options);
        $resp = file_get_contents($this->rootUrl."v2/translate", false, $context);
        return json_decode($resp);
    }
}
