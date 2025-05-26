<?php

namespace Database\Seeders;

use App\Models\Foundation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoundationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $foundations = [
            [
                "name" => "Yayasan La Tahzan",
                "latitude" => "-7.805317521799368",
                "longitude" => "110.40348201945216",
                "address" => "Gg. Irawan No.21, Pringgolayan, Banguntapan, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198",
                "phone" => "6281386942998"
            ],
            [
                "name" => "Yayasan Al Kahfi",
                "latitude" => "-7.765742418743407",
                "longitude" => "110.37059995190677",
                "address" => "Jetisharjo JT II/515, Cokrodiningratan, Kec. Jetis, Kota Yogyakarta, Daerah Istimewa Yogyakarta 55233",
                "phone" => "6285727267630"
            ],
            [
                "name" => "Rumah Yoel",
                "latitude" => "-7.761787898854957",
                "longitude" => "110.40714390357672",
                "address" => "Jl. Sepakbola No.3a, Ngropoh, Condongcatur, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281",
                "phone" => ""
            ],
            [
                "name" => "TPA Samirono",
                "latitude" => "-7.778563719088217",
                "longitude" => "110.38213920181438",
                "address" => "Jl. Anggrek No.65, Samirono, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55222",
                "phone" => ""
            ],
            [
                "name" => "Rumah Singgah Anak",
                "latitude" => "-7.759515730843903",
                "longitude" => "110.36909069258823",
                "address" => "Jl. Selokan Mataram No.234, Kutu Dukuh, Sinduadi, Kec. Mlati, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55284",
                "phone" => "6281325561116"
            ],
            [
                "name" => "Yayasan Sayap Ibu",
                "latitude" => "-7.770077362739665",
                "longitude" => "110.44993869550821",
                "address" => "6CHX+VJX, Jl. Ukrim, RT.07/RW.02, Kadirojo II, Purwomartani, Kec. Kalasan, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55571",
                "phone" => "0274497845"
            ],
            [
                "name" => "Yayasan Madania",
                "latitude" => "-7.8006498536121756",
                "longitude" => "110.40382035371555",
                "address" => "Jl. Raya Janti Gg. Gemak No.88, Wonocatur, Banguntapan, Kec. Banguntapan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55198",
                "phone" => "0274412451"
            ],
            [
                "name" => "Panti Sosial Tresna Werdha",
                "latitude" => "-7.84257137751834",
                "longitude" => "110.3385080221421",
                "address" => "Jl. Kasongan No.223, Kajen, Bangunjiwo, Kec. Kasihan, Kabupaten Bantul, Daerah Istimewa Yogyakarta 55184",
                "phone" => ""
            ],
            [
                "name" => "BRSPA",
                "latitude" => "-7.682351181174135",
                "longitude" => "110.45937515273218",
                "address" => "8F85+XGR, JL. Kalasan-Pakem, Sleman Desa Balong, Jaten, Bimomartani, Ngemplak, Sleman Regency, Special Region of Yogyakarta 55584",
                "phone" => ""
            ],
            [
                "name" => "Yayasan Hamba",
                "latitude" => "-7.6675687192481",
                "longitude" => "110.40902826191594",
                "address" => "Jl. Kaliurang, RT.1/RW.11, Dero Wetan, Harjobinangun, Kec. Pakem, Kabupaten Sleman, Daerah Istimewa Yogyakarta",
                "phone" => "0274898013"
            ],
        ];
        foreach ($foundations as $item) {
            Foundation::create($item);
        }
    }
}
