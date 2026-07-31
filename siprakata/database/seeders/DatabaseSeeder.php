<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {

        // ── Buat Roles ─────────────────────────────────────────────────────
        $roles = [
            ['name'=>'admin',      'display_name'=>'Administrator',  'description'=>'Akses penuh ke semua fitur'],
            ['name'=>'dosen',      'display_name'=>'Dosen',          'description'=>'Kelola jadwal, materi, nilai, presensi, pengumuman'],
            ['name'=>'mahasiswa',  'display_name'=>'Mahasiswa',      'description'=>'Lihat jadwal, nilai, presensi, materi'],
            ['name'=>'guest',      'display_name'=>'Guest',          'description'=>'Hanya info publik'],
        ];
        foreach ($roles as $r) Role::firstOrCreate(['name'=>$r['name']], $r);

        // ── Buat Permissions ────────────────────────────────────────────────
        $modules = ['mahasiswa','dosen','matakuliah','prodi','ruangan','tahun_ajaran','krs','jadwal','presensi','materi','nilai','pengumuman'];
        $actions = ['view','create','edit','delete'];
        foreach ($modules as $mod) {
            foreach ($actions as $act) {
                Permission::firstOrCreate(
                    ['name' => "$mod.$act"],
                    ['display_name' => ucfirst($act).' '.ucfirst($mod), 'module' => $mod]
                );
            }
        }

        // ── Assign permissions ke role ──────────────────────────────────────
        $admin     = Role::where('name','admin')->first();
        $dosen     = Role::where('name','dosen')->first();
        $mahasiswa = Role::where('name','mahasiswa')->first();

        // Admin: semua permissions
        $admin->permissions()->sync(Permission::all()->pluck('id'));

        // Dosen: view semua + CRUD jadwal, materi, nilai, presensi, pengumuman
        $dosenPerms = Permission::where('module','like','%')
            ->where('name','like','%.view')
            ->pluck('id')->toArray();
        $dosenCrud = Permission::whereIn('module',['jadwal','materi','nilai','presensi','pengumuman'])->pluck('id')->toArray();
        $dosen->permissions()->sync(array_unique(array_merge($dosenPerms, $dosenCrud)));

        // Mahasiswa: view only untuk jadwal, nilai, presensi, materi
        $mhsPerms = Permission::whereIn('module',['jadwal','nilai','presensi','materi'])
            ->where('name','like','%.view')->pluck('id');
        $mahasiswa->permissions()->sync($mhsPerms);

        // ── Buat User Default ───────────────────────────────────────────────
        $adminRole = Role::where('name','admin')->first();
        User::firstOrCreate(
            ['email' => 'admin@kampus.ac.id'],
            ['name' => 'Admin Akademik', 'password' => Hash::make('admin123'), 'role_id' => $adminRole->id]
        );

        $dosenRole = Role::where('name','dosen')->first();
        User::firstOrCreate(
            ['email' => 'dosen@kampus.ac.id'],
            ['name' => 'Dosen Pengampu', 'password' => Hash::make('dosen123'), 'role_id' => $dosenRole->id]
        );

        $this->command->info('Seeder selesai! User default:');

        // ── Data dummy untuk testing ──────────────────────────────────────────
        $this->call(DummyDataSeeder::class);
        $this->call(AdditionalDummySeeder::class);
    }
}