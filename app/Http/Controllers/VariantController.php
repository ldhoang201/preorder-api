<?php

namespace App\Http\Controllers;

use App\Models\Variant;
use Illuminate\Http\Request;

class VariantController extends Controller
{
    public function getUserId()
    {
        $user = UserController::show();
        return $user->id;
    }

    public function index() {
        return Variant::getVariants($this->getUserId());
    }
}
