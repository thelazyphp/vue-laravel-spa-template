<?php

namespace App\Http\Controllers\Api\V1\Catalog;

use Illuminate\Http\Request;
use App\Models\Catalog\Apartment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Catalog\ApartmentCollection;
use App\Http\Resources\Catalog\Apartment as ApartmentResource;

class ApartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * @param  \App\Models\Catalog\Apartment  $apartment
     * @return void
     */
    public function favorite(Apartment $apartment)
    {
        $apartment->favorite();
    }

    /**
     * @param  \App\Models\Catalog\Apartment  $apartment
     * @return void
     */
    public function unfavorite(Apartment $apartment)
    {
        $apartment->unfavorite();
    }

    /**
     * @param  \App\Models\Catalog\Apartment  $apartment
     * @return void
     */
    public function toggleFavorited(Apartment $apartment)
    {
        $apartment->toggleFavorited();
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'filter' => 'array',
            'sort' => 'string',
            'per_page' => 'integer|min:1',
        ]);

        return new ApartmentCollection(
            Apartment::where('is_published', true)
                ->filterBy($request->get('filter', []))
                ->sortBy($request->get('sort', '-published_at'))->paginate($request->get('per_page'))
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function getFavorited(Request $request)
    {
        $this->validate($request, [
            'filter' => 'array',
            'sort' => 'string',
            'per_page' => 'integer|min:1',
        ]);

        $favorited = Apartment::getFavorited()->map(function ($favorited) {
            return $favorited->id;
        });

        return new ApartmentCollection(
            Apartment::whereIn('id', $favorited)
                ->where('is_published', true)
                ->filterBy($request->get('filter', []))
                ->sortBy($request->get('sort', '-published_at'))->paginate($request->get('per_page'))
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
     * @param  \App\Models\Catalog\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        return new ApartmentResource($apartment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Catalog\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Apartment $apartment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Catalog\Apartment  $apartment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Apartment $apartment)
    {
        //
    }
}
