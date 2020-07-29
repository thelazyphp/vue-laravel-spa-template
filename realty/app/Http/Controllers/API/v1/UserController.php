<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny');

        return UserResource::collection(
            auth()->user()->employees()->paginate()
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
            'role' => 'required|string|max:191|in:manager,employee',
            'f_name' => 'required|string|max:191',
            'm_name' => 'nullable|string|max:191',
            'l_name' => 'required|string|max:191',
            'email' => 'required|string|max:191|email|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $attributes = [
            'company_id' => auth()->user()->company_id,
            'password' => Hash::make($request->password),
        ];

        return new UserResource(
            User::create(
                array_merge(
                    $attributes, $request->only('role', 'f_name', 'm_name', 'l_name', 'email')
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
        if ($id === 'self') {
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
        ];

        $managerRules = [
            'role' => 'string|max:191|in:manager,employee',
            'password' => 'string|min:8',
        ];

        $employeeRules = [
            'password' => 'required_with:new_password|password',
            'new_password' => 'string|min:8|confirmed',
        ];

        $this->validate(
            $request, array_merge(
                $rules, $user->isManager() ? $managerRules : $employeeRules
            )
        );

        if ($user->isManager() && $request->has('role')) {
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

        if ($user->isManager() && $request->has('password')) {
            $user->password = Hash::make($request->password);
        } elseif ($request->has('new_password')) {
            $user->password = Hash::make($request->new_password);
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
            $id = auth()->id();
        }

        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();
    }
}
