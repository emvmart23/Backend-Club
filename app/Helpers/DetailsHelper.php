<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

function getDataByIdOfAnyTable($id, $row) {
    $d = DB::table('attendances')->where('user_id', $id)->first();
    return $d;
}