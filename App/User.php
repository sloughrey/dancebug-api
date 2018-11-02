<?php

namespace App;

use App\Database;

class User
{
    /**
     * The main user table name
     *
     * @var string
     */
    protected $coreTable = 'tblstudios';

    /**
     * The unique id for this user
     *
     * @var int
     */
    protected $dancerID;

    /**
     * The dance studio the user belongs to
     *
     * @var string
     */
    protected $studioName;

    /**
     * The studio id to be used in a studio lookup table if we had one
     *
     * @var int
     */
    protected $studioID;

    /**
     * The first name of the user
     *
     * @var string
     */
    protected $firstName;

    /**
     * The last name of the user
     *
     * @var string
     */
    protected $lastName;

    /**
     * The gender of the user
     *
     * @var string
     */
    protected $gender;

    /**
     * The date of birth of the user
     *
     * @var string
     */
    protected $dob;

    /**
     * An array of errors encountered using the user object.
     * Object is valid if errors array is empty
     *
     * @var array
     */
    protected $errors = [];

    /**
     * Simple validation rules for our class properties, set in the construct
     *
     * @var array
     */
    protected $propValidationRules = [];

    public function __construct($userId = null)
    {
        if ($userId) {
            $this->dancerID = $userId;
        }

        // Create our validation rules array
        $this->propValidationRules = [
            'studioName' => [
                'maxLength' =>  100,
                'type' => 'string',
            ],
            'studioID' => [
                'maxLength' =>  11,
                'type' => 'integer'
            ],
            'firstName' => [
                'maxLength' =>  100,
                'type' => 'string'
            ],
            'lastName' => [
                'maxLength' =>  100,
                'type' => 'string'
            ],
            'gender' => [
                'maxLength' =>  20,
                'type' => 'string'
            ],
            'dob' => [
                'type' => 'date'
            ]
        ];
    }

    /**
     * Used to set and validate our class properties
     *
     * @param string $prop
     * @param mixed $val
     * @return void
     */
    public function set($prop, $val)
    {
        if (property_exists($this, $prop)) {
            if ($this->validateProp($prop, $val) === true) {
                $this->$prop = $val;
            } else {
                $this->errors[] = "Property '$prop' could not be validated, please ensure your value is in the correct format";
            }
        }
    }

    /**
     * Used to get our class property values and perform any additional logic needed
     *
     * @param string $prop
     * @return mixed
     */
    public function get($prop)
    {
        if (property_exists($this, $prop)) {
            return $this->prop;
        }
    }

    /**
     * Validate the max length and type of our properties
     *
     * @param string $prop
     * @param mixed $val
     * @return boolean|string
     */
    protected function validateProp($prop, $val)
    {
        if (!$prop || !$val) {
            throw new \Exception('Please provide all required parameters.');
        }

        if (isset($this->propValidationRules[$prop])) {
            foreach ($this->propValidationRules[$prop] as $k => $v) {
                switch ($k) {
                    case 'maxLength':
                        if (strlen($val) > $v) {
                            $this->errors[] = "Property '$prop' cannot be longer than $v characters.";
                        }
                        break;
                    case 'type':
                        switch ($v) {
                            case 'date':
                                // date validation for yyyy/mm/dd
                                if (preg_match("^\\d{4}-\\d{2}-\\d{2}^", $val) !== 1) {
                                    $this->errors[] = "Property '$prop' has an invalid date format.  Please use yyyy/mm/dd.";
                                };
                                break;
                            default:
                                if (gettype($val) != $v) {
                                    $this->errors[] = "Property '$prop' must be a '$v'.";
                                }
                        }
                        break;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Check if our user object has any errors or not
     *
     * @return boolean
     */
    public function hasErrors()
    {
        if (!empty($this->errors)) {
            return true;
        }

        return false;
    }

    /**
     * Returns the array of errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Returns a list of properties required for the creation of the User
     *
     * @return array
     */
    protected function requiredProps()
    {
        return [
            'studioName', 'studioID', 'firstName', 'lastName', 'gender', 'dob'
        ];
    }

    /**
     * Checks to see if this user has all of the required properties for saving
     *
     * @return boolean
     */
    protected function hasRequiredProps()
    {
        foreach ($this->requiredProps() as $prop) {
            if (!$this->$prop) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns an array representation of the user object
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'studioName' => $this->studioName,
            'studioID' => $this->studioID,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'gender' => $this->gender,
            'dob' => $this->dob
        ];
    }

    /**
     * Loads the user from the database
     *
     * @return boolean
     */
    public function load()
    {
        if (!$this->dancerID) {
            throw new \Exception('Trying to load user without id set');
        }

        $mysqli = Database::getDBObj();
        $sql = 'SELECT StudioName, StudioID, FirstName, LastName, Gender, DOB 
                
                FROM ' . $this->coreTable . '
                
                WHERE DancerID = ?';
        
        if (!($stmt = $mysqli->prepare($sql))) {
            throw new \Exception("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);
        }
        if (!$stmt->bind_param("i", $this->dancerID)) {
            throw new Exception("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        if (!$stmt->execute()) {
            throw new \Exception("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        if (!($res = $stmt->get_result())) {
            throw new \Exception("Getting result set failed: (" . $stmt->errno . ") " . $stmt->error);
        }

        if ($res->num_rows === 1) {
            $row = $res->fetch_assoc();

            $this->studioName = $row['StudioName'];
            $this->studioID = $row['StudioID'];
            $this->firstName = $row['FirstName'];
            $this->lastName = $row['LastName'];
            $this->gender = $row['Gender'];
            $this->dob = $row['DOB'];
        } else {
            return false;
        }

        return true;
    }

    public function save()
    {
        // make sure we have all required properties before saving
        if (!$this->hasRequiredProps()) {
            $this->errors[] = 'All mandatory fields must be submitted.  Mandatory fields are: ' . implode(',', $this->requiredProps());
            return false;
        }

        $mysqli = Database::getDBObj();
        if (!$this->dancerID) {
            $sql = 'INSERT INTO ' . $this->coreTable . ' (StudioName, StudioID, FirstName, LastName, Gender, DOB)
            VALUES (?, ?, ?, ?, ?, ?)';

            if (!($stmt = $mysqli->prepare($sql))) {
                return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->bind_param("sissss", $this->studioName, $this->studioID, $this->firstName, $this->lastName, $this->gender, $this->dob)) {
                return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->execute()) {
                return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }

            // set the last insert id
            $this->dancerID = $mysqli->insert_id;
        } else {
            $sql = 'UPDATE ' . $this->coreTable . ' SET StudioName = ?, StudioID = ?, FirstName = ?, LastName = ?, Gender = ?, DOB = ?';
            
            if (!($stmt = $mysqli->prepare($sql))) {
                return "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
            }
            if (!$stmt->bind_param("sissss", $this->studioName, $this->studioID, $this->firstName, $this->lastName, $this->gender, $this->dob)) {
                return "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
            }
            if (!$stmt->execute()) {
                return "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            }
        }
        
        return true;
    }
}
