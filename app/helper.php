<?php

use App\Models\School;
use Illuminate\Support\Facades\Auth;

function getSchoolList(){
    $user = Auth::user();
    $userId = $user->id;  
    return School::where("user_id",$userId)->get(); 

}
?>