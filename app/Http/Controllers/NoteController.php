<?php

namespace App\Http\Controllers;

use App\Note;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(auth()->user()->notes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:190',
            'description' => 'nullable|string|max:10000'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $note = new Note($validator->validated());

        $request->user()->notes()->save($note);

        return response()->json($note);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function show(Note $note)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function edit(Note $note)
    { }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        if (request()->user()->id !== $note->user_id) {
            throw new ModelNotFoundException();
        }
        $json = json_decode($request->getContent());

        $validator = Validator::make((array) $json, [
            'title' => "required|string|max:190",
            'description' => "nullable|string|max:10000"
        ]);
        // dd($request->all(), $note);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $note->update($validator->validated());

        return response()->json($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note  $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        if ($note->user_id !== request()->user()->id) {
            throw new ModelNotFoundException();
        }
        $note->delete();
        return response()->json(['message' => 'Note Deleted!']);
    }
}
