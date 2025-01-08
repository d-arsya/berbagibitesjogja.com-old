<?php

namespace Database\Seeders;

use App\Models\Volunteer\Division;
use App\Models\Volunteer\Faculty;
use App\Models\Volunteer\Program;
use App\Models\Volunteer\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    // private $faculties = [
    //     "Biologi", "Ekonomi Bisnis", "Filsafat", "Fisipol", "Geografi",
    //     "Hukum", "Ilmu Budaya", "Kedokteran", "Kedokteran Gigi",
    //     "Kedokteran Hewan", "Kehutanan", "MIPA", "Pascasarjana",
    //     "Pertanian", "Peternakan", "Psikologi", "Teknologi Pertanian",
    //     "Vokasi","Teknik","Farmasi","Lainnya","Kontributor"
    // ];
    private $data = [
        ['Biologi', ['Biologi']],
        ['Ekonomi Bisnis', ['Akuntansi', 'Ilmu Ekonomi', 'Manajemen']],
        ['Farmasi', ['Farmasi']],
        ['Filsafat', ['Filsafat']],
        ['Geografi', ['Geografi Lingkungan', 'Kartografi dan Penginderaan Jauh', 'Pembangunan Wilayah']],
        ['Hukum', ['Hukum']],
        ['Ilmu Budaya', [
            'Antropologi Budaya',
            'Arkeologi',
            'Bahasa dan Kebudayaan Korea',
            'Bahasa dan Sastra Indonesia',
            'Pariwisata',
            'Sastra Arab',
            'Sastra Inggris',
            'Bahasa, Sastra, dan Budaya Jawa',
            'Bahasa dan Kebudayaan Jepang',
            'Bahasa dan Sastra Prancis',
            'Sejarah',
        ]],
        ['Fisipol', [
            'Ilmu Hubungan Internasional',
            'Ilmu Komunikasi',
            'Manajemen dan Kebijakan Publik',
            'Pembangunan Sosial dan Kesejahteraan',
            'Politik dan Pemerintahan',
            'Sosiologi',
        ]],
        ['Kedokteran Gigi', ['Higiene Gigi', 'Kedokteran Gigi']],
        ['Kedokteran Hewan', ['Kedokteran Hewan']],
        ['Kedokteran', ['Gizi', 'Ilmu Keperawatan', 'Kedokteran']],
        ['Kehutanan', ['Kehutanan']],
        ['MIPA', [
            'Elektronika dan Instrumentasi',
            'Fisika',
            'Geofisika',
            'Ilmu Aktuaria',
            'Ilmu Komputer',
            'Kimia',
            'Matematika',
            'Statistika',
        ]],
        ['Pertanian', [
            'Agronomi',
            'Akuakultur',
            'Ekonomi Pertanian dan Agribisnis',
            'Ilmu Tanah',
            'Manajemen Sumberdaya Akuatik',
            'Mikrobiologi Pertanian',
            'Penyuluhan dan Komunikasi Pertanian',
            'Proteksi Tanaman',
            'Teknologi Hasil Perikanan',
        ]],
        ['Peternakan', ['Ilmu dan Industri Peternakan']],
        ['Psikologi', ['Psikologi']],
        ['Teknik', [
            'Arsitektur',
            'Perencanaan Wilayah dan Kota',
            'Teknik Biomedis',
            'Teknik Elektro',
            'Teknik Fisika',
            'Teknik Geodesi',
            'Teknik Geologi',
            'Teknik Industri',
            'Teknik Kimia',
            'Teknik Mesin',
            'Teknik Nuklir',
            'Teknik Sipil',
            'Teknologi Informasi',
            'Teknik Infrastruktur Lingkungan',
            'Teknik Sumber Daya Air',
        ]],
        ['Teknologi Pertanian', [
            'Teknik Pertanian',
            'Teknologi Industri Pertanian',
            'Teknologi Pangan dan Hasil Pertanian',
        ]],
        ['Vokasi', [
            'Manajemen Informasi Kesehatan',
            'Pengelolaan Hutan',
            'Pengembangan Produk Agroindustri',
            'Sistem Informasi Geografis',
            'Teknik Pengelolaan dan Pemeliharaan Infrastruktur Sipil',
            'Teknik Pengelolaan dan Perawatan Alat Berat',
            'Teknologi Rekayasa Elektro',
            'Teknologi Rekayasa Instrumentasi dan Kontrol',
            'Teknologi Rekayasa Internet',
            'Teknologi Rekayasa Mesin',
            'Teknologi Rekayasa Pelaksanaan Bangunan Sipil',
            'Teknologi Rekayasa Perangkat Lunak',
            'Teknologi Survei dan Pemetaan Dasar',
            'Teknologi Veteriner',
            'Akuntansi Sektor Publik',
            'Bahasa Inggris',
            'Bahasa Jepang untuk Komunikasi Bisnis dan Profesional',
            'Bisnis Perjalanan Wisata',
            'Manajemen dan Penilaian Properti',
            'Pembangunan Ekonomi Kewilayahan',
            'Pengelolaan Arsip dan Rekaman Informasi',
            'Perbankan',
        ]],
        ['Lainnya', []],
        ['Kontributor', []],
        ['Pascasarjana', [
            'Pascasarjana',
        ]],
    ];

    private $division = ['Food', 'Sosmed', 'Fund', 'Operational Manager', 'Friend', 'Volunteer', 'Bendahara', 'Sekretaris'];

    public function run(): void
    {
        foreach ($this->division as $item) {
            Division::create(['name' => $item]);
        }
        foreach ($this->data as $number => $item) {
            Faculty::create(['name' => $item[0]]);
            foreach ($item[1] as $program) {
                Program::create([
                    'faculty_id' => $number + 1,
                    'name' => $program,
                ]);
            }
        }
        User::create([
            'name' => 'Kamaluddin Arsyad',
            'email' => env('EMAIL_ACC'),
            'role' => 'super',
            'program_id' => 83,
            'phone' => '6289636055420',
            'division_id' => 2,
        ]);
    }
}
