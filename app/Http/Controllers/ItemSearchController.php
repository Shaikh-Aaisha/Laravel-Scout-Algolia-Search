<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Item;

class ItemSearchController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('titlesearch')) {
            $items = Item::search($request->titlesearch)
                ->paginate(6);
        } else {
            $items = Item::paginate(6);
        }
        return view('item-search', compact('items'));
    }
    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function create(Request $request)
    {
        $this->validate($request, ['title' => 'required']);


        $items = Item::create($request->all());
        return back();
    }

    #API Function For Search

    public function searchapi(Request $request)
    {
            try 
            {

                if ($request->has('titlesearch')) {
                    $items = Item::search($request->titlesearch)->get();
                    return response()->json(
                        [
                            'status'       =>  "success",
                            'message'      =>  "Record Found",
                            'data' => $items
                        ],
                        200
                    );
                } else {
                    $items = Item::all();
                    return response()->json(
                        [
                            'status'       =>  "success",
                            'message'      =>  "All Records",
                            'data' => $items
                        ],
                        200
                    );
                }
            }
            catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => trans('validation.custom.invalid.request'),
                    'error'   => $e->getMessage()
                ], 500);
            }
        // }
    }

    #API Function For Create/Insert a Record

    public function createapi(Request $request)
    {
        $data = $request->only('title');
        $validator = Validator::make($data, [
            'title' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json(
                [
                    'status'    => 'failed',
                    'errors'    =>  $validator->errors(),
                    'message'   =>  trans('validation.custom.input.invalid'),
                ],
                400
            );
        } else {
            try {

                $items = Item::create($request->all());
                if ($items) {
                    return response()->json(
                        [
                            'status'       =>  "success",
                            'message'      =>  "Record Created  Successfuly!",
                            'data' => $items
                        ],
                        200
                    );
                } else {
                    return response()->json(
                        [
                            'status'       =>  "success",
                            'message'      =>  "Unable To Create Record",

                        ],
                        400
                    );
                }
            } catch (\Throwable $e) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => trans('validation.custom.invalid.request'),
                    'error'   => $e->getMessage()
                ], 500);
            }
        }
    }
}
