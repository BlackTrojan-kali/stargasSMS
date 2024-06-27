<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    //
    public function show(Request $request){
        $articles = Article::all();
        return view("manageArticles",["articles"=>$articles]);
    }
    public function insert(Request $request){
        return view("addArticle");
    }
    public function delete(Request $request,$id){
        $article = Article::findOrFail($id);
        $article->delete();
        return response()->json(["message"=>"element supprime avec succes"]);
    }
    public function store(Request $request){
        $request->validate([
           "title"=>"string| required",
           "poids"=>"numeric|required",
           "state"=>"string|max:1",
           "type"=>"string|required"
        ]);
        $type = $request->type;
        $weight = floatval($request->poids);
        $state = intval($request->state);
        if($type == "bouteille-gaz"){
            $acticles = Article::where("title","=",$request->title)->where("weight","=",$weight)->where("state","=",$state)->get();
            if(count($acticles) >=1){
                return back()->withErrors(["message"=>"article existe deja"]);        
            }

        $article = new Article();
        $article->title = $request->title;
        $article->weight = $weight;
        $article->state = $state;
        $article->type = $type;
        $article->save();

        
        $stock = new Stock();
        $stock->qty = 0;
        $stock->type = $article->type;
        $stock->region = "centre";
        $stock->category = "magazin";
        $article->hasStock()->save($stock);
        
        $stock2 = new Stock();
        $stock2->qty = 0;
        $stock2->type = $article->type;
        $stock2->region = "centre";
        $stock2->category = "production";
        $article->hasStock()->save($stock2);
     
        $stock8 = new Stock();
        $stock8->qty = 0;
        $stock8->type = $article->type;
        $stock8->region = "centre";
        $stock8->category = "commercial";
        $article->hasStock()->save($stock8);
     
        $stock3 = new Stock();
        $stock3->qty = 0;
        $stock3->type = $article->type;
        $stock3->region="ouest";
        $stock3->category ="commercial";
        $article->hasStock()->save($stock3);

        $stock4 = new Stock();
        $stock4->qty = 0;
        $stock4->type = $article->type;
        $stock4->region="littoral";
        $stock4->category ="commercial";
        $article->hasStock()->save($stock4);

        $stock5 = new Stock();
        $stock5->qty = 0;
        $stock5->type = $article->type;
        $stock5->region="sud";
        $stock5->category ="commercial";
        $article->hasStock()->save($stock5);

        $stock6 = new Stock();
        $stock6->qty = 0;
        $stock6->type = $article->type;
        $stock6->region="est";
        $stock6->category ="commercial";
        $article->hasStock()->save($stock6);

        $stock7 = new Stock();
        $stock7->qty = 0;
        $stock7->type = $article->type;
        $stock7->region="nord";
        $stock7->category ="commercial";
        $article->hasStock()->save($stock7);
        
    

        return back()->withSuccess("article insere avec succes");


        }else{
            $articles=Article::where("title","=",$request->title)->get();
            $state = 0;
            $weight = 0;
            if(count($articles)>=1){
               return back()->withErrors(["message"=>"article existe deja"]);
        }
        $article = new Article();
        $article->title = $request->title;
        $article->weight = $weight;
        $article->state = $state;
        $article->type = $type;
        $article->save();
        return back()->withSuccess("article insere avec succes");

        }

    }
}
