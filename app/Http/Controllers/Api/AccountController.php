<?php
namespace App\Http\Controllers\Api;
use App\User, Session, Helper;
use App\Http\Controllers\Controller;
use NotificationChannels\WebPush\PushSubscription;


class AccountController extends Controller
{
    function store()
    {
        $userID = \Auth::user()->id;

        $v = \Validator::make(request()->all(), [
            'name'=> 'required|min:2|max:75',
            'email'=> 'required|min:2|max:255|email|unique:users,email,' . $userID,
            'phone'=> 'required|digits_between:10,20|numeric|unique:users,phone,' . $userID,
        ]);

        if( $v->fails() )
        {
            return Helper::error(null, $v->messages());
        }

        $dtUpdate = [
            'email'=> request('email'),
            'name'=> request('name'),
            'phone'=> request('phone'),
        ];

        //photo
        if( request('photo') )
        {
            $res = Helper::UploadCamera('temp', 'photo');

            if( $res['name'] ?? null )
            {
                $f = $res['name'];
                $path = config('app.img_path').date("Y/m/d")."/";
                $pathS = '200xauto/'.config('app.img_path').date("Y/m/d")."/";

                $content = \File::get(storage_path('temp/'.$f));

                $uploaded = \Storage::disk()->put($path.$f, \File::get(storage_path('temp/'.$f)));
                $thumbnailS = \Storage::disk()->put($pathS.$f, \File::get(storage_path('temp/'.$f)));

                if( $uploaded )
                {
                    //create thumbnail 200xauto
                    $img = \Image::make(\Storage::disk()->path($pathS.$f));

                    // resize the image to a height of 200 and constrain aspect ratio (auto width)
                    $img->resize(200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    // enable interlacing
                    $img->interlace();

                    // save image interlaced
                    $img->save();

                    $dtUpdate['photo'] = $path.$f;

                    unlink(storage_path('temp/'.$f));
                }
            }
        }

        if( User::find($userID)->update($dtUpdate) )
        {
            $auth = User::find($userID);

            $auth->token = $auth->createToken('token-auth')->plainTextToken;
            $auth->photo = $auth->photo ? \Storage::url($auth->photo) : null;

            return Helper::success($auth, __('message.profile.update.success'));
        }
        else return Helper::error(null, __('message.profile.update.fail'));
    }

    function subscription()
    {
        $user = User::findOrFail(request('userId'));
        $subscription = request('subscription') ? json_decode( request('subscription'), TRUE ) : NULL;

        // create PushSubscription and connect to this user
        if( $subscription )
        {
            $pushsub = $user->updatePushSubscription($subscription['endpoint'], $subscription['keys']['p256dh'], $subscription['keys']['auth']);

            return $pushsub;
        }
    }

    function stopSubscription()
    {
        $this->validate(request()->all(), ['endpoint' => 'required']);

        $user = PushSubscription::findByEndpoint(request('endpoint'))->user;

        $user->deletePushSubscription(request('endpoint'));

        return response()->json(null, 204);
    }
}