<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    //
    public function create(Request $request)
    { 
        $request->validate([
          'name'=>'required|string|max:200',
          'done'=>'required',
        ]);

        $task = Task::create([
          'name'=>$request->name,
          'done'=>$request->done
        ]);
        return response()->json($task,200);
    }

    public function getAll()
    {
        $task = Task::orderBy('name','asc')->get();
        return response()->json($task);
    }

    public function get($taskId)
    {
        
        $task = Task::where('id',$taskId)->get();
        
        if(isset($task[0]['name'])){$code='200';}else{$code='404';}
        return response()->json($task,$code); 
    }

    public function update(Request $request, $taskId)
    {
        $request->validate([
            'name'=>'required|string|max:200',
            'done'=>'required',
        ]);
        $task = Task::where('id',$taskId)
                ->update([
                  'name'=>$request->name,
                  'done'=>$request->done,
                ]);
        if ($task){$code='200';}else{$code='404';}
        return response()->json([ 
          'code'=>$code,
        ],$code);
    }

    public function delete($taskId)
    { 
        $task = Task::where('id',$taskId)->delete();
        if($task){$code='204';$msg='data deleted !';}else{$code='404';$msg='Failed!';} 
        return response()->json(['msg'=>$msg,'code'=>$code],$code);

    }

    public function upload(Request $request)
    {

        $request->validate([
            'file' => 'required|max:100',
            'fileName'=>'required'
        ]);
        
        $files = $request->file('file');
        $path = $files->storeAs('uploads', $files->getClientOriginalName(),'local-upload'); //Default location will be in "storage/app/public"
        
        return response()->json([
            'message' => 'success uploaded !',
            'path' => $path,
        ],201);
    }

    public function download(Request $request)
    { 
        $baseDir = Storage::disk('local-upload');
        $chekDir = $baseDir->exists('uploads');
        if ($chekDir) {
            try {
                $path = $baseDir->path("uploads/$request->filename");  
                return response()->download($path); 
            } catch (\Throwable $th) {
                return response()->json([
                    'message'=>'File Not Found'
                ],404);
            } 
        } 
    }

}
