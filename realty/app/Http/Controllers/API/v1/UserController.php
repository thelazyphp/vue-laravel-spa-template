<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Filters\UsersFilter;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);
        $filter = new UsersFilter($request);

        return UserResource::collection(
            auth()->user()
                ->company
                ->users()
                ->filter($filter)->paginate()
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
            'image_id' => 'nullable|integer|exists:images,id',
            'role'     => 'required|string|in:manager,employee',
            'f_name'   => 'required|string|max:191',
            'm_name'   => 'nullable|string|max:191',
            'l_name'   => 'required|string|max:191',
            'email'    => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $attributes = [
            'company_id' => auth()->user()->company_id,
            'password'   => Hash::make($request->password),
        ];

        return new UserResource(
            User::create(
                array_merge(
                    $attributes, $request->only(
                        'image_id',
                        'role',
                        'f_name',
                        'm_name',
                        'l_name',
                        'email'
                    )
                )
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int|string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id === 'self') {
            $user = auth()->user();
        } else {
            $user = User::findOrFail($id);
        }

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
        if ($id === 'self') {
            $user = auth()->user();
        } else {
            $user = User::findOrFail($id);
        }

        $this->authorize('update', $user);

        $rules = [
            'image_id' => 'nullable|integer|exists:images,id',
            'f_name'   => 'string|max:191',
            'm_name'   => 'nullable|string|max:191',
            'l_name'   => 'string|max:191',

            'email' => [
                'string',
                'email',
                'max:191',
                Rule::unique('users')->ignore($user),
            ],
        ];

        if ($user->id == auth()->id()) {
            $rules['cur_password'] = 'required_with:new_password|password';
            $rules['new_password'] = 'string|min:8|confirmed';
        } else {
            $rules['role']     = 'string|in:manager,employee';
            $rules['password'] = 'string|min:8';
        }

        $this->validate($request, $rules);

        if ($request->has('image_id')) {
            $user->image_id = $request->image_id;
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

        if ($user->id == auth()->id()) {
            if ($request->has('new_password')) {
                $user->password = Hash::make($request->new_password);
            }
        } else {
            if ($request->has('role')) {
                $user->role = $request->role;
            }

            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
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
        if ($id === 'self') {
            $user = auth()->user();
        } else {
            $user = User::findOrFail($id);
        }

        $this->authorize('delete', $user);
        $user->delete();
    }
}
