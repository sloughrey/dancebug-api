<?php

namespace App\Controllers;

use App\User;
use App\UserRepository;
use App\ResourceControllerInterface;

class UsersController implements ResourceControllerInterface
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
    public function create()
    {
        // validate our user data
        $user = new User();
        $user->set('studioName', $_POST['studioName']);
        $user->set('studioID', (int) $_POST['studioID']);
        $user->set('firstName', $_POST['firstName']);
        $user->set('lastName', $_POST['lastName']);
        $user->set('gender', $_POST['gender']);
        $user->set('dob', $_POST['dob']);

        if ($user->hasErrors()) {
            return $this->redirectError(422, $user->getErrors());
        }

        if ($user->save() !== true) {
            if ($user->hasErrors()) {
                return $this->redirectError(400, $user->getErrors());
            } else {
                throw new Exception('User could not be saved and had no errors set');
            }
        }

        return $this->sendSuccess(['user' => $user->toArray()], 200, 'User successfully created');
    }

    public function update()
    {
        // read in our put data
        parse_str(file_get_contents("php://input"), $_PUT);
        foreach ($_PUT as $key => $value)
        {
            unset($_PUT[$key]);
            $_PUT[str_replace('amp;', '', $key)] = $value;
        }

         // validate our user data
         $user = new User((int) $_PUT['dancerID']);
         $user->load();

         $user->set('studioName', $_PUT['studioName']);
         $user->set('studioID', (int) $_PUT['studioID']);
         $user->set('firstName', $_PUT['firstName']);
         $user->set('lastName', $_PUT['lastName']);
         $user->set('gender', $_PUT['gender']);
         $user->set('dob', $_PUT['dob']);
 
         if ($user->hasErrors()) {
             return $this->redirectError(422, $user->getErrors());
         }
 
         if ($user->save() !== true) {
             if ($user->hasErrors()) {
                 return $this->redirectError(400, $user->getErrors());
             } else {
                 throw new Exception('User could not be saved and had no errors set');
             }
         }
 
         return $this->sendSuccess(['user' => $user->toArray()], 200, 'User successfully updated');
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
        http_response_code(200);
        return [
            'status' => 'success',
            'statusMsg' => $statusMsg,
            'errors' => [],
            'data' => $data
        ];
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
        http_response_code($statusCode);
        // return 422 status code for bad data
        return [
            'status' => 'error',
            'statusMsg' => $statusMsg,
            'errors' => $errors,
            'data' => []
        ];
    }
}
