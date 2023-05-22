<?php

namespace App\Http\Controllers;

use App\Models\Variant;

class VariantController extends Controller
{
    public function getUserId()
    {
        $user = UserController::show();
        return $user->id;
    }

    // get all variants
    public function index() {
        return Variant::getVariants($this->getUserId());
    }
}
