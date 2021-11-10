<?php

namespace App\Http\Controllers\Cms\Update;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuestBook;
use App\Models\Location;
use DataTable;
use Helper;

class GuestBookController extends Controller
{
    public function index()
    {
        if (request()->ajax() || request('export') ) 
        {
            $rows = GuestBook::with(['rel_province', 'rel_city'])->where('status', '<>', '-1')->orderBy('id', 'desc');

            //EXPORT
            if( request('export') ) return $this->_export(DataTable::of($rows)->getFilteredQuery());
            
            return DataTable::of($rows)
                ->addColumn('action', function ($r) { return Helper::action($r); })
                ->editColumn('status', function ($r) { return Helper::status($r); })
                ->editColumn('address_label', function ($r) { return ucwords(strtolower($r->address .', '. $r->rel_city->nama .', '. $r->rel_province->nama)); })
                ->editColumn('updated_at', function ($r) { return Helper::formatDate($r->updated_at, 'id'); })
                ->toJson();
        }

        return view('CMS::page.update.guest_book.index');
    }

    public function create()
    {

        return view('CMS::page.update.guest_book.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'min:2', 'max:75'],
            'last_name' => ['required', 'min:2', 'max:75'],
            'organization' => ['required', 'min:2', 'max:100'],
            'province' => ['required'],
            'city' => ['required'],
            'address' => ['required', 'min:3'],
        ]);

        return Helper::save(GuestBook::class, [], function($request, $data, $redirect) {
            return $redirect;
        }, request('id'));
    }

    public function edit($id)
    {
        $row = GuestBook::with(['rel_province', 'rel_city'])->find($id);

        if( !$row ) return abort(404);

        return view('CMS::page.update.guest_book.form', compact('row'));
    }

    public function destroy($id)
    {
        $resutl = ['status' => false, 'message' => 'Delete operation on resource has failed'];
        
        if( GuestBook::find($id)->update(['status'=>'-1']) )
        {
            $resutl = ['status' => true, 'message'=> 'Resource has been deleted succesfully'];        
        }
        
        return response()->json($resutl);
    }

    public function getCity(Request $request)
    {
        if( $request->kode )
        {
            $kode = (int) $request->kode .'01';
            $data = Location::where('type', 'city')->where('kode', '>=', $kode)->where('kode', '<', ($kode + 100))->get()->pluck('nama', 'kode');
            return $data;
        }
    }
}
