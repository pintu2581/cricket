<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\TokenGenerateController;
use App\Http\Controllers\Api\TournamentController;
use App\Models\Country;
use App\Models\Tournament;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LeagueController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $tournament =  $this->insertTournament();
        $data = [
            'count_user' => Tournament::latest()->count(),
            'menu'       => 'menu.v_menu_admin',
            'content'    => 'content.view_league',
            'title'    => 'Table League',
            'tournament'    => $tournament,
        ];

        if ($request->ajax()) {
            $q_tournament = Tournament::select('*')->orderByDesc('created_at');
            return DataTables::of($q_tournament)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $html = "<span class=''>On</span>";
                    if($row['status'] == 0){
                        $html = "<span class=''>Off</span>";
                    }

                    return $html;
                })
                ->addColumn('action', function($row){

                    $btn = '<div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="btn btn-sm btn-icon btn-outline-success btn-circle mr-2 edit editUser"><i class=" fi-rr-edit"></i></div>';
//                    $btn = $btn.' <div data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-sm btn-icon btn-outline-danger btn-circle mr-2 deleteUser"><i class="fi-rr-trash"></i></div>';

                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('layouts.v_template',$data);
    }

    public function insertTournament(){
        $tokenObj = new TokenGenerateController();
        $token = $tokenObj->checkToken();
        $tournament = new TournamentController();
        $tournaments = $tournament->getTournamentResponse($token);
        foreach ($tournaments as $tournament){
            Tournament::updateOrInsert($tournament,['key'=> $tournament['key']]);
        }

        return Tournament::all()->toArray();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Tournament::updateOrCreate(['id' => $request->id],
            [
                'position' => $request->position,
                'status' => $request->status,
            ]);

        return response()->json(['success'=>'League saved successfully!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Tournament = Tournament::find($id);
        return response()->json($Tournament);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
