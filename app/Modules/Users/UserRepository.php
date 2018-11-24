<?php

namespace App\Modules\Users;

use App\Modules\Users\Types\UserType;
use App\User;
use Auth;
use Hash;
use Optimait\Laravel\Exceptions\ApplicationException;
use Optimait\Laravel\Repos\EloquentRepository;
use Optimait\Laravel\Traits\UploaderTrait;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/*
 * Class UserRepository
 * @package App\Admin\User
 */
class UserRepository extends EloquentRepository
{
    use UploaderTrait;
    /*
     * @var UserValidator
     */
    public $validator;

    /*
     * @var
     */
    protected $insertedId;

    protected $password;


    /*
     * @param User $user
     * @param UserValidator $userValidator
     */
    public function __construct(User $user, UserValidator $userValidator)
    {
        $this->model = $user;
        $this->validator = $userValidator;
    }

    /*
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getNumberOfUser()
    {
        return $this->model
            ->exceptMe()
            ->forMe()
            ->count();
    }

    public function getPaginated($items = 10, $orderBy = 'name', $orderType = 'DESC')
    {
        return $this->model
            ->exceptMe()
            ->forMe()
            ->with('userType')
            ->orderBy($orderBy, $orderType)
            ->paginate($items);
    }
    public function getClientsPaginated($items = 10, $orderBy = 'name', $orderType = 'DESC')
    {
        return $this->model
            ->exceptMe()
            ->forMe()
            ->clients()
            ->with('userType')
            ->orderBy($orderBy, $orderType)
            ->paginate($items);
    }



    public function createClient($userData)
    {
        $userModel = parent::getNew($userData);
        $userModel->setUserType(UserType::CLIENT);

        if ($userModel->save()) {
            $this->insertedId = $userModel->id;

            event('client.saved', array($userModel, $userData, false));

            return $userModel;
        }
        throw new ApplicationException('Client cannot be added at this moment. Please try again later.');
    }

    public function updateClient($id, $userData, $returnModel = false)
    {
        $userModel = $this->getById($id);

        $userModel->fill($userData);
        if ($userModel->save()) {

            if(count($userData['primary_contacts']['first_name']) > 0){
                $userModel->primaryContacts()->delete();
                foreach($userData['primary_contacts']['first_name'] as $k => $firstName){
                    $userModel->primaryContacts()->save(new PrimaryContact([
                        'first_name' => $firstName,
                        'last_name' => $userData['primary_contacts']['last_name'][$k],
                        'phone' => $userData['primary_contacts']['phone'][$k],
                        'email' => $userData['primary_contacts']['email'][$k],
                    ]));
                }
            }

            event('client.saved', array($userModel, $userData));
            return $userModel;
        }

        throw new ApplicationException('User cannot be saved at this moment. Please try again later.');
    }

    /*
     * @param $userData
     * @return bool
     */

    public function createUser($userData)
    {
        $userModel = parent::getNew($userData);
        $this->setPassword($userData['password']);
        $userModel->password = Hash::make($this->getPassword());
        $userModel->setUserType($userData['user_type_id']);

        if ($userModel->save()) {
            $this->insertedId = $userModel->id;
            event('user.saved', array($userModel, $userData, false, $this->getPassword()));
            return $userModel;
        }
        throw new ApplicationException('User cannot be added at this moment. Please try again later.');
    }

    /*
     * @param $userData
     * @return bool
     */

    public function updateUser($id, $userData, $returnModel = false)
    {
        $userModel = $this->getById($id);

        if (@$userData['password'] == '') {
            unset($userData['password']);
        } else {
            $userData['password'] = Hash::make($userData['password']);
        }
        $userModel->fill($userData);
        if ($userModel->save()) {
            event('user.saved', array($userModel, $userData));
            return $userModel;
        }
        throw new ApplicationException('User cannot be saved at this moment. Please try again later.');
    }


    /*
     * @param $password
     * @return mixed
     */
    public function changePassword($password, $userModel = null)
    {
        if (is_null($userModel)) {
            $userModel = Auth::getUser();
        }

        $userModel->password = Hash::make($password);
        //$userModel->password = 'Try';
        return $userModel->save();
    }

    /*
     * @return mixed
     */
    public function getInsertedId()
    {
        return $this->insertedId;
    }

    /*
     * @param $email the mail used to check the duplicate record in the db
     * @return void
     */

    public function checkDuplicateUsers($email, $exclude=[0])
    {
        //echo $email;
        if ($this->model->where('email', $email)->whereNotIn('id', $exclude)->count() > 0) {
            throw new ApplicationException('The user is already registered.');
        }
    }


    public function deleteUser($id)
    {
        $user = $this->getById($id);
        if (is_null($user)) {
            throw new ResourceNotFoundException('User not found.');
        }
        return $user->selfDestruct();
    }



}