<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController as BaseController;

class GroupController extends BaseController
{


    public function create(Request $request)
    {
        
        $owner_id = Auth::user()->id;
        $image_name = time() . '.' . $request->photo->extension();
        $request->photo->move(public_path('upload/Group_images'), $image_name);
        $Group = DB::insert('insert into groups (name,owner_id,category,description,photo)values(?,?,?,?,?)',
        [$request->name,$owner_id,$request->category,$request->description,$image_name]);
        $Group=DB::select('select * from groups where owner_id=? and name=?',[$owner_id ,$request->name]);
        return $this->sendResponse($Group,'Group Created Successfull');
    }


    public function createpost(Request $request , $group_id)
    {
        $owner_id = Auth::user()->id;
        $post=DB::insert('insert into post_group(group_id,user_id,content,category)values(?,?,?,?)',
        [$group_id,$owner_id,$request->content,$request->category]);
        if($request->file('images'))
        {
            $this->storeImagesGroup($post,$request->images);
        }
        $Post=DB::select('select *,* from post_group pg join images img on img.post_group_id=pg.id where img.post_group_id=?',[$post->id]);
        return $this->sendResponse($Post,'Post Created Successfull');
    }

    public function showRequestPost(Request $request,$group_id)
{
$Post=DB::select('select * from post_group where group_id=?,accept=?',[$group_id,'0']);
return $this->sendResponse($Post,'Post proposed');
}
public function AcceptRequestPost(Request $request,$post_id,$group_id)
{
    //update accept to '1'
    $post=DB::table('post_group')->where('id',$post_id)->update(['accept'=>1]);
    return $this->sendResponse($Post,'Post proposed');

}
public function RefuseRequestPost(Request $request,$post_id,$group_id)
{
    //delete from group_post
    $post=DB::table('post_group')->where('id',$post_id)->delete();
}
public function requestJoin(Request $request,$group_id)
{
    $user_id=Auth::User()->id;
    $join=DB::table('group_user')->insert([
        'group_id'=>$group_id,
        'user_id'=>$user_id,
    ]);
    return  $join;
}
public function showRequestsJoin($group_id)
{
    $show_request=DB::table('group_user')->where('group_id',$group_id)->where('accept','0')->get();
    return $show_request;
}
public function AcceptRequestJoin($group_id,$user_id)
{
$acceptJoin=DB::table('group_user')->where(['group_id'=>$group_id,'user_id'=>$user_id])->update(['accept'=>1]);
}
public function RefuseRequestJoin($group_id,$user_id)
{
    $acceptJoin=DB::table('group_user')->where(['group_id'=>$group_id,'user_id'=>$user_id])->delete();
}
public function OuterGroup($group_id)
{
    $user_id=Auth::User()->id;
    $outer=DB::table('group_user')->where(['group_id'=>$group_id,'user_id'=>$user_id])->delete();
}
public function deletePostGroup($group_id,$post_id)
{
//by ownerPost
$Post=DB::table('post_group')->where(['id'=>$post_id,'user_id'=>Auth::id])->delete();
}
public function dimissalGroup($user_id,$group_id)
{
    //by ownerGroup
    $outer=DB::table('group_user')->where(['group_id'=>$group_id,'user_id'=>$user_id])->delete();

}
public function deleteMyGroup($group_id)
{

$group=DB::table('groups')->where(['id'=>$group_id,'owner_id'=>Auth::id])->delete();
}
public function showOwnerGroup()
{
 $group=DB::table('groups')->where(['owner_id'=>Auth::id])->get();
}
public function joinedGroup()
{
    $user=Auth::id;
$joined=DB::statement('select groups.* from groups inner group_user 
                                        on groups.id=group_user.group_id inner users
                                         on users.id=group_user.user_id
                                          where group_user.accept=? and group_user.user_id=?',
                                          ['1',$user])->get();
}
public function requestedGroup()
{
    $user=Auth::id;
    $requested=DB::statement('select groups.* from groups inner group_user 
                                            on groups.id=group_user.group_id inner users
                                             on users.id=group_user.user_id
                                              where group_user.accept=? and group_user.user_id=?',
                                              ['0',$user])->get();
    }

public function proposedGroup()
{
 $group=DB::table('groups')->get();
}
public function SearchGroup()
{

}
public function MyPostsGroup($group_id)
{
$MyPost=DB::table('post_group')->where(['group_id'=>$group_id,'user_id'=>Auth::id])->get();
return $MyPost;
}
public function PostMemberGroup($user_id,$group_id)
{
    $MemberPost=DB::table('post_group')->where(['group_id'=>$group_id,'user_id'=>$user_id])->get();
    return $MemberPost;
}

}

