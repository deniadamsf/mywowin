<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Http\Request;

class AdminMembershipController extends Controller
{
    public function index()
    // merah semua gini
    // gatauuu huhuh
    {
        $memberships = Membership::with('user')->get();
        return view('admin.memberships.index', compact('memberships'));
    }

    public function create()
    {
        $members = User::where('role', 'member')->get();
        return view('admin.memberships.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_toko' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'nama_sales' => 'nullable',
            'level_membership' => 'required|in:Silver,Gold,Platinum',
        ]);

        Membership::create($request->all());

        return redirect()->route('memberships.index')->with('success', 'Membership berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $membership = Membership::findOrFail($id);
        $members = User::where('role', 'member')->get();
        return view('admin.memberships.edit', compact('memberships', 'members'));
    }

    public function update(Request $request, $id)
    {
        $membership = Membership::findOrFail($id);

        $membership->update($request->all());

        return redirect()->route('memberships.index')->with('success', 'Membership berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $membership = Membership::findOrFail($id);
        $membership->delete();

        return redirect()->route('memberships.index')->with('success', 'Membership berhasil dihapus.');
    }
}
