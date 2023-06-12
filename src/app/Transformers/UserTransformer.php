<?php

namespace App\Transformers;


use App\Models\User;
use App\Utils\Date;
use Carbon\Carbon;
use  \League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(User $user)
    {
       
        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => Date::Valid($user->created_at) ? Carbon::parse($user->created_at)->toDateTimeString() : null,
        ];
    }

    public function collect($collection)
    {
        $transformer = new UserTransformer();
        return collect($collection)->map(function ($model) use ($transformer) {
            return $transformer->transform($model);
        });
    }
}
