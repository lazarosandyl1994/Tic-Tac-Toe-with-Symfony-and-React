<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Request;

trait TranslateRequest
{

    public function getAttribute(Request $request, string $attribute)
    {
        if (json_decode($request->getContent())) {
            return json_decode($request->getContent())->{$attribute};
        } else {
            return $request->request->all()[$attribute];
        }
    }
}