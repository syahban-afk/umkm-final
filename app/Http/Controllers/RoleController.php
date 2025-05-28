<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    /**
     * Mengubah role user dari customer menjadi admin
     */
    public function becomeAdmin()
    {
        $user = User::find(Auth::id());

        if ($user->role !== 'admin') {
            $user->role = 'admin';
            $user->save();

            return redirect()->route('dashboard')
                ->with('success', 'Selamat! Anda sekarang memiliki akses sebagai penjual.');
        }

        return redirect()->route('dashboard')
            ->with('error', 'Anda sudah terdaftar sebagai penjual.');
    }
}
