<?php

namespace App\Modules\UserImport;

use App\UserImport;
use Optimait\Laravel\Repos\EloquentRepository;
use Optimait\Laravel\Traits\UploaderTrait;
use DB;
/*
 * Class UserRepository
 * @package App\Admin\User
 */

class UserImportRepository extends EloquentRepository
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

    /*
     * @param User $user
     * @param UserValidator $userValidator
     */
    public function __construct(UserImport $userImport, UserImportValidator $userImportValidator)
    {
        $this->model = $userImport;
        $this->validator = $userImportValidator;
    }

    /**
     * @param array $searchData
     * @param int $items
     * @param string $orderBy
     * @param string $orderType
     * @return Vendor[]|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection
     */
    public function getPaginated($searchData = [], $items = 10, $orderBy = 'id', $orderType = 'DESC')
    {
        $model = $this->model->where(function ($q) use ($searchData) {

        })->orderBy($orderBy, $orderType);
        if (!is_null($items)) {
            return $model->paginate($items);
        }
        return $model->get();
    }

    public function insertUserImport($data){
        return $this->model->create($data)->id;
        //$data['created_at'] = \Carbon\Carbon::now();
        //$data['updated_at'] = \Carbon\Carbon::now();
        //return $this->model->insertGetId($data);
    }
    public function updateJobId($id,$job_id){
        $userImport = $this->model->where('id', $id)->first();
        if ($userImport)
        {
            $userImport->job_id = $job_id;
            $userImport->save();
        }
    }

    public function deleteAll()
    {
        return $this->model->where('id', '>', 0)->delete();
    }

}
