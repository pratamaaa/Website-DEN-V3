<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewKuesionerLayanan extends Model
{
    protected $table = 'view_kuesioner_layanan';

    // View biasanya tidak punya primary key → biarkan null
    protected $primaryKey = null;
    public $incrementing = false;

    // View tidak punya timestamps
    public $timestamps = false;

    // Lock : model ini read-only
    public function save(array $options = [])
    {
        throw new \Exception("Cannot save to a database view.");
    }

    public function delete()
    {
        throw new \Exception("Cannot delete from a database view.");
    }
}
