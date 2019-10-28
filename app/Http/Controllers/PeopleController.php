<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\PeopleCollection;
use App\Http\Resources\PersonResource;
use App\Models\Person;

class PeopleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activePeople = Person::where('status','active')->get();
        return new PeopleCollection($activePeople);
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
            // remove first line (header)
            array_shift($lines);
            $updated = 0;
            $inserted = 0;
            foreach ($lines as $line) {
                if ($line === '') continue;
                $lineData = str_getcsv($line, ",");
                $primaryId = $lineData[0];
                if (is_numeric($primaryId)) { // update since id present (existing record)
                    if (Person::find($primaryId) !== null) {
                        DB::table('people')
                        ->where('id', $primaryId)
                        ->update([
                            'first_name' => $lineData[1],
                            'last_name' => $lineData[2],
                            'email_address' => $lineData[3],
                            'status' => $lineData[4]
                        ]);
                        $updated++;
                    } else {
                        Person::insert([
                            'first_name' => $lineData[1],
                            'last_name' => $lineData[2],
                            'email_address' => $lineData[3],
                            'status' => $lineData[4]
                        ]);
                        $inserted++;
                    }
                } else { // new data = insert
                    Person::insert([
                        'first_name' => $lineData[1],
                        'last_name' => $lineData[2],
                        'email_address' => $lineData[3],
                        'status' => $lineData[4]
                    ]);
                    $inserted++;
                }
            }
            $affected = $updated + $inserted;
            $statusCode = 200;
            if ($inserted > 0) {
                $statusCode = 201;
            }
            return response(array(
                    "feedback" => $affected . " rows affected, " . $inserted . " inserted and " . $updated . " updated."
                    , "collection" => new PeopleCollection(Person::where('status','active')->get())
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
        return new PersonResource(Person::findOrFail($id));
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
        $person = Person::findOrFail($id);
        $person->update($request->all());

        return response()->json(null, 204);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $person = Person::findOrFail($id);
        $person->delete();

        return response()->json(null, 204);
    }
}
