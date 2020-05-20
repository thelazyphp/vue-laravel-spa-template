<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
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
        $this->authorize('viewAny', User::class);

        return UserResource::collection(
            User::where('company_id', auth()->user()->company_id)->get()
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
        $this->authorize('create', User::class);

        $this->validate($request, [
            'role' => [
                'required',
                'string',
                Rule::in(User::VALID_ROLES),
            ],

            'f_name' => 'required|string|max:191',
            'm_name' => 'nullable|string|max:191',
            'l_name' => 'required|string|max:191',
            'email' => 'required|string|max:191|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'settings' => 'array',
        ]);

        $attributes = [
            'company_id' => auth()->user()->company_id,
            'password' => Hash::make($request->password),
        ];

        $user = User::create($request->only([
            'role',
            'f_name',
            'm_name',
            'l_name',
            'email',
        ]) + $attributes);

        $user->settings = $request->input('settings', User::DEFAULT_SETTINGS);
        $user->save();

        return new UserResource($user);
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id === 'current') {
            $id = auth()->id();
        }

        $user = User::findOrFail($id);
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($id === 'current') {
            $id = auth()->id();
        }

        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $rules = [
            'f_name' => 'string|max:191',
            'm_name' => 'nullable|string|max:191',
            'l_name' => 'string|max:191',

            'email' => [
                'string',
                'max:191',
                'email',
                Rule::unique('users')->ignore($user),
            ],

            'password' => 'required_with:new_password|password',
            'new_password' => 'string|min:8|confirmed',
            'settings' => 'array',
        ];

        if ($request->has('role')) {
            $this->authorize('updateRole', $user);

            $rules['role'] = [
                'required',
                'string',
                Rule::in(User::VALID_ROLES),
            ];
        }

        $this->validate($request, $rules);

        if ($request->has('role')) {
            $user->role = $request->role;
        }

        if ($request->has('f_name')) {
            $user->f_name = $request->f_name;
        }

        if ($request->has('m_name')) {
            $user->m_name = $request->m_name;
        }

        if ($request->has('l_name')) {
            $user->l_name = $request->l_name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        if ($request->has('settings')) {
            $user->settings = $request->settings;
        }

        $user->save();
        return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id === 'current') {
            $id = auth()->id();
        }

        $user = User::findOrFail($id);
        $this->authorize('delete', $user);

        $user->delete();
    }
}
