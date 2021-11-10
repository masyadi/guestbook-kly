<?php
namespace App\Http\Controllers;
use App\User, Session, Helper, Auth;
use App\Http\Controllers\Controller;
use Socialite;

class AuthController extends Controller
{
    /**
     * LOGIN PROCCESS
    **/
    function authUser($type = 'cms')
    {
        $validation = [
            'user'=> 'required|min:2|max:255',
            'password'=> 'required',
        ];
        $v = \Validator::make(request()->all(), $validation);

        if( $v->fails() )
        {
            return back()->withErrors($v)->withInput();
        }

        $auth = User::where('name', request('user'))
        ->orWhere('email', request('user'))
        ->orWhere('username', request('user'))
        ->with('relrole')
        ->first();

        if( $auth )
        {
            if( \Hash::check(request('password'), $auth->password) )
            {
                $params = [
                    'type' => $type
                ];

                if( $type == 'cms' )
                {
                    if( in_array($auth->relrole->slug, ['super-admin', 'admin']) )
                    {
                        $params['url'] = Helper::CMS('');
                        $params['login'] = Helper::CMS('login?_c='.Session::get('_c'));
                    }
                    else return back()->withInput()->with('error', _('You do not have access to the admin page'));
                }
                else
                {
                    $params['url'] = url('/');
                    $params['login'] = url('login?_c='.Session::get('_c'));
                }

                return $this->_proccessLogin($auth->email, $params);
            }
            else return back()->withInput()->with('error', __('Your password is not valid'));
        }
        else return back()->withInput()->with('error', __('Your account is not found'));
    }

    /**
     * FORGOT 
    **/
    function forgot()
    {
        \Config::set('title', __('Lupa Password'));

        if( $c = request('c') )
        {
            \Config::set('title', __('Buat Password Baru'));

            $code = \Helper::url_decode($c);

            if( \Helper::val($code, 'time') >= strtotime('-2hours') )
            {
                if( request()->getMethod()=='POST' )
                {
                    $validation = [
                        'password_baru'=> 'required|min:6|max:255',
                        'ulangi_password'=> 'required|min:6|max:255|same:password_baru',
                    ];
                    $v = \Validator::make(request()->all(), $validation);
    
                    if( $v->fails() )
                    {
                        return back()->withErrors($v)->withInput();
                    }
    
                    $user = \App\User::where('token', \Helper::val($code, 'token'))->first();
    
                    if( $user )
                    {
                        //old password
                        if( \Hash::check(request('password_baru'), $user->password) )
                        {
                            return back()->withInput()->with('error', __('Password harus beda dengan password sebelumnya'));
                        }
    
                        $password = \Hash::make(request('password_baru'));
    
                        if(\App\User::where('id', $user->id)->update([
                            'token'=> \Hash::make(md5(json_encode($user).uniqid().time())),
                            'password'=> $password
                        ]))
                        {
                            return redirect(Helper::CMS('login'))->withInput()->with('success', __('Password berhasil direset'));
                        }
                    }
                }
                else
                {
                    $user = \App\User::where('token', \Helper::val($code, 'token'))->first();

                    if( $user )
                    {
                        return view('CMS::page.auth.reset', compact('c', 'user'));
                    }
                }
            }
            
            return redirect(Helper::CMS('login'))->with('error', __('Link salah atau telah kedualuarsa, silakan hubungi yang mengundang Anda'));
        }
        else 
        {
            if( request()->getMethod()=='POST' )
            {
                $validation = [
                    'user'=> 'required|min:2|max:255',
                ];
                $v = \Validator::make(request()->all(), $validation);
    
                if( $v->fails() )
                {
                    return back()->withErrors($v)->withInput();
                }
    
                $auth = User::where('name', request('user'))->orWhere('email', request('user'))->first();
    
                if( $auth )
                {
                    //new token
                    $token = \Hash::make(md5(json_encode($auth).uniqid().time()));
    
                    if( User::where('id', $auth->id)->update(['token'=>$token, 'updated_at'=>date("Y-m-d H:i:s")]) )
                    {
                        // send notification
                        $user = User::where('id', $auth->id)->first();
    
                        \Notification::send($user, new \App\Notifications\ForgotPassword(Helper::CMS('forgot'), Helper::CMS('login')));
    
                        return back()->withInput()->with('success', __('Link reset password sudah terikim ke email Anda'));
                    }
                    
                }
                else return back()->withInput()->with('error', __('Akun Anda tidak ditemukan'));
    
                return back()->withInput()->with('error', __('Terjadi kesalahan, silakan coba lagi'));
            }
            else return view('CMS::page.auth.forgot');
        }
    }

