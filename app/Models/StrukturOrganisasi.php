<?php

// app/Models/StrukturOrganisasi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrukturOrganisasi extends Model
{
    protected $table = 'struktur_organisasi';

    protected $primaryKey = 'id_so';

    protected $fillable = [
        'id_parent',
        'nama_lengkap',
        'jabatan',
        'foto',
        'urutan',
        'is_active',
    ];

    // Relasi ke parent
    public function parent()
    {
        return $this->belongsTo(StrukturOrganisasi::class, 'id_parent', 'id_so');
    }

    // Relasi ke children
    public function children()
    {
        return $this->hasMany(StrukturOrganisasi::class, 'id_parent', 'id_so')
            ->where('is_active', 'yes')
            ->orderBy('urutan');
    }

    // Rekursif untuk semua keturunan
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }
}
