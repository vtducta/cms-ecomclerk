<?php

namespace App\Modules\Users\Types;

use App\Exceptions\ApplicationException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class UserTypeRepository
 * @package App\Admin\UserType
 */
class UserTypeRepository extends \App\Core\EloquentRepository
{
    /**
     * @var UserTypeValidator
     */
    public $validator;

    /**
     * @var
     */
    protected $insertedId;

    /**
     * @param UserType $userType
     * @param UserTypeValidator $userTypeValidator
     */
    public function __construct(UserType $userType, UserTypeValidator $userTypeValidator)
    {
        $this->model = $userType;
        $this->validator = $userTypeValidator;
    }

    public function getPaginated($items = 10, $orderBy = 'title', $orderType = 'DESC')
    {
        return $this->model->orderBy($orderBy, $orderType)->paginate($items);
    }

    /**
     * @param $userTypeData
     * @return bool
     */
    public function createUserType($userTypeData, $returnModel = false)
    {
        $userTypeModel = parent::getNew($userTypeData);
        if ($userTypeModel->save()) {
            $this->insertedId = $userTypeModel->id;
            if($returnModel){
                return $userTypeModel;
            }
            return true;
        }
        throw new ApplicationException('UserType cannot be added at this moment. Please try again later.');
    }

    /*public function getByIdWithRole($id){
        return $this->model->find($id);
    }*/

    /**
     * @param $userTypeData
     * @return bool
     */
    public function updateUserType($id, $userTypeData, $returnModel = false)
    {
        $userTypeModel = $this->getById($id);

        $userTypeModel->fill($userTypeData);
        if ($userTypeModel->save()) {
            if($returnModel){
                return $userTypeModel;
            }
            return true;
        }
        throw new ApplicationException('UserType cannot be saved at this moment. Please try again later.');
    }

    /**
     * @return mixed
     */
    public function getInsertedId()
    {
        return $this->insertedId;
    }



    /**
     * @param $id
     * @return int
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     */
    public function deleteUserType($id)
    {
        //cet the userType first
        $userType = $this->getById($id);
        /*print_r($userType);
        die();*/
        if (is_null($userType)) {
            throw new ResourceNotFoundException('UserType not found.');
        }
        return $userType->selfDestruct();
    }



}