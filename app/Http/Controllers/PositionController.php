<?php

namespace App\Http\Controllers;

use App\Position;
use App\Shift;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class PositionController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Note:
        //  - loop and check if index is not equal to null
        //  - if not equal to null, sort based on it's index, 0 - highest
        //  - otherwise, based on its id
        $notSorted = Position::whereNull('index')->count();
        // if it hasn't been sorted, just display based on the current id
      
        if ( $notSorted > 0 ) 
        return ['data' => Position::all()];
  
        return ['data' => Position::orderBy('index', 'asc')->get()];
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // public function store(Request $request)
    // {
    //     try {
    //         return Position::create(['position' => $request->get('position')]);
    //     } catch (QueryException $e) {
    //         $errorCode = $e->errorInfo[1];
    //         if ($errorCode == 1062) {
    //             return response('The position you input existed already!', 500);
    //         }
    //     }
    // }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  \App\Position  $position
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show(Position $position)
    // {
    //     return $position;
    // }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  \App\Position  $position
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, Position $position)
    // {   
       
    //     try {
    //         $position->position = $request->get('position');
    //         $position->save();
    //         return response('Position updated!', 204);
    //     } catch (QueryException $e) {
    //         $errorCode = $e->errorInfo[1];
    //         if ($errorCode == 1062) {
    //             return response('The position you input existed already!', 500);
    //         }
    //     }
    // }

    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create(Request $request)
    {
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $last_records = Position::orderBy('id', 'desc')->first();
            $new_posindex = 0;
            if(isset($last_records)){
                $new_posindex = $last_records['id'];
            }
            $position = new Position();
            $position->position =$request->position;
            $position->index = $new_posindex;
            $position->save();

            if($position):
                $response = ['status'=>true,'message'=>'New Position Added Successfully!'];          
            else:
                $response = ['status'=>false,'message'=>'New Postion Not Added!'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }
 

      /**
        * Update the specified resource in storage.
        *
        * @param  int  $id
        * @return Response
        */
    public function update(Request $request ,$id)
    {
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];

            $data = [ 'position' =>$request->position];
            $position = Position::where('id',$id)->update($data);
            
            if($position):
                $response = ['status'=>true,'message'=>'Position Updated Successfully!'];          
            else:
                $response = ['status'=>false,'message'=>'Position Not Updated!'];
            endif;
            return response($response);
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        }
    }
    


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy($postion_id)
    {
        try{
            $response = ['status'=>false,'message'=>'Something Wrong'];
            $position =Position::find($postion_id);
            $position->delete();

            if($position):
                $shift = Shift::where('position_id',$postion_id)->delete();
                $response = ['status'=>true,'message'=>'Successfully removed!'];          
            else:
                $response = ['status'=>false,'message'=>'Not removed Successfully!'];
            endif;

            return response($response);
        }catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()]);   
        } 
    }

    /**
     * Show all deleted positions (soft deletion)
     * 
     * @return [type] [description]
     */
    public function showTrashed()
    {
        return Position::onlyTrashed()->get();
    }

    /**
     * Restore trashed position
     *
     * @param  int  $id
     * @return [type] [description]
     */
    public function restoreTrashed($id)
    {
        $position = Position::onlyTrashed()->find($id);

        if (!$position) {
            return response('Position not found!', 404);
        }

        $position->restore();
        return response('', 204);
    }

    /**
     * Sort position(s)
     * 
     * @return [type] [description]
     */
    public function sort(Request $request)
    {
       
        foreach ($request->get('data') as $k => $v) {
            $position = Position::find( $v['position_id'] );
            $position->index = $v['index'];
            $position->save();
        }
        return response('Sorted', 200);
    }

    
}
