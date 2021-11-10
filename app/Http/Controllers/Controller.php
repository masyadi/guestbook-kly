<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Helper;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function creator()
    {
        $uid = Helper::userId();

        if(request('id')) request()->request->add(['updated_by'=>request('updated_by',$uid)]);
        if(!request('id')) request()->request->add(['created_by'=>request('created_by', $uid)]);
    }

    /**
     * EXPORT
     * @example
     * return $this->export(
            \App\Models\MstDistributor::query() ,[
            'NO', 'Nama Distributor', 'Tgl. Entri'
        ], 
        function($number, $row){
            
            return [
                $number,
                $row->nama,
                \Helper::formatDate($row->created_at, 'id'),
            ];

        }, 'distributr');
     */
    function export($query, $header, callable $data, $name=null, $format='xlsx', $setting=null, callable $options=null)
    {
        $logo = public_path('static/img/icon.png');

        if( request('header') ) $header = array_values(request('header'));

        $result = (
            new \App\Excel\TableExport(
                $query,[

                    'header'=> $header,
                    'title'=> $name,
                    'logo'=> $logo,
                    'orientation'=> \Helper::val($setting, 'orientation')=='landscape' ? \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE : \PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT,
                    'noheader'=> request('noheader') ? true : false,

                    ],function($number, $row) use($data) {

                        if( request('header') )
                        {
                            $ret = [];
                            foreach(request('header') as $k=>$v)
                            {
                                $ret[] = $row->{$k};
                            }

                            return $ret;
                        }
                        else {
                            return $data($number, $row);
                        }
                    }));
        
        // Optional options
        if( $options )
        {
            $options($result);
        }
        
        if( !$name ) $name = uniqid();

        $name = \Str::slug($name);

        if( $format=='pdf' )
        {
            return $result->download($name.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
        }
        else return $result->download($name.'.xlsx');
    }

    function import($file, callable $callback)
    {
        \Excel::import(new \App\Excel\TableImport(function( $response ) use($callback) {
            return $callback($response);
        }), 
        $file);
    }
}
