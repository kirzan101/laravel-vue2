<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Resources\UserGroupPermissionResource;
use App\Interfaces\UserGroupPermissionInterface;
use App\Models\UserGroupPermission;
use App\Traits\ReturnCollectionTrait;
use App\Traits\ReturnResultTrait;
use Exception;
use Illuminate\Support\Facades\DB;

class UserGroupPermissionService implements UserGroupPermissionInterface
{
    use ReturnResultTrait, ReturnCollectionTrait;

    public ?int $last_id = null;
    public array $return_result = [];

    /**
     * index of user group permissions
     *
     * @return array
     */
    public function indexUserGroupPermission(): array
    {
        try {
            $user_group_permissions = UserGroupPermission::all();

            $this->return_result = $this->returnCollection(200, 'success', results: UserGroupPermissionResource::collection($user_group_permissions));
        } catch (Exception $e) {
            $this->return_result = $this->returnCollection($e->getCode(), 'error', $e->getMessage());
        }

        return $this->return_result;
    }

    /**
     * index of user group permissions by user group ID
     *
     * @param integer $user_group_id
     * @return array
     */
    public function indexUserGroupPermissionByUserGroupId(int $user_group_id): array
    {
        try {
            $user_group_permissions = UserGroupPermission::where('user_group_id', $user_group_id)->get();

            $this->return_result = $this->returnCollection(200, 'success', results: UserGroupPermissionResource::collection($user_group_permissions));
        } catch (Exception $e) {
            $this->return_result = $this->returnCollection($e->getCode(), 'error', $e->getMessage());
        }

        return $this->return_result;
    }

    /**
     * create user group permission service
     *
     * @param array $request
     * @return array
     */
    public function createUserGroupPermission(array $request): array
    {
        try {
            DB::beginTransaction();

            $user_group_permission = UserGroupPermission::create($request);

            $this->return_result = $this->returnResult(201, 'success', Helper::message(201), $user_group_permission->id, new UserGroupPermissionResource($user_group_permission));
        } catch (Exception $e) {
            DB::rollBack();

            $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }

    /**
     * Update user group permission service
     *
     * @param array $request
     * @param UserGroupPermission $userGroupPermission
     * @return array
     */
    public function updateUserGroupPermission(array $request, $user_group_permission_id): array
    {
        try {
            DB::beginTransaction();

            $userGroupPermission = UserGroupPermission::findOrFail($user_group_permission_id);

            $user_group_permission = tap($userGroupPermission)->update($request);

            $this->return_result = $this->returnResult(200, 'success', Helper::message(200), $user_group_permission->id, new UserGroupPermissionResource($user_group_permission));
        } catch (Exception $e) {
            DB::rollBack();

            $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }

    /**
     * show the user group permission by id
     *
     * @param integer $user_group_permission_id
     * @return array
     */
    public function showUserGroupPermission(int $user_group_permission_id): array
    {
        try {
            $this->last_id = $user_group_permission_id;

            $user_group_permission = UserGroupPermission::findOrFail($user_group_permission_id);

            return $this->return_result = $this->returnResult(200, 'success', Helper::message(), $user_group_permission->id, new UserGroupPermissionResource($user_group_permission));
        } catch (Exception $e) {
            return $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage(), $this->last_id);
        }

        return $this->return_result;
    }

    /**
     * delete user group permission service
     *
     * @param UserGroupPermission $userGroupPermission
     * @return array
     */
    public function deleteUserGroupPermission(int $user_group_permission_id): array
    {
        try {
            DB::beginTransaction();

            $userGroupPermission = UserGroupPermission::findOrfail($user_group_permission_id);

            $this->last_id = $userGroupPermission->id;

            $userGroupPermission->delete();

            $this->return_result = $this->returnResult(204, 'success', Helper::message(204), $this->last_id);
        } catch (Exception $e) {
            DB::rollBack();

            $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }

    /**
     * Create multiple user group permissions
     *
     * @param array $permissions
     * @param integer $user_group_id
     * @return array
     */
    public function createMultipleUserGroupPermission(array $permissions, int $user_group_id): array
    {
        try {
            DB::beginTransaction();

            // create user group permission for permissions
            foreach ($permissions as $permission_id) {
                ['result' => $result] = $this->createUserGroupPermission([
                    'user_group_id' => $user_group_id,
                    'permission_id' => $permission_id
                ]);

                $this->last_id = $result->id;
            }

            $this->return_result = $this->returnResult(201, 'success', Helper::message(201), $this->last_id);
        } catch (Exception $e) {
            DB::rollBack();

            $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }

    /**
     * Update user group permissions by user group ID
     *
     * @param array $permissions
     * @param integer $user_group_id
     * @return array
     */
    public function updateUserGroupPermissionByUserGroupId(array $permissions, int $user_group_id): array
    {
        try {
            DB::beginTransaction();

            //delete previous permissions
            UserGroupPermission::where('user_group_id', $user_group_id)->delete();

            //re-create the permissions
            foreach ($permissions as $permission_id) {
                ['result' => $result] = $this->createUserGroupPermission([
                    'user_group_id' => $user_group_id,
                    'permission_id' => $permission_id
                ]);

                $this->last_id = $result->id;
            }

            $this->return_result = $this->returnResult(200, 'success', Helper::message(200), $this->last_id);
        } catch (Exception $e) {
            DB::rollBack();

            $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }

    /**
     * delete user group permission by user group ID
     *
     * @param integer $user_group_id
     * @return array
     */
    public function deleteUserGroupPermissionByUserGroupId(int $user_group_id): array
    {
        try {
            DB::beginTransaction();

            // delete user group permission by user group Id
            UserGroupPermission::where('user_group_id', $user_group_id)->delete();

            $this->return_result = $this->returnResult(204, 'success', Helper::message(204));
        } catch (Exception $e) {
            DB::rollBack();
            $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }
}
