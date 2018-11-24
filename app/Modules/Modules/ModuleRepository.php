<?php
namespace App\Modules\Modules;

use App\Core\EloquentRepository;
use Illuminate\Support\Str;

class ModuleRepository extends EloquentRepository
{


    public $validator;

    protected $insertedId;
    protected $optionsView;


    public function __construct(Module $module, ModuleValidator $moduleValidator)
    {
        $this->model = $module;
        $this->validator = $moduleValidator;
    }


    /**
     * get the modules for the provided ids
     *
     * @param array $ids
     * @return array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getIn(array $ids)
    {
        return $this->model->whereIn('id', $ids)->get();
    }

    /**
     * @param $moduleData
     * @return bool
     */
    public function createModule($moduleData)
    {


        /*$finalData = $moduleData-except(array('password'));
        print_r($finalData);*/

        $moduleModel = parent::getNew($moduleData);
        $moduleModel->slug = Str::slug($moduleData['name'], '-');
        if ($moduleModel->save()) {
            $this->insertedId = $moduleModel->id;
            return true;
        }
        return false;


    }


    /**
     * @param $moduleData
     * @return bool
     */
    public function updateModule($id, $moduleData)
    {

        $moduleModel = $this->getById($id);


        $moduleModel->fill($moduleData);
        $moduleModel->slug = \Str::slug($moduleData['name'], '-');
        if ($moduleModel->save()) {
            return true;
        }

        /*$moduleModel->update($moduleData);*/

        return false;

    }


    public function getInsertedId()
    {
        return $this->insertedId;
    }

    /**
     * @param $email the mail used to check the duplicate record in the db
     * @return int
     */
    public function checkDuplicateModules($email)
    {
        //echo $email;
        return $this->model->where('email', $email)->count();
        //print_r(\DB::getQueryLog());
    }

    public function deleteModule($id)
    {
        $module = $this->getById($id);
        if(is_null($module)){
            throw new ResourceNotFoundException('Module Not Found');
        }
        $moduleSlug = $module->slug;
        if ($this->model->destroy($id)) {
            //lets delete all the permissions from the users for this module
            //get the permisison object
            $permission = new \Permission();
            $permission->where('resource', 'LIKE',$moduleSlug)->delete();

           // print_r(DB::getQueryLog());
            return true;
        }

        return false;
    }
}