<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\KD\Ayurvedic_Info;
use Carbon; 
use DB;
use Auth;

class KnowledgedbController extends Controller{

    public function show(){
        
        $details = Ayurvedic_Info::get();
        $recent_blog = Ayurvedic_Info::orderBy('created_at')
                                ->limit(10)
                                ->get();

        $tags = DB::table('test.Tags')->get();
        $data = array(
            'menu' => 'knowledge',
            'section' => 'details',
            'details' => $details,
            'blogs' => $recent_blog,
            'tags'  => $tags
        );
        return view('home')->with($data);
    }

    public function save(Request $request){
        $blog = new Ayurvedic_Info;
        $blog->Title = $request->Title;
        $blog->Author = Auth::user()->employee->name;
        $blog->Description = $request->Description;
        $blog->tag = $request->tagging;
        $blog->created_at = Carbon::now();
        $blog->updated_at = Carbon::now();
        $blog->save();
        return redirect('/service/kd');
    }

    public function update(Request $request){
        
        //$blog->save();
        return redirect('/service/kd');
    }
}
?>
