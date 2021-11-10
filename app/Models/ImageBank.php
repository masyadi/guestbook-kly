<?php

namespace App\Models;

use App\Models\BaseModel;

class ImageBank extends BaseModel
{
    static function position($id=0)
    {
        $ret = [];
        
        if($row = ImageBank::where('id', $id)->first())
        {
            $ret[] = [
                'id' => $row->id,
                'title' => $row->title,
            ];
            
            $ret = array_merge($ret, self::position($row->parent));
        }
        
        
        return $ret;
    }
    
    static function child($id)
    {
        $ret = [];
        
        if($row = ImageBank::where('parent', $id)->get())
        {
            if( $row )
            {
                foreach( $row as $r)
                {
                    $ret[] = $r->id;
                    
                    $ret = array_merge($ret, self::child($r->id));
                }    
            }
        }
                
        return $ret;
    }
}