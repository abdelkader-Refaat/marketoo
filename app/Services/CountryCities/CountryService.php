<?php

namespace App\Services\CountryCities;

use App\Models\Country;
use App\Models\AllUsers\User;
use App\Services\Core\BaseService;

class CountryService extends BaseService
{
    public function __construct()
    {
        $this->model = Country::class;
    }


    public function getFlags()
    {
        $flags = [];
        foreach (\File::files(public_path('admin/assets/flags/png')) as $path) {
            $file = pathinfo($path);
            $flags[] =  $file['filename'] . '.' . $file['extension'];
        }
        return $flags;
    }

    public function delete(int $id, array $relationsToCheck = [], array $conditions = []): array
    {
        $country = $this->model::find($id);

        $users = User::where('country_code', 'LIKE', '%' . fixPhone($country->key) . '%')->exists();
        if ($users) {
            return ['key' => 'error', 'msg' => __('admin.country_related_with_users')];
        }

        $country->delete();

        return ['key' => 'success', 'msg' => __('admin.deleted_successfully')];
    }

    public function deleteAll($request, array $relationsToCheck = []): array
    {
        $requestIds = json_decode($request['data']);
        $has_users = false;

        foreach (array_column($requestIds, 'id') as $id) {
            $country = $this->model::find($id);
            $users = User::where('country_code', 'LIKE', '%' . fixPhone($country->key) . '%')->exists();
            if ($users) {
                $has_users = true;
                break;
            } else {
                $country->delete();
            }
        }
        return [
            'key' => $has_users ? 'error' : 'success',
            'msg' => !$has_users ?  __('admin.deleted_successfully') : __('admin.country_related_with_users_or_cities')
        ];
    }
}
