<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\HasTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Spatie\Translatable\HasTranslations;
class ArticleController extends Controller
{
    use ApiResponse;
    use HasTranslations;
    public $translatable = ['title','content'];

    
    private  function articleValidate($request){
        $rules =[
            'title_en'  => 'required|string',
            'title_ar'  => 'required|string',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            'user_id' => 'required|exists:users,id',
           
             
        ];
        
        $validator= Validator::make($request->all(),$rules );
       
        return $validator;
 
    }

    public function articleCreate(Request $request)
    {

        $validator=$this->articleValidate($request);
        if ($validator->fails()) {
          return  $this->apiResponse(0,$validator->errors()->first(),$validator->errors());
        }

        $article =new Article();
        $article->title=json_encode(['en'=>$request->title_en,'ar'=>$request->title_ar]);
        $article->content=json_encode(['en'=>$request->content_en,'ar'=>$request->content_ar]);
        $article->user_id=$request->user_id;
        $article->save();
        return  $this->apiResponse(1,'Articl Created Successfully',$article);
       
    }



    public function articleUpdate(Request $request ,$id)
    {
        
        $article=article::findOrFail($id);
       
        if ($article) {

            $article->update($request->all());
            $article->save();
            return  $this->apiResponse(1,'Articl updated Successfully',$article);

        }else {

            return  $this->apiResponse(0,'The article dosen\'t exist',$article);

        }
      

    }



    public function articleDelete($id)
{
   
    
        $article = Article::findOrFail($id);
        // $path = 'adminlte/images/'.$record->image;
      
        // Storage::disk('public')->delete('adminlte\img\\'.$record->photo);
        if ($article) {
        
        $article->delete();
       
        return  $this->apiResponse(1,'Articl deleted Successfully',$article);

    }else {

        return  $this->apiResponse(0,'The article dosen\'t exist',$article);

    }
       
        

    
}
    
}


