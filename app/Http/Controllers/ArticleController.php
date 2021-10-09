<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\HasTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    use ApiResponse;
  

    
    private  function articleValidate($request){
        $rules =[
            'title_en'  => 'required|string',
            'title_ar'  => 'required|string',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            
           
             
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

      $request->merge(['title'=>
      [
          'en'=>$request->title_en,
          'ar'=>$request->title_ar
      ],
      'content'=>
      [

            'en'=>$request->content_en,
            'ar'=>$request->content_ar
      ]
      ]
    );
      $article=$request->user()->articles()->create($request->all());
       
      return  $this->apiResponse(1,'Articl Created Successfully',$article);
       
    }



    public function articleUpdate(Request $request ,$id)
    {
        
        $article=article::find($id);
    
        if ($article) {

            $request->merge(['title'=>
            [
                'en'=>$request->title_en??$article->getTranslation('title','en'),
                'ar'=>$request->title_ar??$article->getTranslation('title','ar')
            ],
            'content'=>
            [
                  'en'=>$request->content_en??$article->getTranslation('content','en'),
                  'ar'=>$request->content_ar??$article->getTranslation('content','ar')
            ]
            ]
          );

            $article->update($request->all());
            
         
          
            return  $this->apiResponse(1,'Articl updated Successfully',$article);

        }else {

            return  $this->apiResponse(0,'The article dosen\'t exist',$article);

        }
      

    }



    public function articleDelete($id)
{
   
    
        $article = Article::find($id);
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


