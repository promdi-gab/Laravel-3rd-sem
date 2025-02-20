<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Listener;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;



class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $artists = Artist::with('albums')->orderBy('artist_name', 'DESC')->get();
        $artists = Artist::all();
        dump($artists);
        dump($artists);
        foreach ($artists as $artist) {
            dump($artist);
            dump($artist->artist_name);
            dump($artist->albums); // ! lazy loaded with relationship one to many
            foreach ($artist->albums as $album) {
                dump($album->album_name);
            }
        }
        return View::make('artist.index', compact('artists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return View::make('artist.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $input = $request->all();
        // Artist::create($input);
        // return Redirect::to('artist');



        $input = $request->all();

        $request->validate([
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);
        if ($file = $request->hasFile('image')) {

            $file = $request->file('image');
            $fileName = uniqid() . '_' . $file->getClientOriginalName();
            $request->image->storeAs('images', $fileName, 'public');
            $input['img_path'] = 'images/' . $fileName;
            $artist = Artist::create($input);
        }
        return Redirect::to('artist');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $artist = Artist::find($id);

        return View::make('artist.edit', compact('artist'));
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
        $artist = Artist::find($id);

        $artist->update($request->all());
        return Redirect::to('/artist')->with('success', 'Artist updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $artist = Artist::find($id);
        $artist->albums()->delete();
        $artist->delete();
        $artist = Artist::with('albums')->get();
        // ! Delete artist and album same time 
    }
}
