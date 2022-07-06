<?php 
namespace App\Traits;
use App\Models\Image;
use App\Model\Post;

Trait ImagesTrait 
{
   //POST
    public function storeImagesPost($post,$request)     
    {
       foreach($request as $img)
       {
         
         $image_name=md5(microtime()).'_'.$post->name.'.'.$img->extension();
        $img->move(public_path('/post_images'),$image_name);
        $image=Image::create(['path'=>$image_name,'post_group_id'=>$post->id]);
        $image->save();
       }
    }
       public function updateImagesEvent($post,$request)     
    {

       foreach($post->images as $img)
       {
          $img->delete();
       }
       foreach($request as $img)
       {
        $image_name='img-'.$post->name.'.'.$img->extension();
        $img->move(public_path('/project_images'),$image_name);
        $image=Image::create(['path'=>$image_name,'project_id'=>$pro->id]);
        $image->save();

       }
    }
       public function deleteImagesPost($pro)     
    {
        foreach($pro->images as $img)
       {
          $img->delete();
       }
}

//Event
public function storeImagesEvent($post,$request)     
    {
       foreach($request as $img)
       {
         
         $image_name=md5(microtime()).'_'.$post->name.'.'.$img->extension();
        $img->move(public_path('/group_post'),$image_name);
        $image=Image::create(['path'=>$image_name,'post_group_id'=>$post->id]);
        $image->save();
       }
    }
       public function updateImagesEvent($post,$reuest)     
    {

       foreach($post->images as $img)
       {
          $img->delete();
       }
       foreach($request as $img)
       {
        $image_name='img-'.$post->name.'.'.$img->extension();
        $img->move(public_path('/project_images'),$image_name);
        $image=Image::create(['path'=>$image_name,'project_id'=>$pro->id]);
        $image->save();

       }
    }
       public function deleteImagesEvent($pro)     
    {
        foreach($pro->images as $img)
       {
          $img->delete();
       }
}

//POST_GROUP
public function storeImagesGroup($post,$request)     
    {
       foreach($request as $img)
       {
         
         $image_name=md5(microtime()).'_'.$post->name.'.'.$img->extension();
        $img->move(public_path('/group_post'),$image_name);
        $image=Image::create(['path'=>$image_name,'post_group_id'=>$post->id]);
        $image->save();
       }
    }
       public function updateImagesGroup($post,$request)     
    {

       foreach($post->images as $img)
       {
          $img->delete();
       }
       foreach($request as $img)
       {
        $image_name='img-'.$post->name.'.'.$img->extension();
        $img->move(public_path('/project_images'),$image_name);
        $image=Image::create(['path'=>$image_name,'project_id'=>$pro->id]);
        $image->save();

       }
    }
       public function deleteImagesGroup($pro)     
    {
        foreach($pro->images as $img)
       {
          $img->delete();
       }
}
     

}


