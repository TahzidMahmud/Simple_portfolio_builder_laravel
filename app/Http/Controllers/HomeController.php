<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use Illuminate\Http\Request;
use App\EducationField;
use App\WorkField;
use App\ContactForm;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use App\Award;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }
    // public function log_out(){
    //     Auth::logout();
    //     return redirect();
    // }

    public function welcome(){
        $users=User::all();
        return view('welcome',compact('users'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user=Auth::user();

        $count=ContactForm::where('for_user',$user->id)->where('seen','0')->count();


        return view('home',compact('user','count'));
    }
    public function update_profile(Request $request){

        $id=$request->id;
        $to=$request->end;
        $genera=str_replace(' ', '-', $request->genera);
        $resume_db=null;
        $user=User::findOrFail($id);

        $social_links=$user->social_links;

        if(!is_null($social_links)){

            array_push($social_links,$request->social_links);
        }
        if($request->file('resume')){


            auth()->user()->resume ?File::delete( public_path(auth()->user()->resume)): '';

            $resume= $request->resume;
            $resumeName=$request->file('resume')->getClientOriginalName().time().$request->file('resume')->getClientOriginalExtension();
            $resume->move(public_path('resumes/'.auth()->user()->name.'/'),$resumeName);
            $resume_db='resumes/'.auth()->user()->name.'/'.$resumeName;
        }

        if ($request->file('image')) {
            if(!is_null($user->image)){

                File::delete( public_path(''.$user->image));
            }
            $img = $request->image;
            $imagePath = $request->file('image');
            $imageName = $imagePath->getClientOriginalName();
            $img->move(public_path('images/'.auth()->user()->name.'/'), $imageName);
            $user->update([
                'skills'=>$request->skills,
                'social_links'=>$request->social_links,
                'name'=>$request->name,
                'email'=>$request->email,
                'phone_no'=> $request->phone_no,
                'address'=>$request->address,
                'about'=>$request->about,
                'genera'=>$genera,
                'resume'=>$resume_db,
                'image'=>'images/'.auth()->user()->name.'/'.$imageName,
            ]);

        }
        $user->update([
            'skills'=>$request->skills,
            'social_links'=>$request->social_links,
            'name'=>$request->name,
            'email'=>$request->email,
            'phone_no'=> $request->phone_no,
            'address'=>$request->address,
            'about'=>$request->about,
            'genera'=>$genera,
            'resume'=>$resume_db,


        ]);




        return redirect()->route('home');
    }
    public function show($id){


        $user=User::findOrFail($id);
        // $user=$usr[0];
        $skills=$user->skills;
        $social_links=$user->social_links;
        if($skills){
            foreach($skills as $skill){
                $skills = preg_replace('/[^0-9]/', '', $skills);
            }
        }
        $email=$user->email;
        $name=$user->name;
        $address=$user->address;
        $phone_no=$user->phone_no;
        $about=$user->about;
        $id=$user->id;
        $image=$user->image;
        $genera=$user->genera;

        $education=EducationField::where('user_id',$id)->get();
        $work_field=WorkField::where('user_id',$id)->get();
        $awards=Award::all()->where('user_id',$id);


        return view('homepage',compact('skills','user','social_links','email','name','address','phone_no','id','about','education','work_field','image','genera','awards'));
    }

    public function search_user(Request $request){
        $data=[];
        $user=User::where('email',$request->mail)->get();
        if ($user->isEmpty()){
            $data=array("message"=>"No Results Found..!");
        }else{
            $data=$user;
            $data[0]["message"]="result found";
        }
         return response($data);
    }

    public function download_cv($id){
        $user=User::findOrFail($id);

        $file_path=public_path().'/'.$user->resume;
        $headers = array(
            'Content-Type' => 'application/pdf',
        );
        $headers=implode(" ",$headers);

        if(file_exists($file_path)){

            return response()->download($file_path,$user->name.'cv.pdf',[
                'Content-Type' => 'application/pdf'
            ]);
        }
    }
    public function get_link(){
        $url=url()->to('/').'/landing/'.auth()->user()->id;
        return  response($url);
    }
}
