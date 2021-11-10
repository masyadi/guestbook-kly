<?php
namespace App\Http\Controllers\Api;
use App\User, Session, Helper;
use App\Http\Controllers\Controller;
use \App\Events\OnLogin;

class AuthController extends Controller
{
    function init()
    {
        return Helper::success([
            'name'=> config('app.name'),
            'title'=> config('app.title'),
            'timezone'=> config('app.timezone'),
            'locale'=> config('app.locale'),
            'icon'=> asset('static/img/icon.png?v1'),
            'logo'=> asset('static/img/logo.png?v1'),
        ]);
    }

    function login()
    {
        $v = \Validator::make(request()->all(), [
            'user'=> 'required|min:2|max:255',
            'password'=> 'required',
        ]);

        if( $v->fails() )
        {
            return Helper::error(null, $v->messages());
        }

        $auth = User::where('name', request('user'))
            ->orWhere('email', request('user'))
            ->orWhere('username', request('user'))
            ->first();
    
        if( $auth )
        {
            if( \Hash::check(request('password'), $auth->password) )
            {
                $auth->token = $auth->createToken('token-auth')->plainTextToken;
                $auth->photo = $auth->photo ? \Storage::url($auth->photo) : null;
                $auth->forceChangePassword = $auth->logged_in_at ? 0 : 1;

                if( !empty($auth->logged_in_at) )
                {
                    event(new OnLogin(null, $auth, null));
                }

                return Helper::success($auth, __('message.login.successfully', ['attribute' => $auth->name]));
            }
            else return Helper::error(null, __('message.password.wrong'));
        }
        else return Helper::error(null, __('message.account.not.found'));
    }

    function forgotPassword()
    {
        $v = \Validator::make(request()->all(), [
            'user'=> 'required|min:2|max:255',
        ]);

        if( $v->fails() )
        {
            return Helper::error(null, $v->messages());
        }

        $auth = User::where('name', request('user'))
            ->orWhere('email', request('user'))
            ->orWhere('username', request('user'))
            ->first();
    
        if( $auth )
        {
            \Notification::send($auth, new \App\Notifications\PinPassword());

            return Helper::success(null, __('message.code.sent.successfully'));
        }
        else return Helper::error(null, __('message.account.not.found'));
    }

    function changePassword()
    {
        $v = \Validator::make(request()->all(), [
            'password'=> 'required|min:8|max:255',
            'confirm_password'=> 'required|min:8|max:255|same:password',
        ]);

        if( $v->fails() )
        {
            return Helper::error(null, $v->messages());
        }

        $auth = \Auth::user();
    
        if( $auth )
        {
            if( request('current_password') )
            {
                if( !\Hash::check(request('current_password'), $auth->password) )
                {
                    return Helper::error(null, __('message.current.password.incorrect'));
                }
            }

            if( \Hash::check(request('password'), $auth->password) )
            {
                return Helper::error(null, __('message.password.different'));
            }

            if( User::where('id', $auth->id)->update([
                'password' => \Hash::make(request('password'))
            ]) )
            {
                event(new OnLogin(null, $auth, null));
                
                return Helper::success($auth, __('message.password.changed.successfully'));
            }

            return Helper::error(null, __('message.failed.change.password'));
        }
        else return Helper::error(null, __('message.account.not.found'));
    }

    function matchPinPassword()
    {
        $v = \Validator::make(request()->all(), [
            'user'=> 'required|min:2|max:255',
        ]);

        if( $v->fails() )
        {
            return Helper::error(null, $v->messages());
        }

        $auth = User::where('name', request('user'))
            ->orWhere('email', request('user'))
            ->orWhere('username', request('user'))
            ->first();

        if( $auth )
        {
            $c = null;

            if( file_exists(storage_path('pin_password/'.$auth->id.'.json')) )
            {
                $c = file_get_contents(storage_path('pin_password/'.$auth->id.'.json'));
                $c = $c ? json_decode($c, TRUE) : NULL;
            }

            if( $c['kode'] ?? null )
            {
                if($c['kode'] == request('code'))
                {
                    $auth->token = $auth->createToken('token-auth')->plainTextToken;
                    $auth->photo = $auth->photo ? \Storage::url($auth->photo) : null;
                    $auth->forceChangePassword = 1;

                    return Helper::success($auth, __('message.please.change.password'));
                }
            }
            
            return Helper::error(null, __('message.code.you.incorrect'));    
        }
        else return Helper::error(null, __('message.account.not.found'));
    }

    function logout()
    {
        auth()->user()->tokens()->delete();

        return Helper::success(null, __('message.logout.successfully'));
    }

    protected function user()
    {
        return \Auth::user();
    }

    protected function userId()
    {
        return $this->user()->id;
    }
}