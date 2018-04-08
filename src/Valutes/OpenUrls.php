<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.04.2018
 * Time: 20:59
 */

namespace Valutes;


class OpenUrls
{
    public function getUrls()
    {
        $sources = [
//            [
//                'url'=>"http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml",
//                'type'=>'xml'
//            ],
            [
                'url'=>"https://www.cbr-xml-daily.ru/daily_json.js",
                'type'=>'json'
            ]
        ];

        return $this->openAndReadUrls($sources);
    }

    public function openAndReadUrls(array $sources)
    {
        $count_ind = count($sources);
        if($count_ind){
            for ($indx = 0; $indx < $count_ind; $indx++ )
            {
                if($ch = curl_init($sources[$indx]['url']))
                {
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//                    'Content-Length: ' . strlen($fields)
                        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                        'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.162 Safari/537.36'
                    ));
//                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                    $text = curl_exec($ch);
                    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    if ($status == 404){
                        continue;
                    }
                }
                if($text){
                    $type_url = $sources[$indx]['type'];
                    $result = [
                        'type' => $type_url,
                        'text' => $text
                    ];
                    return $this->sendToParser($result);
                }

            }
        }else{
            throw new \Exception("URL`s not found");
        }
    }

    public function openAndReadUrls_old(array $sources)
    {
        $count_ind = count($sources);
        for ($indx = 0; $indx < $count_ind; $indx++ )
        {
            $text_url = fopen($sources[$indx]['url'], "rt");
            if($text_url)
            {
                $text = '';
                $type_url = $sources[$indx]['type'];
                while($text_url = fread($text_url,100) ){
                    $text .= $text_url;
                }
                $result = [
                    'type' => $type_url,
                    'text' => $text
                    ];
                return $this->sendToParser($result);
            }

        }
        throw new \Exception("URL`s is over");
    }
    public function sendToParser(array $result)
    {
        if ($result['type'] === 'xml') {
            $res = Parser_Xml::parse($result['text']);
            return $res;
        } elseif ($result['type'] === 'json') {
            $res = Parser_Json::parse($result['text']);
            return $res;
        }
    }

}