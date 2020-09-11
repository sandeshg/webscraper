<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use App\Domain;
use App\User;

class ProjectsController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        

        $project = Project::where('user_id', '=', auth()->user()->id)
                    ->orderBy('created_at', 'DESC')
                    ->paginate(5);


        $data = array(
            'title' => 'Projects',
            'data'=> $project,
            'testproject' => ['Domain','Online','Category','Link Library','Content']
        );
        return view('projects.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'description' => 'required'
        ]);

        // Create post
        $project = new Project;
        $project->name=$request->input('name');
        $project->description=$request->input('description');
        $project->remarks=$request->input('remarks');
        $project->user_id = auth()->user()->id;
        $project->save();

        return redirect('/projects')->with('success','Project Registered');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::find($id);
        if(!$project){
            return redirect('/projects')->with('error','Unable to process request');
        }else if(auth()->user()->id == $project->user_id){
            $domains = Domain::where('project_id', $project->id)->orderBy('created_at', 'DESC')->paginate(10);

            $data = array(
                'project' => $project,
                'domains' => $domains
            );
            return view('projects.show')->with($data);
        }else{
            return redirect('/projects')->with('error','Unable to process request');
        }
        
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $project = Project::find($id);
        if(auth()->user()->id == $project->user_id){
            return view('projects.edit')->with('project',$project);
        }else{
            return redirect('/projects')->with('error','Unable to process request');
        }
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required',
            'description' => 'required'
        ]);

        // find project
        $project = Project::find($id);
        if(auth()->user()->id == $project->user_id){
            $project->name=$request->input('name');
            $project->description=$request->input('description');
            $project->remarks=$request->input('remarks');
            $project->save();

            return redirect('/projects')->with('success','Project Updated');
        }else{
            return redirect('/projects')->with('error','Unable to process request');
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::find($id);
        
        if(auth()->user()->id == $project->user_id){
            $name = $project->name;
            $project->delete();
            return redirect('/projects')->with('success','Project "'.$name.'" Removed');
        }else{
            return redirect('/projects')->with('error','Unable to process request');
        }

        
    }
}
