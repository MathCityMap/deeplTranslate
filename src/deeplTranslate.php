<?php

namespace Autentek\DeeplTranslator;
use stdClass;

class deeplTranslate
{
    public function __construct(private readonly string $apiKey, private readonly string $rootUrl = "https://api-free.deepl.com/")
    {
    }

    public function translateTexts(array $texts, string $targetLang, string|null $sourceLang = null): stdClass
    {
        $data = array('text' => $texts, 'source_lang' => $sourceLang, 'target_lang' => $targetLang);
        $data = json_encode($data);
        return $this->requestDeepl($data);
    }

    public function requestDeepl(string $data): stdClass
    {
        $context_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Authorization: DeepL-Auth-Key " . $this->apiKey . "\r\n"
                    . "Content-type: application/json\r\n",
                'content' => $data
            )
        );

        $context = stream_context_create($context_options);
        $resp = file_get_contents($this->rootUrl . "v2/translate", false, $context);
        if ($resp === false) {
           $headers = $this->parseHeaders($http_response_header);
           $resp = ["error" => true, "response_code" => $headers["response_code"]];
           return $resp;
        }
        return json_decode($resp);
    }

    private function parseHeaders( $headers )
    {
        $head = array();
        foreach( $headers as $k=>$v )
        {
            $t = explode( ':', $v, 2 );
            if( isset( $t[1] ) )
                $head[ trim($t[0]) ] = trim( $t[1] );
            else
            {
                $head[] = $v;
                if( preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out ) )
                    $head['reponse_code'] = intval($out[1]);
            }
        }
        return $head;
    }
}
