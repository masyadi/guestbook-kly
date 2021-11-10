<?php
namespace App\Http\Controllers\Cms\Tool;

use App\Http\Controllers\Controller,
    App\Models\ImageBank, Helper, Storage,
    Illuminate\Validation\Rule;

class ImageController extends Controller
{
    /**
     * LIST 
    **/
    public function index()
    {
        $rows = ImageBank::query();

        $isAjax = request()->ajax();
        
        switch( request('view') )
        {
            case 'my-file':
                $rows->where('status', '<>', '-1');            
                $rows->where('mime_type', '<>', '__dir');
                $rows->where('created_by', \Auth::user()->id);
            break;
            case 'recent':
                $rows->where('mime_type', '<>', '__dir');
                $rows->where('status', '<>', '-1');
                $rows->orderBy('id', 'DESC');
                
                if( request('q') )
                {
                    $rows->where(function($q){
                        foreach( explode(' ', request('q')) as $s )
                        {
                            $q->orWhere('title', 'LIKE', '%'.trim($s).'%');   
                            $q->orWhere('location', 'LIKE', '%'.trim($s).'%');   
                            $q->orWhere('photographer', 'LIKE', '%'.trim($s).'%');   
                            $q->orWhere('caption', 'LIKE', '%'.trim($s).'%');   
                            $q->orWhere('keywords', 'LIKE', '%'.trim($s).'%');   
                            $q->orWhere('copyright', 'LIKE', '%'.trim($s).'%');   
                        }
                    });
                }
                
            break;
            case 'starred':
                $rows->where('starred', '1');
                $rows->where('status', '<>', '-1');
            break;
            case 'trash':
                $rows->where('status', '-1');
            break;
            default:
                
                $rows->where('status', '<>', '-1');
                
                if($isAjax)
                {
                    $rows->orderBy('id', 'DESC');
                    $rows->where('mime_type', '<>', '__dir');
                }
                else
                {
                    $rows->orderBy('title');
                    $rows->where('parent', request('parent', 0));
                }

            break;
        }
        
        $rows = $rows->paginate(12);
        
        $position = ImageBank::position(request('parent', 0));
        
        if( $position ) $position = array_reverse($position);
        
        $current = ImageBank::find(request('parent'));

        return view('CMS::page.tool.image_bank.'.($isAjax ? '_index' : 'index'), compact('rows', 'position', 'current', 'isAjax'));
    }

    /**
     * ADD NEW
    **/
    public function create()
    {
        return view('CMS::page.tool.image_bank.form');
    }
    
    /**
     * ROW EDIT BY ID 
    **/
    public function edit($id)
    {
        $row = ImageBank::find($id);

        if( !$row ) return abort(404);


        return view('CMS::page.tool.image_bank.form', compact('row', 'roles'));
    }
    
    /**
     * ROW DELETE BY ID 
    **/
    public function destroy($id)
    {
        $ret = ['status'=>false, 'message'=> 'Delete operation on resource has failed'];
        
        if(ImageBank::find($id)->update(['status'=>'-1']))
        {
            $ret = ['status'=>true, 'message'=> 'Resource has been deleted succesfully'];        
        }
        
        return response()->json($ret);
    }
    
