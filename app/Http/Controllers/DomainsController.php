<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Sandesh\TextAnalysis\Scrapers\WebScraper as ws;
use App\Project;
use App\Link;
use App\Domain;
use App\User;

class DomainsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('projects.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $project = Project::find($id);
       
        return view('domains.create')->with('project',$project);
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
            'description' => 'required',
            'url' => 'required',
            'selector' => 'required',
            'pagination_link' => 'required',
            'pagination_increment_scheme' => 'required',
            'pagination_end' => 'required'
        ]);
        
        $selector = array(
            'selector' => array(
                            'title' => array(
                                'css' => $request->input('selector'),
                                'type'=> array('_text','href')
                            )

            ),
            'pagination' => array(
                                'link' => $request->input('pagination_link'),
                                'incrementBy' => $request->input('pagination_increment_scheme'),
                                'end' => $request->input('pagination_end')
                            )
        );
        // Create domain
        $domain = new Domain;
        $domain->name = $request->input('name');
        $domain->description=$request->input('description');
        $domain->url=$request->input('url');
        $domain->selector=json_encode($selector);
        $domain->remarks=$request->input('remarks');
        $domain->project_id = $request->input('projectCode');
        $domain->save();

        return redirect('/projects/'.$request->input('projectCode'))->with('success','Domain Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $domain = Domain::find($id);
        $links = Link::where('domain_id', $domain->id)->orderBy('created_at', 'DESC')->paginate(20);
        //$this->debug(json_decode($domain->selector),1);
          $data = array(
                'domain' => $domain,
                'links' => $links,
                'selector'=>json_decode($domain->selector)
            );
            return view('domains.show')->with($data);
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $domain = Domain::find($id);
        //$selector  = json_decode($domain->selector);
        //$this->debug((object)$selector,1);
        $project = Project::find($domain->project_id);
        $data = array(
            'project'=>$project,
            'domain'=>$domain,
            'selector'=>json_decode($domain->selector)
        );
        //return view('domains.create')->with('project',$project);
        return view('domains.edit')->with($data);
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
            'description' => 'required',
            'url' => 'required',
            'selector' => 'required',
            'pagination_link' => 'required',
            'pagination_increment_scheme' => 'required',
            'pagination_end' => 'required'
        ]);
        
        //$this->debug($request,1);

        $selector = array(
            'selector' => array(
                            'title' => array(
                                'css' => $request->input('selector'),
                                'type'=> array('_text','href')
                            )

            ),
            'pagination' => array(
                                'link' => $request->input('pagination_link'),
                                'incrementBy' => $request->input('pagination_increment_scheme'),
                                'end' => $request->input('pagination_end')
                            )
        );
        // find domain
        $domain = Domain::find($id);
        $domain->name = $request->input('name');
        $domain->description=$request->input('description');
        $domain->url=$request->input('url');
        $domain->selector=json_encode($selector);
        $domain->remarks=$request->input('remarks');
        //$domain->project_id = $domain->project_id;
        $domain->save();

        return redirect('/domains/'.$domain->id)->with('success','Domain Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $domain = Domain::find($id);
        $links = Link::where('domain_id', $domain->id)->get();
        if(!empty($links)){
            $name = $domain->name;
            return redirect('/domains/'.$domain->id)->with('error','Domain "'.$name.'" has several Links.');
        }else{
            $name = $domain->name;
            $domain->delete();
            return redirect('/projects/'.$domain->project_id)->with('success','Domain "'.$name.'" Removed');
        }
        
        
    }


    

    /**
     * Preview data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response as json
     */
    public function preview(Request $request){

        $url = $request->input('url');
        $selector = array(
            'selector' => array(
                            'title' => array(
                                'css' => $request->input('selector'),
                                'type'=> array('_text','href')
                            )

            ),
            'pagination' => array(
                                'link' => $request->input('pagination_link'),
                                'incrementBy' => $request->input('pagination_increment_scheme'),
                                'end' => $request->input('pagination_end')
            ));

        
        return response()->json($this->pullData($url, $selector)); 
    }

    /**
     * Extract data from web
     *
     * @param  \Url of the web $url
     * @param  Css selector $selector
     * @return array $output
     */
    private function pullData($url, $selector){
         
         $css_selector = $selector['selector']['title']['css'];
         $paginateString = (string) $selector['pagination']['link'];
         $incrementBy = (int) $selector['pagination']['incrementBy'];
         $end = (int) $selector['pagination']['end'];
         $to_scrap = $selector['selector']['title']['type'];
 
         $scrap = new ws($url,$css_selector,$to_scrap);
         $output = $scrap->getLinksWithPagination($paginateString,$end,$incrementBy);
         return $output;

    }
     /**
     * Extract data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response as json
     */
    public function extract(Request $request){
        $domain_id = $request->input('domainCode');
        $domain = Domain::find($domain_id);

        $url = $domain->url;
        $ts = json_decode($domain->selector);
        //$this->debug($ts,1);
        $selector = array(
            'selector' => array(
                            'title' => array(
                                'css' => $ts->selector->title->css,
                                'type'=>'href'
                            )
                        ),
            'pagination' => array(
                                'link' => $ts->pagination->link,
                                'incrementBy' => $ts->pagination->incrementBy,
                                'end' => $ts->pagination->end
            ));

        $output = $this->pullData($url, $selector);

        
        $i=0;
        foreach($output as $temp){
            foreach($temp as $o){
                //save links to database
                $link = new Link;
                $link->url = $o;
                $link->urlSignature = md5($o);
                $link->isManual = 'N';
                $link->domain_id = $domain_id;
                //$this->debug($o,1);
                $link->save();
                $i++;
                unset($link);
            }
        }
        return redirect('/domains/'.$domain_id)->with('success','Successfully Added '.$i.' Links !!');

    }


    
}
