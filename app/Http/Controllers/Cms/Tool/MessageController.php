<?php
namespace App\Http\Controllers\Cms\Tool;

use App\Models\Message, Helper,
	App\Models\MessageHeader;

use App\User, Auth, Str;

class MessageController 
{
	function index()
	{
		$uID = \Auth::user()->id;

		//GET ROWS
		$rows = MessageHeader::where(function($q) use($uID) {

			if( request('view')=='sent' )
			{
				$q->orWhere('from_id', $uID);
				$q->orWhere(function($q) use($uID) {
					$q->where('to_id', $uID);
					$q->whereHas('relmessages', function($q){
						$q->where('is_from_sender', false);
					});
				});
			}
			else
			{
				//inbox
				$q->orWhere('to_id', $uID);
				$q->orWhere(function($q) use($uID) {
					$q->where('from_id', $uID);
					$q->whereHas('relmessages', function($q){
						$q->where('is_from_sender', false);
					});
				});
			}
		})->orderBy('updated_at', 'DESC');

		//SEARCH
		if( request('q') )
		{
			$rows->where(function($q){

				foreach (explode(' ', request('q')) as $s) 
				{
					$q->orWhere('subject', 'LIKE', '%'.$s.'%');

					$q->orWhereHas('relmessages', function($q) use($s) {
						$q->where('content', 'LIKE', '%'.$s.'%');
					});
				}

			});
		}


		$rows = $rows->paginate(25);

		if( $rows )
		{
			foreach( $rows as $k=>$v )
			{
				$c = $v->from_id == $uID ? false : true;

				$v->unread = Message::where('header_id', $v->id)->where('is_from_sender', $c)->whereNull('read_at')->count();
				$v->date = Message::where('header_id', $v->id)->max('created_at');
				$v->is_attachment = Message::where('header_id', $v->id)->whereNotNull('attachments')->count();
				
				if( $v->from_id == $uID )
				{
					$v->sender =  \App\User::find($v->to_id)->name;
				}
				else
				{
					$v->sender =  \App\User::find($v->from_id)->name;
				}

				$rows[$k] = $v;
			}
		}

		return view('CMS::page.tool.message.inbox', compact('rows'));
	}

	function show($slug)
	{
		if( $row = MessageHeader::where('slug', $slug)->first() )
		{
			$fromID = \Auth::user()->id;

			$rows = [];

			Message::where('header_id', $row->id)->orderBy('id')->chunk(1000, function($r) use(&$rows) {
				$rows = array_merge($rows, $r->toArray());
			});

			$c = $row->from_id == $fromID ? false : true;

			Message::where('header_id', $row->id)->where('is_from_sender', $c)->whereNull('read_at')->update(['read_at'=>date("Y-m-d H:i:s")]);

			return view('CMS::page.tool.message.show', compact('row', 'rows', 'slug'));	
		}
		else return abort(404);
	}

	function create()
	{
		return view('CMS::page.tool.message.create');
	}

	function store()
	{
		$fromID = \Auth::user()->id;
		$fromSender = true;

		$validation = [
            'content'=> 'required'
        ];

        if( !request('slug') )
        {
        	$validation['to_id'] = 'required';
        	$validation['subject'] = 'required|max:255';
        }

        $v = \Validator::make(request()->all(), $validation);

        if( $v->fails() )
        {
            $redirect = back()->withErrors($v)->withInput();
        }
        else
        {
        	//header
        	$headerID = null;

        	if( request('slug') )
        	{
	        	if( $header = MessageHeader::where('slug', request('slug'))->first() )
	        	{
	        		$headerID = $header->id;
	        		$fromSender = $header->from_id == $fromID ? true : false;
	        	}
	        }

	        if( !$headerID )
	        {
	        	$dt = [
	        		'from_id'=> $fromID,
	        		'from_type'=> 'user',
	        		'to_id'=> request('to_id'),
	        		'subject'=> request('subject'),
	        		'created_at'=> date('Y-m-d H:i:s'),
	        		'updated_at'=> date('Y-m-d H:i:s'),
	        	];
	        	$dt['slug'] = Str::slug($dt['subject'].'-'.md5($dt['from_id'].'-'.$dt['to_id'].'-'.$dt['created_at']));

	        	//insert header
	        	$headerID = MessageHeader::insertGetId($dt);
	        }
        	
        	if(Message::insert([
    			'header_id'=> $headerID,
    			'is_from_sender'=> $fromSender,
    			'content'=> request('content'),
    			'created_at'=> date('Y-m-d H:i:s'),
    		]))
        	{
        		MessageHeader::where('id', $headerID)->update(['updated_at'=>date('Y-m-d H:i:s')]);

        		$redirect = redirect(url('message'))->with('success', 'Pesan terkirim');

        		//send notif
        		$this->_sendNotif($headerID, $fromSender, request('content'));
        	}
        	else $redirect = back()->with('error', 'Pesan gagal terkirim')->withInput();
        }

        return $redirect;
	}

	private function _sendNotif($headerID, $fromSender, $content)
	{
		if($header = MessageHeader::find($headerID))
		{
			$toID = $fromSender ? $header->to_id : $header->from_id;
			$sender = $fromSender ? $header->from_id : $header->to_id;

			if( $fromSender )
			{
				//sender from id
				$sender = \App\User::with('relrole')->find($header->from_id);
			}
			else
			{
				//sender from id
				$sender = \App\User::with('relrole')->find($header->to_id);
			}

			if( !$fromSender )
			{
				//sender from id
				$user = \App\User::with('relrole')->whereIn('id', [$header->from_id])->get();
			}
			else
			{
				//sender from id
				$user = \App\User::with('relrole')->whereIn('id', [$header->to_id])->get();
			}

			if( $user )
			{
				$link = url('message/'.$header->slug);

				\Notification::send($user, 
	                new \App\Notifications\NotifMessage($sender, $content, $link)
	            );
	        }
		}
	}
}