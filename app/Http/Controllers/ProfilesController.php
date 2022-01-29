<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Cache;

class ProfilesController extends Controller
{
  public function index(User $user) {

    $follows = (auth()->user()) ? auth()->user()->following->contains($user->id) : false;

    $postCount =Cache::remember(
      'count.posts.'.$user->id,
     now()->addSeconds(30),
      function () use($user) {
        return $user->posts->count();
    }); 

    $followersCount = $user->profile->followers->count();
    $followingCount = $user->following->count(); 

    return view('profiles.index', compact('user','follows','postCount','followersCount','followingCount'));
  }

  public function edit(User $user) {
      $this->authorize('update',$user->profile);
      return view('profiles.edit', compact('user'));
  }

  public function update(Request $request, $id)
  {
      //
      $data = $this->validate($request,[
        'title' => 'required',
        'description' => 'required',
        'url' => 'url',
        'image' => '',
        ]);

        # Fetch Raw Data ....
        $rawData = Profile::find($id);


       if(request()->hasFile('image')){

          $FinalName = time().rand().'.'.$request->image->extension();

           if($request->image->move(public_path('images'),$FinalName)){
             //commit this because i have no image
                // unlink(public_path('images/'.$rawData->image));
                $data['image'] =  $FinalName;
              }else{
                  $FinalName = $rawData->image;
              }

       }else{
           $FinalName = $rawData->image;
       }



       //$data['image'] =  $FinalName;

       $op = Profile::where('id',$id)->update($data);

       if($op){
           $message = "Raw Updated";
       }else{
           $message = "Error Try Again";
       }

       session()->flash('Message',$message);

       return redirect("/profile/".auth()->user()->id);
  }
// public function update(Request $request, $id)
// {
//     //
//     $data = $this->validate($request,[
//         'title' => 'required',
//         'description' => 'required',
//          'url' => 'url',
//          'image' => 'nullable|image|mimes:png,jpg',
        
//       ]);

//       # Fetch Raw Data ....
//       $rawData = Profile::find($id);


//      if(request()->hasFile('image')){

//         $FinalName = time().rand().'.'.$request->image->extension();

//          if($request->image->move(public_path('images'),$FinalName)){

//                unlink(public_path('images/'.$rawData->image));

//             }else{
//                 $FinalName = $rawData->image;
//             }

//      }else{
//          $FinalName = $rawData->image;
//      }



//      $data['image'] =  $FinalName;

//      $op = Profile::where('id',$id)->update($data);

//      if($op){
//          $message = "Raw Updated";
//      }else{
//          $message = "Error Try Again";
//      }

//      session()->flash('Message',$message);
//      return redirect("/profile/".auth()->user()->id);
// }
}