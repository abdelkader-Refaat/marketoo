<?php

namespace App\Http\Controllers\Admin\PublicSettings;

use App\Http\Controllers\Admin\Core\AdminBasicController;
use App\Traits\ReportTrait;
use App\Models\PublicSettings\Role;
use App\Http\Requests\Admin\PublicSettings\Role\StoreRequest;
use App\Services\PublicSettings\RoleService;
use App\Traits\Admin\RoleTrait;

class RoleController extends AdminBasicController
{
    use RoleTrait;
    /**
     * Constructor for the RoleController.
     * Initializes model, request classes, service, and variables for views.
     */
    public function __construct()
    {
        // Set the model class for roles
        $this->model = Role::class;

        // Set the store request class
        $this->storeRequest = StoreRequest::class;

        // Set the directory name for views
        $this->directoryName = 'public-settings.roles';

        // Initialize the service for role operations
        $this->serviceName = new RoleService();

        // Define index scopes for search functionality
        $this->indexScopes = 'search';

        // Prepare data for create view
        $this->createCompactVariables = $this->prepareDataForCreateAction();

        // Prepare data for edit view
        $this->editCompactVariables = $this->prepareDataForEditAction();

        // Conditions for destroying a role
        $this->destroyRelationsToCheck = ['admins'];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store()
    {
        // Initialize the store request
        $this->storeRequest = app($this->storeRequest);

        // Create a new role with validated data
        $role = Role::create($this->storeRequest->validated());

        // Create permission records for each permission selected
        $permissions = [];
        foreach ($this->storeRequest->permissions ?? [] as $permission) {
            $permissions[]['permission'] = $permission;
        }

        $role->permissions()->createMany($permissions);

        // Add to log
        ReportTrait::addToLog('  اضافه صلاحية');

        // Return with success message
        return redirect(route('admin.roles.index'))->with('success', __('admin.added_successfully'));
    }

    /**
     * Update the specified role in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        // Initialize the store request
        $this->storeRequest = app($this->storeRequest);

        // Find the role by ID or fail
        $role = Role::findOrFail($id);

        // Update the role with validated data
        $role->update($this->storeRequest->validated());

        // Delete existing permissions
        $role->permissions()->delete();

        // Prepare new permissions
        $permissions = [];
        foreach ($this->storeRequest->permissions ?? [] as $permission) {
            $permissions[]['permission'] = $permission;
        }

        // Create new permissions
        $role->permissions()->createMany($permissions);

        // Log the update action
        ReportTrait::addToLog('  تعديل صلاحية');

        // Redirect with success message
        return redirect(route('admin.roles.index'))->with('success', __('admin.update_successfully'));
    }
}
