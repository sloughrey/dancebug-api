<?php

namespace App\Repositories;

use App\User;
use App\Database;

class UserRepository
{
    /**
     * The table to grab our users from
     *
     * @var string
     */
    protected $userTable = 'tblstudios';

    /**
     * Returns a collection of all users
     *
     * @return array
     */
    public function all()
    {
        $mysqli = Database::getDBObj();
        $sql = 'SELECT t.DancerID, t.StudioName, t.StudioID, t.FirstName, t.LastName, t.Gender, t.DOB, t.DateCreated
        
                FROM ' . $this->userTable . ' AS t';
        $result = $mysqli->query($sql);

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        return $users;
    }

    /**
     * Returns a single user by id
     *
     * @param integer $id
     * @return array
     */
    public function show($id)
    {
        if (!is_numeric($id)) {
            throw new Exception('Please provide a valid numeric user id');
        }

        $user = new User($id);
        if (!$user->load()) {
            return [];
        }

        return $user->toArray();
    }
}
