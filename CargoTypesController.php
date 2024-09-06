<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CargoType;

class CargoTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = CargoType::orderBy('priority', 'ASC')->paginate(10);

        if ($request->ajax()) {
            $sort_by      = $request->get('sortby') ?? 10;
            $sort_type    = $request->get('sorttype');
            $search_query = $request->get('query');

            $data = CargoType::when(!empty($search_query), function ($query) use ($search_query) {
                            return $query->where('cargo_types.name', 'like', '%'.$search_query.'%')
                            ->orWhere('cargo_types.code', 'like', '%'.$search_query.'%');
                        })
                        ->orderBy('priority', 'ASC')
                        ->paginate($sort_by);

            return view('cargo-types.table', compact('data'));
        }
        else
        {
            return view('cargo-types.index',compact('data'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $priority = CargoType::max('priority');
        $priority = $priority != '' ? $priority : 0;

        return view('cargo-types.create', compact('priority'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:container_types',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->getMessageBag()->first(),
                    'data'    => []
                ]);
            }
            return redirect()->back()->with('error', $validator->getMessageBag()->first());
        }

        $data = $request->except([
            '_token',
            '_method'
        ]);

        $data['code']  = strtoupper($data['code']);
        $default_check = CargoType::where(['is_default' => 1,'status' => 1])->first();

        if (!$default_check) {
            $data['is_default'] = 1;
        }

        $quality = CargoType::create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cargo Type created successfully',
                'data'    => $quality
            ]);
        }
        return redirect()->route('cargo-types.index')
            ->with('success','Cargo Type created successfully');
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
        $type = CargoType::find($id);

        return view('cargo-types.edit',compact('type'));
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
        $validator = \Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:container_types,code,'.$id,
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->getMessageBag()->first(),
                    'data'    => []
                ]);
            }

            return redirect()->back()->with('error', $validator->getMessageBag()->first());
        }

        $data = $request->except([
            '_token',
            '_method'
        ]);
        $data['code'] = strtoupper($data['code']);


        $type = CargoType::find($id);

        if (!$type) {
            return redirect()->route('cargo-types.index')
                ->with('error','Cargo Type not found!');
        }

        $type->update($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Cargo Type updated successfully',
                'data'    => $type
            ]);
        }

        return redirect()->route('cargo-types.index')
            ->with('success','delivery Type updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $type = CargoType::find($request->id);

        if ($type->is_default == '1') {
            return response()->json([
                'success' => false,
                'message' => ['Default record can not be deleted.']
            ]);
        }

        $type->delete();

        $Redirect = 'cargo-types';

        return response()->json([
            'success' => true,
            'message' => ['Deleted successfully'],
            'data'    => [
                'redirect' => $Redirect,
            ]
        ]);
    }

    public function changeStatus(Request $request)
    {
        if ($request->ajax()) {
            $data   = array('status' => $request->status );
            $Update = CargoType::where('id', '=', $request->id)->update($data);

            if($Update){
                return response()->json([
                    'success'=>true,
                    'message'=>['Cargo Type status successfully change'],
                    'data'=>[
                       'redirect'=>'/users/',
                       'reload'=>true,
                    ]
                ]);
            } else {
                return response()->json([
                   'success'=>false,
                   'message'=>['Error for change status'],
                   'data'=>[
                       'redirect'=>'',
                   ]
                ]);
            }
        }
    }
    public function reverseCharge(Request $request)
    {
        if ($request->ajax()) {
            $data   = array('reverse_charge' => $request->status );
            $Update = CargoType::where('id', '=', $request->id)->update($data);

            if($Update){
                return response()->json([
                    'success'=>true,
                    'message'=>['Cargo Type reverse charge successfully change'],
                    'data'=>[
                       'redirect'=>'/users/',
                       'reload'=>true,
                    ]
                ]);
            } else {
                return response()->json([
                   'success'=>false,
                   'message'=>['Error for change reverse charge'],
                   'data'=>[
                       'redirect'=>'',
                   ]
                ]);
            }
        }
    }

       public function changedefault(Request $request)
    {
        if ($request->ajax()) {
            $data   = array('is_default' => $request->status );
            $count  = CargoType::where(['is_default' => $request->status])->count();

            if ($count > 0 && $request->status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => ['There is already a default Cargo Type!'],
                    'data'    => []
                ]);
            }

            $Update = CargoType::where('id', '=', $request->id)->update($data);

            if($Update){
                return response()->json([
                    'success'=>true,
                    'message'=>['Cargo Type default status successfully changed.'],
                    'data'=>[
                    ]
                ]);
            } else {
                return response()->json([
                   'success'=>false,
                   'message'=>['Error for change default'],
                   'data'=>[
                       'redirect'=>'',
                   ]
                ]);
            }
        }
    }
}
