<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller,
	App\User, Helper, DataTable, DB;


class NotifController extends Controller
{
	function index()
	{
        if( request('read') )
        {
            DB::table('notifications')->where('notifiable_id', \Auth::user()->id)->update([
                'read_at'=>date('Y-m-d H:i:s')
            ]);

            return redirect(url('notif'));
        }

        \Config::set('title', __('Notifikasi'));

        if( request('r') ) return $this->readNotif(request('r'));

        if (request()->ajax())
        {
            return DataTable::of(DB::table('notifications')->where('notifiable_id', \Auth::user()->id)->orderBy('read_at'))
                    ->editColumn('data', function ($r) { 
                        $data = json_decode($r->data, true);
                        
                        $msg = $r->read_at ? Helper::val($data, 'description', '-') : 
                            '<strong><a href="'.url('notif?r='.$r->id).'">'.Helper::val($data, 'description', '-').'</a></strong>';

                        return $msg;
                    })
                    ->editColumn('created_at', function ($r) { return Helper::formatDate($r->created_at, 'd M Y H:i'); })
                    ->rawColumns(['data', 'created_at'])
                    ->toJson();
        }

        return view('page.notif');
	}

    private function readNotif($id)
    {
        $row = DB::table('notifications')->where('id', $id)->where('notifiable_id', \Auth::user()->id)->first();

        if( $row )
        {
            $data = json_decode($row->data, true);

            //update read at
            DB::table('notifications')->where('id', $id)->update(['read_at'=>date('Y-m-d H:i:s')]);

            return redirect( isset($data['url']) ? $data['url'] : url('notif') );
        }
        else return abort(404);
    }
}