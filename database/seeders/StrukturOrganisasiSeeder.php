<?php

namespace Database\Seeders;

use App\Models\StrukturOrganisasi;
use Illuminate\Database\Seeder;

class StrukturOrganisasiSeeder extends Seeder
{
    public function run(): void
    {
        // Level 1 - Top
        $sekjen = StrukturOrganisasi::create([
            'id_parent' => 0,
            'nama_lengkap' => 'Dr. Ir. Dadan Kusdiana, M.Sc.',
            'jabatan' => 'Sekretaris Jenderal',
            'urutan' => 1,
            'is_active' => 'yes',
        ]);

        // Level 2
        $biro1 = StrukturOrganisasi::create([
            'id_parent' => $sekjen->id_so,
            'nama_lengkap' => 'Bambang Sujito, S.H., M.H.',
            'jabatan' => 'Kepala Biro Umum',
            'urutan' => 1,
            'is_active' => 'yes',
        ]);

        $biro2 = StrukturOrganisasi::create([
            'id_parent' => $sekjen->id_so,
            'nama_lengkap' => 'Ir. Yunus Saefulhak, M.M., M.T.',
            'jabatan' => 'Kepala Biro Fasilitasi Kebijakan Energi dan Persidangan',
            'urutan' => 2,
            'is_active' => 'yes',
        ]);

        $biro3 = StrukturOrganisasi::create([
            'id_parent' => $sekjen->id_so,
            'nama_lengkap' => 'Luh Nyoman Puspa Dewi, S.E., M.M.',
            'jabatan' => 'Kepala Biro Fasilitasi Penanggulangan Krisis dan Pengawasan Energi',
            'urutan' => 3,
            'is_active' => 'yes',
        ]);

        // Level 3 - Bawah Biro 1
        StrukturOrganisasi::create([
            'id_parent' => $biro1->id_so,
            'nama_lengkap' => 'Supriyadi, S.E., M.A.P.',
            'jabatan' => 'Kepala Bagian Rumah Tangga dan Keprotokolan',
            'urutan' => 1,
            'is_active' => 'yes',
        ]);
        StrukturOrganisasi::create([
            'id_parent' => $biro1->id_so,
            'nama_lengkap' => 'Supriadi, S.H., M.H.',
            'jabatan' => 'Koordinator Hukum dan Kepegawaian',
            'urutan' => 2,
            'is_active' => 'yes',
        ]);
        StrukturOrganisasi::create([
            'id_parent' => $biro1->id_so,
            'nama_lengkap' => 'Nanang Kristanto, S.T., M.A.B.',
            'jabatan' => 'Koordinator Perencanaan dan Keuangan',
            'urutan' => 3,
            'is_active' => 'yes',
        ]);

        // Level 3 - Bawah Biro 2
        StrukturOrganisasi::create([
            'id_parent' => $biro2->id_so,
            'nama_lengkap' => 'Lisa Ambarsari, S.T., M.S.E.',
            'jabatan' => 'Koordinator Fasilitasi Kebijakan Energi',
            'urutan' => 1,
            'is_active' => 'yes',
        ]);
        StrukturOrganisasi::create([
            'id_parent' => $biro2->id_so,
            'nama_lengkap' => 'Dra. Suharyati',
            'jabatan' => 'Koordinator Rencana Umum Energi',
            'urutan' => 2,
            'is_active' => 'yes',
        ]);
        StrukturOrganisasi::create([
            'id_parent' => $biro2->id_so,
            'nama_lengkap' => 'Khoiria Oktaviani, S.I.P., M.Eng.',
            'jabatan' => 'Koordinator Humas dan Persidangan',
            'urutan' => 3,
            'is_active' => 'yes',
        ]);

        // Level 3 - Bawah Biro 3
        StrukturOrganisasi::create([
            'id_parent' => $biro3->id_so,
            'nama_lengkap' => 'Budi Cahyono, S.T., M.T.',
            'jabatan' => 'Koordinator Fasilitasi Penanggulangan Krisis Energi',
            'urutan' => 1,
            'is_active' => 'yes',
        ]);
        StrukturOrganisasi::create([
            'id_parent' => $biro3->id_so,
            'nama_lengkap' => 'Prima Agung Prasetyawan Suharko, S.T., M.S.E.',
            'jabatan' => 'Koordinator Fasilitasi Pengawasan Pelaksanaan Kebijakan Energi',
            'urutan' => 2,
            'is_active' => 'yes',
        ]);
    }
}
