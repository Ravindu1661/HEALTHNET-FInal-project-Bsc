<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Announcement extends Model
{
    use HasFactory;

    protected $table = 'announcements';

    protected $fillable = [
        'publisher_type',
        'publisher_id',
        'title',
        'content',
        'announcement_type',
        'image_path',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];


public static function types(): array
{
    $row = DB::selectOne("SHOW COLUMNS FROM announcements WHERE Field = 'announcement_type'");
    if (!$row || empty($row->Type)) return [];

    preg_match("/^enum\\((.*)\\)$/", $row->Type, $m);
    if (empty($m[1])) return [];

    return array_map(fn($v) => trim($v, "'"), explode(',', $m[1]));
}

}
