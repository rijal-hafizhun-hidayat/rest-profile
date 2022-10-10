<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProfileController extends Controller
{
    public function index()
    {
        if (Profile::all()->isEmpty()) {
            $response = [
                'status' => true,
                'message' => 'no data found'
            ];

            return response()->json($response, Response::HTTP_OK);
        }

        $data = Profile::all();
        $response = [
            'status' => true,
            'message' => 'show data profile',
            'data' => $data
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function store(Request $request)
    {
        $credentials = $request->validate(
            [
                'name' => 'required',
                'gender' => 'required|numeric'
            ],
            [
                'name.required' => 'forn name wajib di isi',
                'gender.required' => 'form gender wajib di isi',
                'gender.numeric' => 'data gender tidak valid'
            ]
        );

        $data = Profile::create($credentials);

        $response = [
            'status' => true,
            'message' => 'profile created',
            'data' => $data
        ];

        return response()->json($response, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $data = Profile::findOrFail($id);

        $response = [
            'status' => true,
            'message' => 'show profile',
            'data' => $data
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $credentials = $request->validate(
            [
                'name' => 'required',
                'gender' => 'required|numeric'
            ],
            [
                'name.required' => 'forn name wajib di isi',
                'gender.required' => 'form gender wajib di isi',
                'gender.numeric' => 'data gender tidak valid'
            ]
        );

        Profile::findOrFail($id)->update($credentials);

        $response = [
            'status' => true,
            'message' => 'update profile success'
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        Profile::destroy($id);

        $response = [
            'status' => true,
            'message' => 'success delete'
        ];

        return response()->json($response, Response::HTTP_OK);
    }

    public function count(){
        $response = Profile::select(Profile::raw('count(*) as total'))->groupBy('gender')->get();
        return response()->json($response, Response::HTTP_OK);
    }
}
