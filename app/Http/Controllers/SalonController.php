<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSalonRequest;
use App\Http\Resources\SalonResource;
use App\Models\Salon;
use Illuminate\Http\Request;

class SalonController extends Controller
{
    public function index()
    {
        $salons= Salon::latest()->paginate(10);
        if(count($salons) >0){
            if($salons->total()>$salons->perPage()){
                $data=[
                    'reacodes'=>SalonResource::collection($salons),
                    'pagination'=>[
                        'total'=>$salons->total(),
                        'current_page'=>$salons->currentPage(),
                        'last_page'=>$salons->lastPage(),
                        'per_page'=>$salons->perPage(),
                        'next_page_url'=>$salons->nextPageUrl(),
                        'prev_page_url'=>$salons->previousPageUrl(),
                    ]
                    ];
                return ApiResponse::sendResponse(200,'fetch all salons successfully',$data);
            }
            return ApiResponse::sendResponse(200,'fetch all salons successfully',SalonResource::collection($salons));
        

        }
        
        return ApiResponse::sendResponse(200,'no salon found',[]);
    }


    public function latest(){
        $salon= Salon::latest()->take(2)->get();
        if(count($salon)>0)
        return ApiResponse::sendResponse(200,'fetch latest salons successfully',SalonResource::collection($salon));
         return ApiResponse::sendResponse(200,'no salon found',[]);

    }
    public function show($id){
        $salon= Salon::find($id);
        if($salon)
        return ApiResponse::sendResponse(200,'fetch salon successfully',new SalonResource($salon));
         return ApiResponse::sendResponse(200,'no salon found',[]);

    }
    public function store(StoreSalonRequest $request){
       $validated=$request->validated();
         $salon= Salon::create($validated);
            if($salon)
            return ApiResponse::sendResponse(201,'salon created successfully',new SalonResource($salon));
            return ApiResponse::sendResponse(500,'failed to create salon',[]);

    }
    public function update(StoreSalonRequest $request,$id){
        $data=$request->validated();
        $salon= Salon::findorfail($id);
        $update=$salon->update($data);
        if($update)
        return ApiResponse::sendResponse(200,'salon updated successfully',new SalonResource($data));   
        return ApiResponse::sendResponse(500,'failed to update salon',[]);
       

}
public function delete($id){
    $salon=Salon::find($id);
    $success=$salon->delete;
     if ($success) 
     return ApiResponse::sendResponse(200, 'salon deleted successfully', []);


}
}