    /**
     * UPDATE
    **/
    public function update($id)
    {
        $ret = ['status'=>false, 'message'=> 'The operation on resource has failed'];
        
        switch( request('action') )
        {
            case 'star':
                
                if(ImageBank::find($id)->update(['starred'=>'1']))
                {
                    $ret = ['status'=>true, 'message'=> 'Successfully changed as favorite'];        
                }
                
            break;
            
            case 'unstar':
                
                if(ImageBank::find($id)->update(['starred'=>'0']))
                {
                    $ret = ['status'=>true, 'message'=> 'Successfully changed as unfavorite'];        
                }
                
            break;
            
            case 'rotate_left':
            case 'rotate_right':
            case 'flip_h':
            case 'flip_v':
                
                if( $r = ImageBank::find($id) )
                {
                    $file = pathinfo($r->path);
                    $filePath = storage_path('temp/'.$file['basename']);

                    if( !file_exists(storage_path('temp')) ) mkdir(storage_path('temp'), 777, true);
                    
                    if( config('filesystems.default')=='public' )
                    {
                        $content = Storage::get($r->path);
                    }
                    else
                    {
                        $content = @file_get_contents(Storage::url($r->path));
                    }

                    if( \File::put($filePath, $content) )
                    {
                        // create Image from file
                        $img = \Image::make($filePath);
                        
                        if( request('action')=='rotate_left' )
                        {
                            $img->rotate(-90);
                        }
                        elseif( request('action')=='rotate_right' )
                        {
                            $img->rotate(90);
                        }
                        elseif( request('action')=='flip_h' )
                        {
                            $img->flip('h');
                        }
                        elseif( request('action')=='flip_v' )
                        {
                            $img->flip('v');
                        }
                        
                        $img->save();
                        
                        $rev = $r->rev ? json_decode($r->rev, true) : [];
                        
                        $path = config('app.img_path').date("Y/m/d")."/";
                        
                        $revNumb = (count($rev)+1).'_';
                        
                        $output = $path.$revNumb.$file['basename'];
                        
                        if( file_exists($filePath) )
                        {   
                            //new image
                            $size = getimagesize($filePath);
                            $dataUpdate = ['path'=> $output];

                            if( isset($size[1]) )
                            {
                                $dataUpdate['width'] = $size[0];
                                $dataUpdate['height'] = $size[1];    
                                $dataUpdate['ratio'] = Helper::image_ratio($size[0], $size[1]);
                                $dataUpdate['file_size'] = filesize($filePath);
                                $dataUpdate['updated_by'] = \Auth::user()->id;
                                $dataUpdate['updated_at'] = date("Y-m-d H:i:s");
                            }

                            
                            #if(Storage::disk()->move('temp/'.$file['basename'], $output))
                            if(Storage::disk()->put($output, \File::get($filePath)))
                            {
                                $rev[] = [
                                    'file'=> $r->path,
                                    'width'=> $r->width,
                                    'height'=> $r->height,
                                    'ratio'=> $r->ratio,
                                    'file_size'=> $r->file_size,
                                    'action'=> request('action'),
                                    'output'=> $dataUpdate,
                                ];
                                
                                $dataUpdate['rev'] = json_encode($rev);

                                //update thumbnail 300x300
                                $outT = config('app.thumb_size').'/'.$output;

                                Storage::disk()->put($outT, \File::get($filePath));
                                
                                $img = \Image::make(Storage::disk()->path($outT));

                                // crop the best fitting 1:1 ratio (300x300) and resize to 300x300 pixel
                                $img->fit(300);

                                // enable interlacing
                                $img->interlace();

                                // save image interlaced
                                $img->save();
                                
                                //update resource                                
                                if( ImageBank::where('id', $id)->update($dataUpdate) )
                                {
                                    $ret = ['status'=>true, 'message'=> 'Successfully changed'];
                                }  

                                //remove temp
                                unlink($filePath);
                            }
                        }
                    }
                }
                                
            break;
            
            case 'trash':
                
                if(ImageBank::find($id)->update(['status'=>'-1']))
                {
                    if( $ids = ImageBank::child($id) )
                    {
                        ImageBank::whereIn($ids)->update(['status'=>'-1']);
                    }
                    
                    $ret = ['status'=>true, 'message'=> 'The resources was deleted'];        
                }
                
            break;
            case 'restore':
                
                if(ImageBank::find($id)->update(['status'=>'1']))
                {
                    if( $ids = ImageBank::child($id) )
                    {
                        ImageBank::whereIn($ids)->update(['status'=>'1']);
                    }
                    
                    $ret = ['status'=>true, 'message'=> 'The resources was restored'];        
                }
                
            break;
        }
        
        return response()->json($ret);
    }
    
