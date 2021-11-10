<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $guarded = ['created_at', 'id'];

    /**
     * GET table name from model with prefix
     */
    static function table($prefix=true)
    {
        return ($prefix ? \DB::getTablePrefix() : '') . 
               (
                   self::query()->getModel()->table ?? self::query()->getQuery()->from
               );
    }
    
    static function saveAttachment($result)
    {
        //SAVE ATTACHMENT
        if( request('attachment') && ($result->id??null))
        {
            $paths = [];
            $where = [
                'id_relasi'=> $result->id,
                'tipe_attachment'=> self::table()
            ];
            
            if( is_array(current(current(request('attachment')))) ) 
            {
                foreach( request('attachment') as $key => $items )
                {
                    $where['tipe_attachment'] = self::table() .'_'. $key;

                    foreach( $items as $item )
                    {
                        if( $item['path'] ?? null )
                        {
                            Attachment::updateOrCreate($where+['path' => $item['path']], [
                                'title'=> $item['title'],
                                'path'=> $item['path'],
                                'params'=> ($item['params']??null) ? json_encode($item['params']) : NULL,
                                'id_relasi'=> $result->id,
                                'tipe_attachment'=> self::table() .'_'. $key,
                            ]);
                            $paths[] = $item['path'];
                        }
                    }

                    //delete old
                    Attachment::where($where)->whereNotIn('path', $paths)->delete();
                }
            }
            else
            {
                foreach( request('attachment') as $item )
                {
                    if( $item['path'] ?? null )
                    {
                        Attachment::updateOrCreate($where+['path' => $item['path']], [
                            'title'=> $item['title'],
                            'path'=> $item['path'],
                            'id_relasi'=> $result->id,
                            'params'=> ($item['params']??null) ? json_encode($item['params']) : NULL,
                            'tipe_attachment'=> self::table()
                        ]);
                        $paths[] = $item['path'];
                    }
                }

                //delete old
                Attachment::where($where)->whereNotIn('path', $paths)->delete();
            }

            // foreach( request('attachment') as $a )
            // {
            //     if( $a['path'] ?? null )
            //     {
            //         Attachment::updateOrCreate($where+['path' => $a['path']], [
            //             'title'=> $a['title'],
            //             'path'=> $a['path'],
            //             'id_relasi'=> $result->id,
            //             'tipe_attachment'=> self::table()
            //         ]);
            //         $paths[] = $a['path'];
            //     }
            // }
        }
    }

    function relattachments()
    {
        return $this->hasMany(Attachment::class, 'id_relasi', 'id')->where('tipe_attachment', self::table());
    }
    function relauthor()
	{
		return $this->hasOne(\App\User::class, 'id', 'created_by');
	}
}