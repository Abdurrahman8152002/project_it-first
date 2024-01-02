<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class Roles extends Controller
{

    public function assignRole(Request $request, $id)
    {
        $user = User::find($id);
        $role = Role::findByName($request->role);
        $user->assignRole($role);
        return response()->json(['message' => 'Role assigned successfully']);
    }

}
