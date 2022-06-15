<?php

namespace App\Http\Controllers;

use App\Exports\PlayersExport;
use App\Imports\PlayersImport;
use App\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $players = ($request->search) ?
            Player::where('name', 'LIKE', '%' . $request->search . '%')->orderBy('id', 'desc')->paginate(10) :
            Player::orderBy('id', 'desc')->paginate(10);

        return view('pages.players.index', ['players' => $players]);

        //if($request->search){
        //  $players = Player::where('name', 'LIKE', '%' . $request->search . '%')->orderBy('id', 'desc')->paginate(10);
        //}else{
        //  $players = Player::orderBy('id', 'desc')->paginate(10);
        //}
        //return view('pages.players.index', ['players' => $players]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.players.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'          => 'required',
            'address'       => 'required',
            'description'   => 'required',
            'retired'       => 'required|boolean',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //Player::create($request->all());
        $player                 = new Player();
        $player->name           = $request->name;
        $player->address        = $request ->address;
        $player->description    = $request->description;
        $player->retired        = $request->retired;
        $player->save();

        if ($request->file('image')) {
            // Get Image File
            $imagePath = $request->file('image');
            // Define Image Name
            $imageName = $player->id . '_' . time() . '_' . $imagePath->getClientOriginalName();
            // Save Image on Storage
            $path = $request->file('image')->storeAs('images/players/' . $player->id, $imageName, 'public');
            //Save Image Path
            $player->image = $path;
        }

        $player->save();

        return redirect('players')->with('status','Player created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function show(Player $player)
    {
        return view('pages.players.show', ['player' => $player]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function edit(Player $player)
    {
        return view('pages.players.edit', ['player' => $player]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $player)
    {
        $player              = Player::find($player->id);
        $player->name        = $request->name;
        $player->address     = $request->address;
        $player->description = $request->description;
        $player->retired     = $request->retired;

        if ($request->file('image')) {

            if($player->image) Storage::delete('public/'.$player->image);
            // Get Image File
            $imagePath = $request->file('image');
            // Define Image Name
            $imageName = $player->id . '_' . time() . '_' . $imagePath->getClientOriginalName();
            // Save Image on Storage
            $path = $request->file('image')->storeAs('images/players/' . $player->id, $imageName, 'public');
            //Save Image Path
            $player->image = $path;
        }

        $player->save();

        return redirect('players')->with('status','Player edited successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        Storage::deleteDirectory('public/images/players/' . $player->id);
        $player->delete();
        return redirect('players')->with('status','Player deleted successfully!');;
    }

    public function truncate()
    {
        Player::truncate();
        return redirect('players')->with('status','Players deleted successfully!');;
    }

    public function export()
    {
        return Excel::download(new PlayersExport, 'players.xlsx');
    }

    public function import()
    {
        Excel::import(new PlayersImport(), request()->file('file'));

        return redirect('/players')->with('success', 'All good!');
    }

    //public function search(Request $request)
    //{
        // $players = Player::where('name', 'LIKE', '%' . $request->search . '%')->paginate(10);
        // return view('pages.players.index', ['players' => $players]);
    //}
}
