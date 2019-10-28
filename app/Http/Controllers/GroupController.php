<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\GroupCollection;
use App\Http\Resources\GroupResource;
use App\Models\Group;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return new GroupCollection(Group::all());
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
        if (!ini_get("auto_detect_line_endings")) {
            ini_set("auto_detect_line_endings", '1');
        }
        $content = $request->file('file');
        try {
            $fileData = file_get_contents($content);
            // PHP_EOL may not actually be usable due to operating system differences
            $lines = explode(PHP_EOL, $fileData);
            array_shift($lines);
            $updated = 0;
            $inserted = 0;
            $ignored = 0;
            foreach ($lines as $line) {
                if ($line === '') continue;
                $lineData = str_getcsv($line, ",");
                $primaryId = $lineData[0];
                if (is_numeric($primaryId)) { // update since id present (existing record)
                    $existingPrimaryId = Group::find($primaryId);
                    if($existingPrimaryId !== null) {
                        DB::table('groups')
                        ->where('id', $primaryId)
                        ->update([
                            'group_name' => $lineData[1]
                        ]);
                        $updated++;
                    } else {
                        $existingGroup = Group::find(['group_name' => $lineData[1]]);
                        // only enter if unique name
                        if ($existingGroup === null || count($existingGroup) === 0) {
                            Group::insert([
                                'group_name' => $lineData[1]
                            ]);
                            $inserted++;
                        } else {
                            $ignored++;
                        }
                    }

                } else { // new data = insert
                    $existingGroup = Group::find(['group_name' => $lineData[1]]);
                    // only enter if unique name
                    if ($existingGroup === null || count($existingGroup) === 0) {
                        Group::insert([
                            'group_name' => $lineData[1]
                        ]);
                        $inserted++;
                    } else {
                        $ignored++;
                    }
                }
            }
            $affected = $updated + $inserted;
            $statusCode = 200;
            if ($inserted > 0) {
                $statusCode = 201;
            }
            return response(array(
                    "feedback" => $affected . " rows affected, " . $inserted . " inserted, " . $updated . " updated, and " . $ignored . " ignored."
                    , "collection" => new GroupCollection(Group::all())
                ), $statusCode);
        } catch (Exception $e) {
            return response()->json(null, 422);
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
    }
}
