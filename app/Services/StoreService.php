<?php

namespace App\Services;

use App\Http\Resources\StoreResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


use App\Models\Store;
use Illuminate\Support\Facades\Validator;


class StoreService
{


    /**
     * @throws Exception
     */
    public function updateStore($id, $data, $image = null)
    {
        $user = Auth::user();
        $store = Store::findOrFail($id);

        if ($user->role === 'vendor' && $store->user_id !== $user->id) {
            throw new Exception('Unauthorized access to store');
        }

        if ($image) {
            $path = $image->store('images/stores', 'public');
            $data['image'] = $path;
        }

        $store->update($data);

        return $store;
    }

}
