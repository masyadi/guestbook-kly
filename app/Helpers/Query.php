<?php
namespace App\Helpers;

Trait Query
{
    static function getBlog($isOrder=true)
    {
        $q = \App\Models\Blog::with('relcategory');

        if( request('preview') )
        {
            $q->whereRaw("md5(CONCAT(id,'-',created_at))='".request('preview')."'");
        }
        else
        {
            $q->where('status', '1')
              ->where('publish_date', '<=', date("Y-m-d H:i:s"));
        }

        if( $isOrder )
        {
            $q->orderBy('publish_date', 'DESC');
        }

        return $q;
    }

    static function getPage()
    {
        $q = \App\Models\Page::where('status', '1');

        return $q;
    }

    static function getBanner()
    {
        $q = \App\Models\Banner::where('status', '1');

        return $q;
    }

    static function getQuote()
    {
        $q = \App\Models\Quote::where('status', '1')
            ->where('publish_at', '<=', date("Y-m-d H:i:s"));

        return $q;
    }

    static function getCategory()
    {
        $q = \App\Models\Tag::where('status', '1')->where('type', 'BLOG_CATEGORY');

        return $q;
    }
}