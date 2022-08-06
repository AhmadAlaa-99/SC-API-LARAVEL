<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Notifications\CommentPostNotify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\BaseController as BaseController;
class CommentController extends BaseController
{
        public function store(Request $request,$id)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'Comment' => 'required|max:50|min:3',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
            //return $this->sendError('validate Error', $validator->errors());
        }
        $user = Auth::user();
        $input['user_id'] = Auth::id();
        $input['post_id']=$id;
        $Comment = Comment::create([
            'Comment' => $request->Comment,
            'post_id' => $input['post_id'],
            'user_id' => $input['user_id'],
        ]);
        $post=Post::findOrFail($id);
        $infUser=User::where('id',$input['user_id'])->select('id','fullname','profile_image')->first();
        $ownerID=Post::select('user_id')->where('id',$id)->first();
        $ownerPost=User::where('id',$ownerID->user_id)->first();
     // $ownerPost->notify(new CommentPostNotify($infUser,$post));
      return $this->sendResponse($Comment, 'Comment added successfully');
    }
    public function update(Request $request,$id)
    {
        $errorMessage = [];
        $input = $request->all();
        $Comment=Comment::find($id);
        
        if ($Comment == null)
        {
            return $this->sendError('the comment does not exist', $errorMessage);
        }

        $validator = Validator::make($input, [  // arg make must be array
            'Comment' => 'required|max:50|min:3',
        ]);
        if ($validator->fails()) {
           // return $this->sendError('validation error', $validator->errors());
           return $this->sendError($validator->errors()->first());
        }
        $Comment->update([
            'Comment'=>$request->Comment,
        ]);
        $infUser=User::where('id',$input['user_id'])->select('id','username','profile_image')->get();
        $ownerID=Post::select('user_id')->where('id',$id)->first();
        $ownerPost=User::where('id',$ownerID)->first();
       // $ownerPost->notify(new CommentPostNotify($infUser));

        return $this->sendResponse($Comment, 'Comment update');

    }

    public function postComments($id)
    {
        $comments=Comment::where('post_id',$id)->get();
      //  $comments=Comment::where('post_id',$id)->select('user_id','comment')->get();
        return $this->sendResponse($comments, 'Post Comments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */

     
    public function destroy($id)
    {
        $errorMessage = [];
        $Comment = Comment::find($id);
        if ($Comment == null) {
            return $this->sendError('the comment does not exist', $errorMessage);
        }
        if ($Comment->user_id != Auth::id()) {
            return $this->sendError('you dont have rights', $errorMessage);
        }
        $Comment->delete();
        return $this->sendResponse(true, 'Comment delete successfully');
    }
}
