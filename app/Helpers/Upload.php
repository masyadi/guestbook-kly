<?php
namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Kladislav\LaravelChunkUpload\Exceptions\UploadMissingFileException;
use Kladislav\LaravelChunkUpload\Handler\HandlerFactory;
use Kladislav\LaravelChunkUpload\Receiver\FileReceiver;

Trait Upload {
    
    /**
     * Handles the file upload
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws UploadMissingFileException
     * @throws \Kladislav\LaravelChunkUpload\Exceptions\UploadFailedException
     */
    static function UploadTemp($saveTo='app/temp') 
    {
        // create the file receiver
        $receiver = new FileReceiver("file", request(), HandlerFactory::classFromRequest(request()));

        // check if the upload is success, throw exception or return response you need
        if ($receiver->isUploaded() === false) 
        {
            throw new UploadMissingFileException();
        }

        // receive the file
        $save = $receiver->receive();

        // check if the upload has finished (in chunk mode it will send smaller files)
        if ($save->isFinished()) 
        {
            // save the file and return any response you need, current example uses `move` function. If you are
            // not using move, you need to manually delete the file by unlink($save->getFile()->getPathname())
            return self::saveFile($saveTo, $save->getFile());
        }

        // we are in chunk mode, lets send the current progress
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);
    }
    
    
    /**
     * Handles the file upload
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws UploadMissingFileException
     * @throws \Kladislav\LaravelChunkUpload\Exceptions\UploadFailedException
     */
    static function UploadCamera($saveTo='temp', $requestName='image') 
    {
        $cameraPath = storage_path($saveTo);

        if(!file_exists($cameraPath) ) mkdir($cameraPath, 0777, true);

        $fileName = md5(uniqid().time()).'.jpeg';

        $file = fopen($cameraPath.'/'.$fileName, "wb");

        $data = explode(',', request($requestName));

        if( $data[1] ?? null )
        {
            $data[1] = str_replace(' ', '+', $data[1]);

            fwrite($file, base64_decode($data[1]));

            fclose($file);

            return [
                'path' => $saveTo,
                'name' => $fileName,
                'mime_type' => 'image-jpeg'
            ];
        }
    }
    

     /**
     * Saves the file
     *
     * @param UploadedFile $file
     *
     * @return \Illuminate\Http\JsonResponse
     */
    static function saveFile($saveTo, UploadedFile $file)
    {
        $fileName = self::createFilename($file);
        // Group files by mime type
        $mime = str_replace('/', '-', $file->getMimeType());
        // Group files by the date (week
        $dateFolder = date("Y-m-W");

        // Build the file path
        $finalPath = storage_path($saveTo);

        // move the file name
        $file->move($finalPath, $fileName);

        return [
            'path' => $saveTo,
            'name' => $fileName,
            'mime_type' => $mime
        ];
    }

    /**
     * Create unique filename for uploaded file
     * @param UploadedFile $file
     * @return string
     */
    static function createFilename(UploadedFile $file, $strConcate = null)
    {
        $extension = $file->getClientOriginalExtension();
        
        $filename = str_replace(".".$extension, "", $file->getClientOriginalName()); // Filename without extension
        
        $filename = \Str::slug($filename);

        // Add timestamp hash to name of the file
        $filename .= "_" . ($strConcate == null ? \Helper::userId() : $strConcate) . '_' . uniqid() . "." . strtolower($extension);

        return $filename;
    }

    /**
     * Set Meta Tag Image
     * @param image string file
     * @param data array
     * @return null
     */
    static function setMeta($image, $data)
    {
        $fileInfo = pathinfo($image);
        
        if( file_exists($image) && strtolower($fileInfo['extension'])=='jpg' )
        {
            $iptc = new \App\Helpers\Iptc($image);
            
            //dd($iptc->dump());
            
            //set title
            if( isset($data['title']) ) $iptc->set(Iptc::HEADLINE, $data['title']);
            
            //set caption
            if( isset($data['caption']) ) $iptc->set(Iptc::CAPTION, $data['caption']); 

            //set event
            if( isset($data['event']) ) $iptc->set(Iptc::CATEGORY, $data['event']); 
            
            //set copyright
            if( isset($data['copyright']) ) $iptc->set(Iptc::COPYRIGHT_STRING, $data['copyright']); 
            
            //set location
            if( isset($data['location']) ) $iptc->set(Iptc::CITY, $data['location']); 
            
            //set photographer
            if( isset($data['photographer']) ) $iptc->set(Iptc::CREATOR, $data['photographer']); 
            
            //set date
            if( isset($data['date']) ) $iptc->set(Iptc::REFERENCE_DATE, $data['date']); 
    
            //set keywords
            if( isset($data['keywords']) ) $iptc->set(Iptc::KEYWORDS, explode(',', $data['keywords'])); 
    
    
            $iptc->write();
        }
    }
}