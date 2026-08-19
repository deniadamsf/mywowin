<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
//import return type redirectResponse
use Illuminate\Http\RedirectResponse;

class UserControllerTemp extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request):RedirectResponse

    // jadi gini ken, kamu mau nyinpan data user beserta data membernya? soalnya di membersip ada user_id
    // iyaaa tapi controllernya kepisah gitu a,p aja djia dsai 

    // gini kamu insert dulu buat usernya 

    // gini ken tak kasi alurnya kalo semisal kamu gatau tanya aja ai
    // kamu pas insert ada errornya gak?
    // pas insert nggak da eror nya tapi balik ke halaman create lagi

    {
        dd("masuk");
        // coba keluar ga ini dd nya
        // ini input dulu?, pokok submit, kan kalo di submi masuk ke kontroller ini
        
        // ada eror ini Route [users.store] not defined. masih baru ku refresh sek

        // aneh, kamu ini yang baut controller pake php artisan make:controller UserController --resource ga? -- ini
        // iya kayak nya:( aku gatau huhuhu
        // faqih apa aku buat baru aja? coba ii\

        // ini diganti namanya aja
        //maksutnya nggak bikin folder baru?
        // udah tak ganti jadi temp, kamu buat controller baru
        // udah, aku edit disini? euy  kok kodenya sama?
        //belum ihh:( iah kok ada dua file
        // tadi katanya kamu bikinn yang file 
        
        // php artisan make:controller UserController --resource

        // ganti dulu, udah anama file gitu di foldermu, error nntya
        // yang usercontroller ku hapus ya bebas pokok ada backup kodenya
        //  sudahh   wait ku bikin baru
        // eh di folder admin apa publik
        // admin admin/UserController
        // sudahh kok ggada adaaa
        // belum masuk ? iyacoba kamu ngarah kesana 
        // cara biar bisam following kayak kamu tadi gimana, kamu pindah aja fikenya, masuk ke file yang baru dibikin, sudah?
    //    belum keliatan? kiirm lago lnknya
    
        $request->validate([
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,member',
        ]);

        $user = User::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'status_aktif' => 1,
        ]);



       
        // 

        if ($request->hasFile('foto_profile')) {
            $fotoPath = $request->file('foto_profile')->store('foto_profiles', 'public');
            $user->update(['foto_profile' => $fotoPath]);
        }

         // nah ini
        // $userId diambil dari variabel $user, lalu user id itu dipake buat insert data ke membersih
        /**
         *  Membership::create([
         *      user_id = $user->id
         * 
         * ])
         * 
         */

        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->hasFile('foto_profile')) {
            $fotoPath = $request->file('foto_profile')->store('foto_profiles', 'public');
            $user->update(['foto_profile' => $fotoPath]);
        }

        return redirect()->route('user.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus.');
    }
}
