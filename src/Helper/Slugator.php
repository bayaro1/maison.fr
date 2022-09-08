<?php
namespace App\Helper;

class Slugator
{
    public static function slugify(string $text):string
    {
        return strtolower(str_replace(' ', '-', $text));
    }
}