<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keyword;
use App\Models\GifproviderKeyword;

class KeywordController extends Controller
{
    public function addKeyword()
    {
        $keywords=[
            [
                "value" => "duck"
            ],
            [
                "value" => "bunny"
            ]
        ];

        Keyword::insert($keywords);
        return "keywords are created successfully!";
    }

    public function resetCounter($keyword)
    {
        $id = Keyword::where('value', $keyword)->first()->id;
        $list = GifproviderKeyword::where('keyword_id', $id)->get();
        foreach ($list as $l)
        {
            $obj = GifproviderKeyword::find($l->id);
            $obj->counter = 0;
            $obj->save();
        }
    }
}