    /**
     * SAVE
    **/
    public function store()
    {
        $act = request('action');
        request()->request->remove('action');
        
        $userID = Helper::userId();
        
        switch( $act )
        {
            case 'create_folder':
                
                $validation = [
                    'title'=> [
                        'required', 'max:150',
                        Rule::unique('image_banks')->where(function ($q) {
                            $q->where('status', '<>', '-1');
                            $q->where('mime_type', '__dir');
                            $q->where('parent', request('parent'));
                        })->ignore(request('id'))   
                    ]
                ];
                
                request()->request->add(['created_by'=>$userID]);
                
                return Helper::save(ImageBank::class, $validation, function($request, $data, $redirect) {
            
                    if( isset($data->id) ) return back();
                    
                    return $redirect;
        
                }, request('id'));
             
            break;
            
            case 'upload_temp':
                
                return Helper::UploadTemp('temp');
                
            break;

            case 'upload_camera':
                
                return Helper::UploadCamera('temp');
                
            break;
            
            case 'remove_temp':
                
                if( file_exists(storage_path('temp/'.request('file'))) )
                {
                    if(unlink(storage_path('temp/'.request('file'))))
                    {
                        return '1';
                    }
                }
                
            break;

            case 'upload':

                $ret = ['status'=>false, 'message'=>__('An error occurred, please try again')];
                
                $validation = [
                    'title'=> ['required', 'min:2', 'max:150'],
                    'date' => ['date'],
                    'location'=> ['max:255'],
                    'photographer'=> ['max:75'],
                    'copyright'=> ['required', 'min:2', 'max:75'],
                    'caption'=> ['required', 'min:2', 'max:255'],
                    'keywords'=> ['max:255'],
                    'files'=> ['required'],
                ];

                $files = json_decode(request('files'), true);

                $v = \Validator::make(request()->except('_token'), $validation);

                if( $v->fails() )
                {
                    $ret['message'] = $v->errors()->all();
                }
                else
                {
                    if( $files )
                    {
                        $c = 0;

                        $parent = request('parent', 0);

                        //auto create folder
                        if( request('folder') && request('folder')!='image-bank' )
                        {
                            $folder = strtoupper(trim(request('folder')));

                            if( $r = ImageBank::where('parent', request('parent', 0))->where('title', $folder)->first() )
                            {
                                $parent = $r->id;
                            }
                            else
                            {
                                $parent = ImageBank::insertGetid([
                                    'parent'=> request('parent', 0),
                                    'title'=> $folder,
                                    'mime_type'=> '__dir',
                                    'created_by'=> $userID,
                                ]);
                            }
                        }

                        foreach( $files as $f )
                        {
                            $file = storage_path('temp/'.$f);

                            if( file_exists($file) )
                            {
                                $data = request()->except(['_token', 'files', 'folder']);
                                $data['date'] = Helper::formatDate($data['date'], "Y-m-d");
                                $data['parent'] = $parent;
                                $data['mime_type'] = mime_content_type($file);
                                $data['file_size'] = filesize($file);
                                $data['created_by'] = $userID;

                                $isImageFile = preg_match('/^image\//', $data['mime_type']);

                                //IMAGE PROPERTIES
                                if( $isImageFile )
                                {
                                    $img = \Image::make($file);

                                    $exif = null;//$img->exif();
                                    
                                    if( in_array(strtolower($data['mime_type']), ['image/jpg', 'image/jpeg']) )
                                    {
                                        // enable interlacing
                                        $img->interlace();

                                        // save image interlaced
                                        $img->save();
                                    }
                                    
                                    $size = getimagesize($file);

                                    if( isset($size[1]) )
                                    {
                                        $data['width'] = $size[0];
                                        $data['height'] = $size[1];    
                                        $data['ratio'] = Helper::image_ratio($size[0], $size[1]);
                                    }
                                    
                                    if( $exif )
                                    {
                                        $data['exif_camera'] = Helper::val($exif, 'Make');
                                        $data['exif_camera'].= ' | '. Helper::val($exif, 'Model');

                                        $data['exif_software'] = Helper::val($exif, 'Software');
                                        $data['exif_date_taken'] = Helper::val($exif, 'DateTime', null);

                                        $data['exif_lng'] = Helper::getGps(Helper::val($exif, 'GPSLongitude', []), Helper::val($exif, 'GPSLongitudeRef'));
                                        $data['exif_lat'] = Helper::getGps(Helper::val($exif, 'GPSLatitude', []), Helper::val($exif, 'GPSLatitudeRef'));
                                    }

                                    
                                }

                                Helper::setMeta($file, [
                                    'date' => $data['date'],
                                    'title' => $data['title'],
                                    'event' => $data['event'],
                                    'caption' => $data['caption'],
                                    'keywords' => $data['keywords'],
                                    'copyright' => $data['copyright'],
                                    'location' => $data['location'],
                                    'photographer' => $data['photographer'],
                                ]);

                                $path = ($isImageFile ? config('app.img_path') : config('app.attachment_path')).date("Y/m/d")."/";
                                
                                //$uploaded = Storage::disk()->move('temp/'.$f, $path.$f);
                                $uploaded = Storage::disk()->put($path.$f, \File::get(storage_path('temp/'.$f)));

                                if( $isImageFile )
                                {
                                    $pathT = config('app.thumb_size').'/'.config('app.img_path').date("Y/m/d")."/";
                                    $pathS = '200xauto/'.config('app.img_path').date("Y/m/d")."/";

                                    $thumbnail = Storage::disk()->put($pathT.$f, \File::get(storage_path('temp/'.$f)));
                                    $thumbnailS = Storage::disk()->put($pathS.$f, \File::get(storage_path('temp/'.$f)));
                                }

                                if( $uploaded )
                                {
                                    $data['path'] = $path.$f;
                                    $fileInfo = $data;

                                    if( $isImageFile )
                                    {
                                        //create thumbnail 200xauto
                                        $img = \Image::make(Storage::disk()->path($pathS.$f));

                                        // resize the image to a height of 200 and constrain aspect ratio (auto width)
                                        $img->resize(200, null, function ($constraint) {
                                            $constraint->aspectRatio();
                                        });

                                        // enable interlacing
                                        $img->interlace();

                                        // save image interlaced
                                        $img->save();

                                        //create thumbnail 300x300
                                        $img = \Image::make(Storage::disk()->path($pathT.$f));

                                        // crop the best fitting 1:1 ratio (300x300) and resize to 300x300 pixel
                                        $img->fit(300);

                                        // enable interlacing
                                        $img->interlace();

                                        // save image interlaced
                                        $img->save();

                                        $fileInfo['thumb'] = Helper::imgSrc($data['path'], '300x300');
                                    }

                                    if($id = ImageBank::insertGetid($data))
                                    {
                                        $c++;
                                    }

                                    $fileInfo['id'] = md5(uniqid().time());
                                    $fileInfo['real'] = Helper::imgSrc($data['path']);
                                    $fileInfo['info'] = \Str::limit(explode('_',pathinfo(storage_path('temp/'.$f))['filename'])[0],255);
                                    $fileInfo['info'] = str_replace('-', ' ', $fileInfo['info']);
                                    $fileInfo['info'] = ucwords($fileInfo['info']);

                                    unlink(storage_path('temp/'.$f));
                                }
                            }
                        }

                        if( $c>0 )
                        {
                            $ret = ['status'=>true, 'message'=>__($c. ' files uploaded'), 'file'=>$fileInfo];    
                        }
                        
                    }
                }

                return response()->json($ret);

            break;
            
        }
    }
}