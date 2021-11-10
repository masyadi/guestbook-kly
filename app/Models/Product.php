<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel
{
    protected $appends = ['logoFullPath','youtubeUrlJson','thumbnailPath'];

    function relattachments()
    {
        $attachmentType = [
            self::table() .'_pattern',
            self::table() .'_pricelist',
        ];

        return $this->hasMany(Attachment::class, 'id_relasi', 'id')->whereIn('tipe_attachment', $attachmentType);
    }

    public function rel_parent_product()
    {
        return $this->belongsTo(self::class, 'parent_product');
    }

    public function rel_children_product()
    {
        return $this->hasMany(self::class, 'parent_product');
    }

    public function relcategory()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function rel_child_product()
    {
        return $this->hasOne(self::class, 'parent_product');
    }

    public function getlogoFullPathAttribute()
    {
        return $this->logo ? \Storage::url($this->logo) : null;
    }

    public function getYoutubeUrlJsonAttribute()
    {
        return $this->youtube_url ? json_decode($this->youtube_url) : null;
    }

    public function getThumbnailPathAttribute()
    {
        $thumbnail = Attachment::where('tipe_attachment', self::table() .'_cover')->where('id_relasi', $this->id)->first();
        return $thumbnail ? $thumbnail->path : null;
    }
}