    /** 
     * ACCEPT INVITATION
     */
    function acceptInvitation()
    {
        \Auth::guard(Helper::activeGuard())->logout();

        if( $user = \App\User::whereRaw("md5(email)='".request('c')."'")->first() )
        {
            $tokenHashed = \Hash::make(md5(json_encode($user)).uniqid().time());

            if(\App\User::where('id', $user->id)->update([
                'email_verified_at'=> date("Y-m-d H:i:s"),
                'updated_at'=> date("Y-m-d H:i:s"),
                'token'=> $tokenHashed,
                'status'=> '1',
            ]))
            {
                $token = \Helper::url_encode([
                    'time'=> time(),
                    'token'=> $tokenHashed,
                    'email'=> $user->email,
                    'login_url' => Helper::CMS('login')
                ]);

                return redirect(Helper::CMS('forgot?c='.$token))->with('success', __('Undangan telah disetujui, dan akun kamu sekarang sudah aktif. Silakan buat password baru Anda'));
            }
            else 
                return redirect(Helper::CMS('login'))->with('error', __('Gagal menyetujui undangan, Silakan hubungi Administrator'));
        }
        else return abort(404);
    }

    /** LOGIN with Google **/
    function googleRedirect()
    {
        if( $n = request('_c') ) Session::put('_c', $n);

        return Socialite::driver('google')->redirect();
    }

    function googleCallback()
    {
        $auth = Socialite::driver('google')->stateless()->user();

        if( $auth )
        {
            //default
            $params = [
                'url'=> Helper::CMS(''),
                'login'=> Helper::CMS('login?_c='.Session::get('_c')),
            ];
            
            //Frontend
            if(strtolower(Session::get('_c')) == 'web')
            {
                $dataUser = User::where('email', $auth->email)->first();

                if(empty($dataUser))
                {
                    // INSERT DATA USER
                    $data = array(
                        'email' => $auth->email,
                        'role_id' => !empty(Role::candidate()) ? Role::candidate()->id : 0,
                        'username' => explode('@', $auth->email)[0].'-'.Str::random(3),
                        'name' => ucwords(explode('@', $auth->email)[0]),
                        'scopes' => json_encode(['web']),
                        'created_at' => Helper::formatDate(null)
                    );

                    User::insert($data);
                }

                $params = [
                    'url'=> Helper::CMS('/'),
                    'login'=> Helper::CMS('login'),
                ];
            }

            return $this->_proccessLogin($auth->user['email'], $params, $auth);
        }
        return abort(401);
    }
    /** LOGIN with Google **/

    private function _proccessLogin($email, $params, $auth=null)
    {
        if( $c = Session::get('_c') )
        {
            $c = Helper::url_decode($c);

            if( isset($c['_next']) ) $params['url'] = $c['_next'];
        }

        //check user
        if($user = User::where('email', $email)->where('status', '1')->first())
        {
            event(new \App\Events\OnLogin($auth, $user, $params));

            Session::forget('_c');

            Helper::sessionLogin($user->id);
            
            $remember = isset($params['remember']) ? $params['remember'] : false;

            Auth::guard($params['type'])->login($user, $remember);

            return redirect($params['url'])->with('success', __('Akun Anda berhasil masuk sebagai '.$user->name));
        }
        else  return redirect($params['login'])->with('error', __('Akun ada tidak ditemukan'));
    }
}