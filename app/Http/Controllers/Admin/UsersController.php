<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Users\UserRepository;
use Exception;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Input;
use Optimait\Laravel\Exceptions\ApplicationException;
use Optimait\Laravel\Services\Email\EmailService;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use View;

class UsersController extends Controller
{
    use ResetsPasswords;
    private $users;


    public function __construct(UserRepository $userRepository)
    {
        $this->users = $userRepository;

    }

    /**
     * Display a listing of the resource.
     * GET /userusers
     *
     * @return Response
     */
    public function index()
    {
        if (\Request::ajax()) {
            return $this->getList();
        }
        return view('webpanel.users.index');
    }

    public function getList($id = 0)
    {
        $users = $this->users->getPaginated(10, Input::get('orderBy', 'id'), Input::get('orderType', 'ASC'));
        return response()->json(array(
            'data' => view('webpanel.users.partials.list', compact('users'))->render(),
            'pagination' => sysView('includes.pagination', ['data' => $users])->render()
        ));
    }

    /**
     * Show the form for creating a new resource.
     * GET /users/create
     *
     * @return Response
     */
    public function create()
    {
        return View::make('webpanel.users.create');
    }

    /**
     * Store a newly created resource in storage.
     * POST /users
     *
     * @return Response
     */
    public function store()
    {
        $this->users->validator->with(Input::all());
        $this->users->validator->isValid();

        $user = $this->users->createUser(Input::all());
        return response()->json(array(
            'notification' => ReturnNotification(array('success' => 'Created Successfully')),
            'redirect' => sysRoute('users.index')
        ));
    }

    /**
     * Display the specified resource.
     * GET /users/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $id = decrypt($id);
        $user = $this->users->getById($id);
        if (is_null($user)) {
            throw new ResourceNotFoundException('User not Found');
        }
        return View::make('webpanel.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * PUT /users/{id}
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $id = decryptIt($id);
        $this->users->validator->with(Input::all());
        if (Input::get('password') == '' || !Input::get('password')) {
            $this->users->validator->setDefault('edit');
        }
        $this->users->validator->isValid();
        if ($this->users->updateUser($id, Input::all())) {
            return response()->json(array(
                'notification' => ReturnNotification(array('success' => 'User Info Saved Successfully')),
                'redirect' => route('webpanel.users.index')
            ));
        }
    }

    public function destroy($id)
    {
        //
    }


    public function getProfile()
    {
        $user = $this->users->getById(\Auth::user()->id);
        return View::make('webpanel.users.profile', compact('user'));
    }

    public function postProfile()
    {
        $this->users->validator->with(Input::all());
        $this->users->validator->setDefault('profile');
        $this->users->validator->isValid();

        $userData = Input::all();
        if (Input::hasFile('photo')) {
            $userData['photo_id'] = $this->users->uploadMedia(Input::file('photo'), true)->id;
            $this->users->deleteOldMedia(Input::get('old_photo'), true);;
        }
        if ($this->users->updateUser(\Auth::user()->id, $userData)) {
            return back()->with(array('success' => 'User Info Saved Successfully'));
            //return Redirect::route('admin.users.edit',array('id'=>$id))->with('success','User Saved Successfully');
        } else {
            redirect()->back()->with(array('error', 'Sorry! cannot perform the requested action at the moement'));
        }
    }

    /**
     * Get the change password view
     * @return \Illuminate\View\View
     */
    public function getChangePassword()
    {
        return View::make('webpanel.users.changepassword');
    }

    /**
     * Post from change password view
     * @return \Illuminate\View\View
     */

    public function postChangePassword()
    {
        $this->users->validator->with(Input::all())->setDefault('change_password')->isValid();

        if ($this->users->changePassword(Input::get('password'))) {
            return back()->with(array('success' => 'User Info Saved Successfully'));
        } else {
            back()->with(array('error', 'Sorry! cannot perform the requested action at the moement'));
        }
    }

    public function getResetPassword(Guard $auth, PasswordBroker $passwords, $id)
    {
        $user = $this->users->getById(decrypt($id));
        if (!$user) {
            throw new ApplicationException("Invalid User Data");
        }
        $password = str_random(6);
        if ($this->users->changePassword($password, $user)) {
            $emailService = new EmailService();
            $emailService->sendEmail('emails.users.password-reset', compact('user', 'password'), function ($email) use ($user) {
                $email
                    ->setSubject(\Config::get('strings.user.passwordReset'))
                    ->setTo($user->email);
            });
            return redirect()->route('webpanel.users.index')->with('success', 'Password Reset Successful');
        }
        throw new ApplicationException("Opps! Something went wrong. Please try again later");
    }


    public function getDefaultPermissions($type)
    {
        $userType = \App\Modules\Users\Types\UserType::find($type);
        return view('webpanel.users.partials.default-permissions', compact('userType'));
    }


    /*
     * Delete the users along with their related data like permissions.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function getDelete($id)
    {
        Authority::authorize('delete', 'users');
        if ($this->users->deleteUser(decryptIt($id))) {
            echo 1;
        } else {
            throw new Exception('Cannot delete User at the moment');
        }
    }

}