<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Whatsapp;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Helper;

class SubmissionController extends Controller
{
    public function whatsapp(Request $request)
    {
        $request->validate([
            'phone' => [
                'required',
                'min:2',
                'max:15',
                Rule::unique('whatsapps')->where(function($q) {
                    return $q->where('status', '<>', '-1');
                })->ignore(request('id'))
            ],
            'description'=> ['max:255'],
        ]);
        
        // add request
        $request->request->add(['status' => '1']);
        $request->request->add(['slug' => \Str::slug($request->phone)]);

        if( $data = Whatsapp::create($request->all()) )
        {
            return Helper::success($data, __('message.subscribe.success'));
        }

        return Helper::success(null, __('message.error.system'));
    }
}