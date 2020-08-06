<?php

namespace App\Http\Controllers\API\v1;

use App\CatalogItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\CatalogItemResource;

class CatalogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CatalogItemResource::collection(
            CatalogItem::paginate()
        );
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CatalogItem  $catalogItem
     * @return \Illuminate\Http\Response
     */
    public function show(CatalogItem $catalogItem)
    {
        return new CatalogItemResource($catalogItem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CatalogItem  $catalogItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CatalogItem $catalogItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CatalogItem  $catalogItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(CatalogItem $catalogItem)
    {
        //
    }
}
