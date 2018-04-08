<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 06.04.2018
 * Time: 22:11
 */

namespace Valutes;


class Parser_Xml
{
    public static function parse($text)
    {
        $root = new \SimpleXMLElement($text);
        $date = strval($root->Cube->Cube->attributes()->time);
        $valutes = [];
        /** @var \SimpleXMLElement $cube */
        foreach ($root->Cube->Cube->children() as $cube) {
            $valute =
                [
                    "currency" => (string)$cube->attributes()->currency,
                    "rate" => (float)$cube->attributes()->rate,
                ];
            if ($valute['currency'] == "RUB") {
                $val_rub = round($valute['rate'],2);
            }
            $valutes[] = $valute;
        }
        if(isset($val_rub)){
            foreach ($valutes as  &$valute) //приводим все валюты по отношению 1 у.е. = rub
            {
                if ($valute['currency'] !== "RUB") {
                    $cur_to_rub = $val_rub / $valute['rate'];
                    $valute['rate'] = round($cur_to_rub, 2);
                } else {
                    $valute['currency'] = "EUR";
                }
            }
            unset($valute);
        }

        $result =
            [
                'date' => $date,
                'nominal' => 1,
                'valutes' => $valutes,
            ];

        return $result;

    }
}