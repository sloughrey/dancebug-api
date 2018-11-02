<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    /**
     * Stores an instance of the UserRepository used to communicate with the db.
     * Ideally this is an instance of a UserRepositoryInterface so you could swap out implementations easily.
     *
     * @var UserRepository
     */
    private $repository;

    public function __construct()
    {
        // This UserRepository class can be injected straight into the controllers as well
        $this->repository = new UserRepository();
    }

    /**
     * Returns a listing of all the users
     *
     * @return Response
     */
    public function all()
    {
        $users = $this->repository->all();
        if (!empty($users)) {
            return $this->sendSuccess($users);
        }
    }

    /**
     * Displays a user with a given id
     *
     * @param integer $id
     * @return Response
     */
    public function show($id)
    {
        $userData = $this->repository->show($id);
        if (!empty($userData)) {
            return $this->sendSuccess($userData);
        }

        return $this->redirectError(422, [], 'Resource does not exist');
    }

    /**
     * Creates a new user
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        // validate our user data
        $user = new User();
        $user->set('studioName', $request->input('studioName'));
        $user->set('studioID', (int) $request->input('studioID'));
        $user->set('firstName', $request->input('firstName'));
        $user->set('lastName', $request->input('lastName'));
        $user->set('gender', $request->input('gender'));
        $user->set('dob', $request->input('dob'));

        if ($user->hasErrors()) {
            return $this->redirectError(422, $user->getErrors());
        }

        if (!$user->save()) {
            if ($user->hasErrors()) {
                return $this->redirectError(400, $user->getErrors());
            }
        }

        return $this->sendSuccess([], 200, 'User successfully created');
    }

    /**
     * Sends a successful response to the user
     *
     * @param array $data
     * @param integer $statusCode
     * @param string $statusMsg
     * @return void
     */
    public function sendSuccess($data = [], $statusCode = 200, $statusMsg = '')
    {
        return response()->json(
            [
            'status' => 'success',
            'statusMsg' => $statusMsg,
            'data' => $data,
            'errors' => []
            ],
            $statusCode
        );
    }

    /**
     * Undocumented function
     *
     * @param array $errors
     * @param integer $statusCode
     * @param string $statusMsg
     * @return response
     */
    public function redirectError($statusCode, $errors = [], $statusMsg = '')
    {
        // return 422 status code for bad data
        return response()->json([
            'status' => 'error',
            'statusMsg' => $statusMsg,
            'errors' => $errors,
            'data' => []
        ], 422);
    }
}
