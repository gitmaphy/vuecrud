<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Person;
use App\Http\Resources\Person as PersonResource;


class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        if (isset($_GET['search'])) { 
            $search = $_GET['search'];
        } else { $search = '';}
        

        $persons = Person::where('first_name', 'like', "%{$search}%")
        ->orWhere('last_name', 'like', "%{$search}%")
        ->orderBy('last_name','asc')->paginate(3);

        
 
        
        return PersonResource::collection($persons);
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
        //
        $person = $request->isMethod('put') ? Person::findOrFail($request->person_id) : new Person;

        $person->id = $request->input('person_id');
        $person->first_name = $request->input('first_name');
        $person->last_name = $request->input('last_name');
        $person->birthday = $request->input('birthday');

        if($person->save()) {
            return new PersonResource($person);
        }
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
        $person = Person::findOrFail($id);
        return new PersonResource($person);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $person = Person::findOrFail($id);
        if($person->delete()) {
            return new PersonResource($person);
        }
        
    }
}
