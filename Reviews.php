<?php

class Reviews
{
    //the data for connection with database
    const dbName = 'tutor';
    const dbHost = 'localhost';
    const dbUser = 'root';
    const dbPassw = '';

    const table = 'reviews';

    protected $types = [
        'name' => 'string',
        'email' => 'email',
        'text' => 'text'
    ];

    protected $requiredFields = ['name', 'email', 'text'];

    protected $connection;

    public $errors = [];

    function __construct()
    {
        //connection with database
        try {
            $this->connection = new \PDO("mysql:dbname=" . self::dbName . ";host=" . self::dbHost, self::dbUser, self::dbPassw);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("set names utf8");
        } catch (PDOException $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * The get all rows in table
     *
     * @return array
     */
    public function getAllReviews()
    {
        //я просто ограничила, т.к. совсем без ограничений не есть хорошо. Но в ТЗ было написано без пагинации
        $sql = 'SELECT * FROM `reviews` ORDER BY `date` DESC LIMIT 0, 20';
        $statement = $this->connection->prepare($sql);
        $statement->execute();
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $result = [];
        while ($res = $statement->fetch()) {
            $result[] = $res;
        }

        return $result;
    }

    /**
     * @param $field
     * @param $type
     * @return bool|int|string
     */
    protected function conversion($field, $type)
    {
        switch ($type) {
            case 'integer':
                $field = (int)$field;

                break;
            case 'string':
                $field = substr($field, 0, 255);

                break;
            default:

                break;
        }

        return $field;
    }

    /**
     * @param $field
     * @param string $type
     * @return mixed
     */
    protected function filter($field, $type = 'string')
    {
        switch ($type) {
            case 'email':
                $options = [
                    'options' => ['regexp' => '/^[a-z0-9а-яё@\-\_\.]+$/uis']
                ];

                break;
            case 'name':
                $options = [
                    'options' => ['regexp' => '/^[a-z0-9а-яё\s\-]+$/uis']
                ];

                break;
            default:
                $options = [];

                break;
        }

        if (!empty($options)) {
            $field = filter_var($field, FILTER_VALIDATE_REGEXP, $options);
        }

        return $field;
    }

    /**
     * @param $field
     * @param $type
     * @return bool|int|mixed|string
     */
    protected function checkField($field, $type, $name)
    {
        $field = $this->conversion($field, $type);

        if (strlen((string)$field) > 0) {
            $field = $this->filter($field, $type);
        }

        if (!$this->requiredField($name, $field)) {
            $this->errors[] = 'Поле ' . $name . ' обязательно для заполнения';
        }

        return $field;
    }

    protected function requiredField($name, $field)
    {
        if (in_array($name, $this->requiredFields) && (empty($field) || strlen($field) < 2)) {
            return false;
        }

        return true;
    }

    /**
     * @param $fields
     * @return mixed
     */
    protected function checkFields($fields)
    {
        foreach ($fields as $key => &$field) {
            if (!empty($this->types[$key])) {
                $field = $this->checkField($field, $this->types[$key], $key);
            } else {
                $field = '';
            }
        }

        return $fields;
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function getFields($fields)
    {
        $fields = $this->checkFields($fields);

        return $fields;
    }

    public function addReview($fields)
    {
        $sql = 'INSERT INTO `reviews` (`name`, `email`, `text`) VALUE (:name, :email, :text)';

        try {
            $statement = $this->connection->prepare($sql);
            $statement->execute([
                'name' => $fields['name'],
                'email' => $fields['email'],
                'text' => $fields['text'],
            ]);
        } catch (PDOException $e) {
            $this->errors[] = $e->getMessage();
            return false;
        }

        return true;
    }
}