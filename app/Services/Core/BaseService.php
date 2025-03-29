<?php

namespace App\Services\Core;

use App\Traits\UploadTrait;
use App\Notifications\BlockUser;
use Illuminate\Support\Collection;
use App\Services\Core\WalletService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;


class BaseService
{

    use UploadTrait;
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function limit($paginateNum = 10, $conditions = [], $with = [], $withCount = [], $scopes = 'search')
    {
        $query = $this->model::query()
            ->latest()
            ->with($with)
            ->withCount($withCount)
            ->where($conditions);

        $query = $this->applyScopes($query, $scopes);

        return $query->paginate($paginateNum);
    }


    /**
     * Get all records with optional conditions and relationships.
     */
    public function all(array $conditions = [], array $with = [], $withCount = [], string $scopes = 'search'): Collection
    {
        $query = $this->model::query()
            ->with($with)
            ->withCount($withCount)
            ->where($conditions);

        $query = $this->applyScopes($query, $scopes);
        return $query->get();
    }

    /**
     * Apply scopes to the query.
     */
    protected function applyScopes($query, string $scopes): object
    {
        if (empty($scopes)) {
            return $query;
        }

        $scopeMethods = explode('->', $scopes);
        foreach ($scopeMethods as $scopeMethod) {
            if (method_exists($this->model, 'scope' . ucfirst($scopeMethod))) {
                if ($scopeMethod == 'search') {
                    $query = $query->search(request()->searchArray);
                }
                $query->$scopeMethod();
            }
        }

        return $query;
    }


    /**
     * Create a new record.
     */
    public function create(array $data): Model
    {
        return $this->model::create($data);
    }


    public function find($id, array $with = [], array $conditions = []): ?object
    {
        return $this->model::query()
            ->with($with)
            ->where($conditions)
            ->findOrFail($id);
    }


    public function first(array $with = [], array $conditions = []): ?object
    {
        return $this->model::query()
            ->with($with)
            ->where($conditions)
            ->first();
    }

    /**
     * Update a record by ID.
     */
    public function update(int $id, array $data): object
    {
        $row = $this->find($id);
        $row->update($data);
        return $row;
    }


    /**
     * Delete a record by ID after checking for related data.
     *
     * @param int $id The ID of the record to delete.
     * @param array $relationsToCheck An array of relations to check for existence.
     * @return array Returns an array with a 'key' and 'msg' indicating the result.
     */
    public function delete(int $id, array $relationsToCheck = [], array $conditions = []): array
    {
        // Find the record or fail
        $record = $this->find(id: $id, conditions: $conditions);

        // Check if any of the specified relations exist
        foreach ($relationsToCheck as $relation) {
            if ($record->$relation()->exists()) {
                return ['key' => 'error', 'msg' => __('admin.record_has_related_data_and_cannot_be_deleted')];
            }
        }

        // If no related data exists, delete the record
        $record->delete();

        return ['key' => 'success', 'msg' => __('admin.deleted_successfully')];
    }


    public function deleteMultiple($request, array $relationsToCheck = [], array $conditions = []): array
    {
        $requestIds = json_decode($request['data'], true);

        // Initialize a flag to track if any record has related data
        $hasRelatedData = false;

        // Loop through each ID
        foreach (array_column($requestIds, 'id') as $id) {
            // Find the record or fail
            $record = $this->model::where($conditions)->findOrFail($id);

            // Check if any of the specified relations exist
            foreach ($relationsToCheck as $relation) {
                if ($record->$relation()->exists()) {
                    $hasRelatedData = true;
                    break 2; // Exit both loops if related data is found
                }
            }

            // If no related data exists, delete the record
            $record->delete();
        }

        return [
            'key' => 'success',
            'msg' => $hasRelatedData ? __('admin.Some_records_have_related_data_and_cannot_be_deleted') :
                __('admin.All_selected_records_have_been_deleted')
        ];
    }

