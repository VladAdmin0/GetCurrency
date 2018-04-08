<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 07.04.2018
 * Time: 18:06
 */

namespace Valutes;


class Parser_Json
{
    public static function parse($text)
    {
        $json_text = json_decode($text, true);
        $date = $json_text['Date'];
        $valutes = [];
        foreach ($json_text['Valute'] as $valute => $v) {
            if (intval($v['Nominal']) > 1){
                $v['Value'] = (float)$v['Value']/$v['Nominal'];
                $v['Nominal'] = 1;
            }
            $val = [
                'currency' => (string)$v['CharCode'],
                'nominal' => intval($v['Nominal']),
                'rate' => round((float)$v['Value'],2)
            ];
            $valutes[] = $val;
        }
        $result = [
            'date' => $date,
            'nominal' => 1,
            'valutes' => $valutes,
        ];

        return $result;
    }
}