<?php

namespace App\Services;

use App\Helpers\Helper;
use App\Http\Resources\UserGroupResource;
use App\Interfaces\UserGroupInterface;
use App\Interfaces\UserGroupPermissionInterface;
use App\Models\UserGroup;
use App\Traits\ReturnCollectionTrait;
use App\Traits\ReturnResultTrait;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserGroupService implements UserGroupInterface
{
    use ReturnResultTrait, ReturnCollectionTrait;

    public ?int $last_id = null;
    public array $return_result = [];

    public function __construct(private UserGroupPermissionInterface $userGroupPermission)
    {
    }

    /**
     * get the list of user groups
     *
     * @return array
     */
    public function indexUserGroup(): array
    {
        try {
            $user_groups = UserGroup::all();

            $this->return_result = $this->returnCollection(200, 'success', results: UserGroupResource::collection($user_groups));
        } catch (Exception $e) {
            $this->return_result = $this->returnCollection($e->getCode(), 'error', $e->getMessage());
        }

        return $this->return_result;
    }

    /**
     * create user group service
     *
     * @param array $request
     * @return array
     */
    public function createUserGroup(array $request): array
    {
        try {
            DB::beginTransaction();

            $user_group = UserGroup::create($request);

            $this->return_result = $this->returnResult(201, Helper::SUCCESS, Helper::message(201), $user_group->id, new UserGroupResource($user_group));

            //create permissions
            $result = $this->userGroupPermission->createMultipleUserGroupPermission($request['permissions'], $user_group->id);

            // if creation of permissions has an error
            if ($result['status'] == 'error') {
                $this->return_result = $this->returnResult($result['code'], $result['status'], $result['message']);
            }
        } catch (Exception $e) {
            DB::rollBack();

            $this->return_result = $this->returnResult($e->getCode(), Helper::ERROR, $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }

    /**
     * update user group service
     *
     * @param array $request
     * @param integer $user_group_id
     * @return array
     */
    public function updateUserGroup(array $request, int $user_group_id): array
    {
        try {
            DB::beginTransaction();

            $userGroup = UserGroup::find($user_group_id);

            $user_group = tap($userGroup)->update($request);

            $this->return_result = $this->returnResult(200, 'success', Helper::message(200), $user_group->id, new UserGroupResource($user_group));

            // update user group permissions
            $result = $this->userGroupPermission->updateUserGroupPermissionByUserGroupId($request['permissions'], $userGroup->id);

            // if update of permissions has an error
            if ($result['status'] == 'error') {
                $this->return_result = $this->returnResult($result['code'], $result['status'], $result['message']);
            }
        } catch (Exception $e) {
            DB::rollBack();

            $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }

    /**
     * show the user group by id
     *
     * @param integer $user_group_id
     * @return array
     */
    public function showUserGroup(int $user_group_id): array
    {
        try {
            $this->last_id = $user_group_id;

            $user_group = UserGroup::findOrFail($user_group_id);

            return $this->return_result = $this->returnResult(200, 'success', Helper::message(), $user_group->id, new UserGroupResource($user_group));
        } catch (Exception $e) {
            return $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage(), $this->last_id);
        }

        return $this->return_result;
    }

    /**
     * delete user group service
     *
     * @param UserGroup $userGroup
     * @return array
     */
    public function deleteUserGroup(int $user_group_id): array
    {
        try {
            DB::beginTransaction();

            $userGroup = UserGroup::findOrFail($user_group_id);

            // store the current id
            $this->last_id = $userGroup->id;

            //delete user group permisions
            $this->userGroupPermission->deleteUserGroupPermissionByUserGroupId($userGroup->id);

            $userGroup->delete();

            $this->return_result = $this->returnResult(204, 'success', Helper::message(204), $this->last_id);
        } catch (Exception $e) {
            DB::rollBack();

            $this->return_result = $this->returnResult($e->getCode(), 'error', $e->getMessage());
        }

        DB::commit();

        return $this->return_result;
    }
}