    /**
     * Toggle the status of a record.
     */
    public function toggleStatus(int $id): array
    {
        $model = $this->find($id);
        $model->update(['status' => !$model->status]);
        $message = $model->status ? __('admin.active') : __('admin.dis_activate');
        return ['key' => 'success', 'msg' => $message, 'data' => $model];
    }

    /**
     * Toggle the blocked status of a user and notify them.
     */
    public function toggleBlock(int $id): array
    {
        $user = $this->find($id);
        $user->update(['is_blocked' => !$user->is_blocked]);

        if ($user->is_blocked) {
            Notification::send($user, new BlockUser());
            return ['msg' => __('admin.blocked')];
        }

        return ['msg' => __('admin.unblocked')];
    }

    /**
     * Update the balance of a user's wallet.
     */
    public function updateBalance(int $id, float $balance, int $type): array
    {
        $walletService = new WalletService();
        $user = $this->find($id);

        if ($balance <= 0) {
            return ['key' => 'fail', 'msg' => __('admin.invalid_balance'), 'balance' => $user->balance];
        }

        if ($type === 0) {
            $walletService->charge($user, $balance);
        } else {
            if ($user->wallet?->balance < $balance) {
                return ['key' => 'fail', 'msg' => __('admin.balance_not_enough'), 'balance' => $user->wallet?->balance];
            }
            $walletService->debt($user->wallet, $balance);
        }

        return ['key' => 'success', 'msg' => __('admin.balance_updated'), 'balance' => $user->wallet?->balance];
    }

    /**
     * Attach a many-to-many relationship.
     */
    public function attachRelation(string $relation, Model $model, array $data): array
    {
        $model->$relation()->attach($data);
        return ['key' => 'success', 'msg' => __('apis.success')];
    }

    /**
     * Sync a many-to-many relationship.
     */
    public function syncRelation(string $relation, Model $model, array $data): array
    {
        $model->$relation()->sync($data);
        return ['key' => 'success', 'msg' => __('apis.success')];
    }

    /**
     * Update or create a has-one relationship.
     */
    public function updateOrCreateRelation(string $relation, Model $model, array $data, array $conditions = []): array
    {
        $relationModel = $model->$relation()->updateOrCreate($conditions, $data);
        return ['key' => 'success', 'msg' => __('apis.success'), 'data' => $relationModel];
    }

    /**
     * Create a record for a has-one or has-many relationship.
     *
     * @param string $relation The name of the relationship.
     * @param Model $model The parent model instance.
     * @param array $data The data to create the related record.
     * @return array Returns an array with a 'key' and 'msg' indicating the result.
     */
    public function createRelation(string $relation, Model $model, array $data): array
    {
        try {
            // Create the related record
            $relationModel = $model->$relation()->create($data);
            return ['key' => 'success', 'msg' => __('apis.success'), 'data' => $relationModel];
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., database errors)
            return ['key' => 'error', 'msg' => __('apis.error_creating_relation'), 'error' => $e->getMessage()];
        }
    }

    /**
     * Create multiple records for a has-many relationship.
     *
     * @param string $relation The name of the relationship.
     * @param Model $model The parent model instance.
     * @param array $data An array of data arrays to create the related records.
     * @return array Returns an array with a 'key' and 'msg' indicating the result.
     */
    public function createManyRelation(string $relation, Model $model, array $data): array
    {
        try {
            // Create multiple related records
            $relationModels = $model->$relation()->createMany($data);
            return ['key' => 'success', 'msg' => __('apis.success'), 'data' => $relationModels];
        } catch (\Exception $e) {
            // Handle any exceptions (e.g., database errors)
            return ['key' => 'error', 'msg' => __('apis.error_creating_relation'), 'error' => $e->getMessage()];
        }
    }

    public function uploadMultiImages($requestedImageToUpload, $modelImagePath): array
    {
        $multiImages = [];

        if (count($requestedImageToUpload) > 0) {
            foreach ($requestedImageToUpload as $singleImage) {
                $multiImages[] = $this->uploadImage($singleImage, $modelImagePath);
            }
        }

        return $multiImages;
    }
}
