<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {

        $users = auth()->user()->following->pluck('user_id');
        //$users= Profile::find(auth()->user()->id);

      // dd( $users= Profile::join('users','users.id','=','profiles.user_id')->select('profiles.*','users.id')->get()->pluck('user_id'));
        
        $posts = Post::whereIn('user_id',$users)->with('user')->latest()->get();

        return view('posts.index', compact('posts'));
    }

    public function create(){

        return view('posts.create');
    }

    public function store(Request $request)
    {
        //
        $data = $this->validate($request,[
            
            "caption" => "required|max:100",
            "image"   => "required|image|mimes:png,jpg"
          ]);


         $FinalName = time().rand().'.'.$request->image->extension();

        if($request->image->move(public_path('images'),$FinalName)){


        $data['user_id'] = auth()->user()->id;
        $data['image'] = $FinalName;

        $op =  Post::create($data);

       

       if($op){
           $Message = "Raw Inserted";
       }else{
           $Message = "Error Try Again";
       }
    }else{
        $Message = "Error In Uploading Try Again ";
    }
        session()->flash('Message',$Message);


        return redirect('/profile/'.auth()->user()->id);
    }

    public function show(Post $post) {
        
        return view('posts.show', compact('post'));
    }
}
