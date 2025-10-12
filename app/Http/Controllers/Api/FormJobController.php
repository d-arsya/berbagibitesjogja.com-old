<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormJob;
use Illuminate\Http\Request;

class FormJobController extends Controller
{
    public function index()
    {
        $data = FormJob::all();
        $data = $data->map(function ($e) {
            return $e["data"];
        });
        return response()->json($data);
    }

    public function update(Request $request)
    {
        $entries = $request->all()['entries'];
        // return response()->json($entries[0]);
        FormJob::truncate();
        if (count($entries)) {
            foreach ($entries as $entry) {
                $ed = uniqid();
                $entry['id'] = $ed;
                foreach ($entry['jobs'] as $i => $job) {
                    $entry['jobs'][$i]['id'] = uniqid(); // ✅ Actually modifies $entry
                }
                FormJob::create(['id' => $ed, 'data' => $entry]);
            }
            $data = FormJob::all();
            $data = $data->map(function ($e) {
                return $e["data"];
            });
            return response()->json($data);
        } else {

            return response()->json([]);
        }
    }
}
