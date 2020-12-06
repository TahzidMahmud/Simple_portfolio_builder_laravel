<?php

namespace App\Http\Controllers;

use Toastr;
use App\ContactForm;
use Illuminate\Http\Request;
use App\Events\NewContactMail;

class ContactFormController extends Controller
{
    public function count(Request $request){
        $count=ContactForm::where('for_user',$request->id)->where('seen','0')->count();
        return response($count);
    }
    public function contact_me(Request $request){
        ContactForm::create([
            'for_user'=>$request->id,
            'name'=>$request->name,
            'email'=>$request->email,
            'subject'=>$request->subject,
            'message'=>$request->message,
        ]);
        broadcast(new NewContactMail($request->id))->toOthers();

        return redirect()->back()->with('message','Thanks for contacting!');

    }
    public function index(){
        $mails=ContactForm::where('for_user',auth()->user()->id)->where('seen',0)->paginate(5);

        return view('mailList',compact('mails'));
    }
    public function mark(Request $request){


            $entry=ContactForm::findOrFail($request->id);
            $entry->update([
                'seen'=>1,
            ]);


        return response()->json(['success'=>'Ajax request submitted successfully']);
    }
}
