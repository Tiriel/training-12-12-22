<?php

namespace App;

class MyserviceFactory
{
    public static function createService()
    {
        return new MyService();
    }
}