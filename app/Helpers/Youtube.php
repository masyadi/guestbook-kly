<?php

namespace App\Helpers;

trait Youtube
{
    static function getYouTubeVideoId($videoUrl)
    {
        $video_id = explode("?v=", $videoUrl);
        if( !isset($video_id[1]) )
        {
            $video_id = explode("youtu.be/", $videoUrl);
        }

        if( empty($video_id[1]) )
        {
            $video_id = explode("/v/", $videoUrl);
        }

        $video_id = explode("&", $video_id[1] ?? null);
        $youtubeVideoID = $video_id[0];

        if( $youtubeVideoID )
        {
            return $youtubeVideoID;
        }
        else
        {
            return null;
        }
    }

    static function getYoutubeThumbnail($videoUrl, $thumbnailQuality = null)
    {
        $videoId = self::getYouTubeVideoId($videoUrl);

        if( !isset($videoUrl) ) return null;
        if( !$videoId ) return null;

        $sources = [
            'low' => 'http://img.youtube.com/vi/'. $videoId .'/sddefault.jpg',
            'medium' => 'http://img.youtube.com/vi/'. $videoId .'/mqdefault.jpg',
            'high' => 'http://img.youtube.com/vi/'. $videoId .'/hqdefault.jpg',
            'maximum' => 'http://img.youtube.com/vi/'. $videoId .'/maxresdefault.jpg',
        ];

        if( in_array($thumbnailQuality, ['low', 'medium', 'high', 'maximum']) )
        {
            if( $source = $sources[strtolower($thumbnailQuality)] )
            {
                return $source;
            }
        }
         
        return $sources;
    }
}