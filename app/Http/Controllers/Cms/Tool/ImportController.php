<?php
namespace App\Http\Controllers\Cms\Tool;

use App\Http\Controllers\Controller,
    Helper,
    App\Models\Blog,
    App\Models\Page,
    App\Models\Tag,
    Illuminate\Validation\Rule;

use \App\Events\OnSaved;

class ImportController extends Controller
{
    public function index()
    {
        return view('CMS::page.tool.import.index');
    }

    public function store()
    {
        $v = \Validator::make(request()->all(), [
            'file'=> 'required|file|max:10000',
            'route'=> 'required|in:blog,page,tag',
        ]);

        if( $v->fails() )
        {
            return back()->withErrors($v)->withInput();
        }
        else
        {
            $md5Name = md5_file(request()->file('file')->getRealPath());
            $guessExtension = request()->file('file')->guessExtension();
            $file = request()->file('file')->storeAs('temp', $md5Name.'.'.$guessExtension, 'local');

            $res = $this->import(\Storage::disk('local')->path($file), function($rows){
                
                $userID = \Auth::user()->id;

                if( $rows )
                {
                    foreach($rows as $k=>$v)
                    {
                        //blog
                        if( $v->get('judul_blog') && $v->get('isi') && request('route')=='blog' )
                        {
                            $title = $v->get('judul_blog');

                            $dt = [
                                'title'=> $title,
                                'content'=> $v->get('isi'),
                                'category_id'=> $v->get('id_kategori'),
                                'slug'=> \Str::slug($title)
                            ];

                            if( $r = Blog::where('slug', \Str::slug($title))->first() )
                            {
                                $dt['updated_at'] = date("Y-m-d H:i:s");
                                $dt['updated_by'] = $userID;

                                Blog::where('id', $r->id)->update($dt);
                            }
                            else
                            {
                                $dt['created_at'] = date("Y-m-d H:i:s");
                                $dt['created_by'] = $userID;
                            
                                Blog::insert($dt);
                            }
                        }
                        //page
                        elseif( $v->get('judul_page') && $v->get('isi') && request('route')=='page' )
                        {
                            $title = $v->get('judul_page');

                            $dt = [
                                'title'=> $title,
                                'content'=> $v->get('isi'),
                                'slug'=> \Str::slug($title)
                            ];

                            if( $r = Page::where('slug', \Str::slug($title))->first() )
                            {
                                $dt['updated_at'] = date("Y-m-d H:i:s");
                                $dt['updated_by'] = $userID;

                                Page::where('id', $r->id)->update($dt);
                            }
                            else
                            {
                                $dt['created_at'] = date("Y-m-d H:i:s");
                                $dt['created_by'] = $userID;
                            
                                Page::insert($dt);
                            }
                        }
                        //tag
                        elseif( $v->get('judul_tag') && $v->get('tipe') && request('route')=='tag' )
                        {
                            $title = $v->get('judul_tag');

                            $dt = [
                                'title'=> $title,
                                'type'=> $v->get('tipe'),
                                'slug'=> \Str::slug($title)
                            ];

                            if( $r = Tag::where('slug', \Str::slug($title))->first() )
                            {
                                $dt['updated_at'] = date("Y-m-d H:i:s");
                                $dt['updated_by'] = $userID;

                                Tag::where('id', $r->id)->update($dt);
                            }
                            else
                            {
                                $dt['created_at'] = date("Y-m-d H:i:s");
                                $dt['created_by'] = $userID;
                            
                                Tag::insert($dt);
                            }
                        }

                    }
                    
                    return redirect(url(request('route')))->with('success', __('Berhasil import data'));
                }
                
            });

            return redirect(url(request('route')));
        }
    }
}