<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Sandesh\TextAnalysis\Scrapers\WebScraper as ws;
use App\Project;
use App\Link;
use App\Domain;
use App\User;

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $domain = Domain::find($id);
       
        return view('links.create')->with('domain',$domain);
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
            'link'=>'required',
        ]);

        $link = new Link;
        $link->url = $request->input('link');
       
        $link->selector = json_encode(array_values($request->input('selector')));
        //$domain->selector=serialize($selector);
        $link->urlSignature = md5($request->input('link'));
        $link->remarks=$request->input('remarks');
        $link->isManual = $request->input('isManual');
        $link->domain_id = $request->input('domainCode');
        try{
            $link->save();
        }catch (Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return redirect('/domains/'.$request->input('domainCode'))->with('error','Link Already Present');
            }
        }
        

        return redirect('/domains/'.$request->input('domainCode'))->with('success','Link Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $link = Link::find($id);
        $data = array(
            'link' => $link,
            'selector'=>json_decode($link->selector),
            'content'=>json_decode($link->content),
        );
        //$this->debug($data['content'],1);
        return view('links.show')->with($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $link = Link::find($id);
        //$selector  = json_decode($domain->selector);
        //$this->debug((object)$selector,1);
        $domain = Domain::find($link->domain_id);
        $data = array(
            'domain'=>$domain,
            'link'=>$link,
            'selector'=>json_decode($link->selector)
        );
        //$this->debug(json_decode($link->selector),1);
        //return view('domains.create')->with('project',$project);
        return view('links.edit')->with($data);
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
            'link'=>'required',
        ]);

        // find link
        $link = Link::find($id);
        $link->url = $request->input('link');
       
        $link->selector = json_encode(array_values($request->input('selector')));
        //$domain->selector=serialize($selector);
        $link->urlSignature = md5($request->input('link'));
        $link->remarks=$request->input('remarks');
        $link->isManual = $request->input('isManual');
        $link->domain_id = $request->input('domainCode');
        try{
            $link->save();
        }
            catch (Illuminate\Database\QueryException $e){
                $errorCode = $e->errorInfo[1];
                if($errorCode == 1062){
                    return redirect('/domains/'.$request->input('domainCode'))->with('error','Link Already Present');
                }
            }
        

        return redirect('/domains/'.$request->input('domainCode'))->with('success','Link Added');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $link = Link::find($id);
        $domain_id = $link->domain_id;
        $link->delete();
        return redirect('/domain/'.domain_id)->with('success','Link Removed');
        
    }
    //Request $request

    public function preview(Request $request){
        $id = $request->input('id');
        $link = Link::find($id);
        if(!empty($link->selector)){
            return response()->json($this->pullData($link->url,json_decode($link->selector)));
        }else{
            $response = array("404"=>"Content Not Found");
            return response()->json($response);
        }
        //$this->debug($link->selector,1);
        //return response()->json($this->pullData($url, $selector)); 
        
    }
    /**
     * Extract data from web
     *
     * @param  \Url of the web $url
     * @param  Css selector $selector
     * @return array $output
     */
    private function pullData($url, $selector){
        $scrap = new ws($url,$selector,'_text');
       // $output = $scrap->getSinglePageContent($selector);
        return $scrap->getSinglePageContent($selector);
    }


    public function extract(Request $request){
        
        $now = new \DateTime('now');

        $domain_id = $request->input('domainCode');
        //$domain = Domain::find($domain_id);
        $links = Link::where('domain_id', $domain_id)->orderBy('created_at', 'DESC')->get();
        $i=0;
        foreach($links as $l){
            //extract content in json format.
            $l->content = json_encode($this->pullData($l->url, json_decode($l->selector)));
            $l->save(); //save data to db.
            $i++;
            usleep(150000);//sleep for quarter of a second
        }
        $date = new \DateTime();
        $diff = $date->getTimestamp() - $now->getTimestamp();
        return redirect('/domains/'.$domain_id)->with('success','Extracted data from '.$i.' links in '.$diff.' sec.');

    }

  //Request $request
    public function applySelector(Request $request){
        $id = $request->input('linkCode');
        $link = Link::find($id);
        if(!empty($link->selector)){

            //save to db
             DB::table('links')
             ->where('domain_id', $link->domain_id)
             ->update(['selector' => $link->selector]);
             return redirect('/links/'.$id.'/edit')->with('success','Applied identifiers to all.');
        }else{
            return redirect('/links/'.$id.'/edit')->with('error','Identifiers must be saved first.');
        }
    }


    public function download($id)
    {
        //get the selectors first
        $link = DB::table('links')->select('id','domain_id','url','selector','content')->where('links.domain_id', '=', $id)->get();
       // $link = DB::table('links')->select('id','domain_id','selector','content')->get();
        $template = array();
        //make array template for existing datas
        foreach($link as $l){
            $tempSelector = json_decode($l->selector);
            foreach($tempSelector as $t){
                if (!array_key_exists($t->name,$template)){
                    $template[$t->name]='';
                }
            }
            
        }
       
        //construct array for csv export
        foreach($link as $l){
            $tempContent = (array) json_decode($l->content);
            $tempOutput = [];
            $tempOutput['url'] = $l->url;
                foreach($template as $key=>$t)
                {
                    if(array_key_exists($key, $tempContent))
                    {
                        $tempOutput[$key] = $tempContent[$key];
                    }else{
                        $tempOutput[$key] = '';
                    }
                }
            
            $output[] = $tempOutput;
        }
       
        $domain = Domain::find($id);
       
        $headers = [
                'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
            ,   'Content-type'        => 'text/csv'
            ,   'Content-Disposition' => 'attachment; filename='.str_replace(' ','',$domain->name).'_'.date('d-m-Y').'.csv'
            ,   'Expires'             => '0'
            ,   'Pragma'              => 'public'
        ];
        
       
        
        # add headers for each column in the CSV download
        array_unshift($output, array_keys($output[0]));
       
        $callback = function() use ($output) 
            {
                $FH = fopen('php://output', 'w');
                foreach ($output as $row) { 
                    fputcsv($FH, $row);
                }
                fclose($FH);
            };
        return response()->stream($callback, 200, $headers);
    }
}
