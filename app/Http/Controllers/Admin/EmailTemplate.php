<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Meeting;
use App\Room;
use DateTimeZone;
use Fomvasss\LaravelStrTokens\Facades\StrToken;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Jackiedo\Timezonelist\Facades\Timezonelist;


class EmailTemplate extends Controller
{
    //
    public function index()
    {

        $timezonelist = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $pageName ='Email Settings';

        return view('admin.email.template',compact('timezonelist','pageName'));
    }
    public function store(Request $request)
    {
//        dd(Carbon::now()->format('D d M Y g:i A'));

        $user = auth()->user();
        $meeting =  Room::find(25);

        $input = str_replace('[meeting_start]','[var:start]',$request->input('invite_participants'));
        $input = str_replace('[meeting_end]','[var:end]',$input);

        $str = StrToken::setText($input)
            ->setEntities([
            'user' => $user,
            'meeting' => $meeting,
            ])->setVars(['start' =>  \Carbon\Carbon::parse($meeting->start_date)->format('D d M Y g:i A')
                , 'end' =>  \Carbon\Carbon::parse($meeting->end_date)->format('D d M Y g:i A')])
            ->replace();

    print_r($str);
    }
}
