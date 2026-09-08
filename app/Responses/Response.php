<?php

namespace App\Responses;

/**
 * Class Response
 * @package App\Domains\Base\Responses
 */
abstract class Response
{
    /**
     * Info if reply is isValid.
     *
     * @var bool
     */
    private $isValid = true;

    /**
     * Errors.
     *
     * @var array
     */
    private $errors = [];

    /**
     * Data.
     *
     * @var null
     */
    private $data = null;

    /**
     * Error code.
     *
     * @var int
     */
    private $code = 200;

    /**
     * Returns data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Adds data to return.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns information if that is valid response.
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * setup response as invalid
     *
     * @return $this
     */
    public function invalid()
    {
        $this->isValid = false;
        return $this;
    }

    /**
     * Returns error messages.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Returns latest error.
     *
     * @return string|false false when array is empty
     */
    public function getLastError()
    {
        return end($this->errors);
    }

    /**
     * Setup error messages.
     *
     * @param array $errors Error messages
     * @return $this
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        $this->invalid();
        return $this;
    }

    /**
     * Add error.
     *
     * @param string $message komunikat z błędem
     * @return $this
     */
    public function addError(string $message)
    {
        $this->errors[] = trans($message);
        $this->invalid();
        return $this;
    }

    /**
     * Add error
     *
     * @param string $key
     * @param string $message
     * @return $this
     */
    public function addErrorWithKey(string $key, string $message)
    {
        $this->errors[$key] = trans($message);
        $this->invalid();
        return $this;
    }

    /**
     * Returns code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return (int) $this->code;
    }

    /**
     * Setup response code.
     *
     * @param int $code Error code
     *
     * @return $this
     */
    public function setCode(int $code)
    {
        $this->code = (int) $code;
        return $this;
    }

    /**
     * Set code for: Not Found
     *
     * @return Response
     */
    public function setCodeAsNotFound()
    {
        $this->invalid();
        return $this->setCode(404);
    }

    /**
     * Set code for: Validation Fail
     *
     * @return Response
     */
    public function setCodeAsValidationFail()
    {
        $this->invalid();
        return $this->setCode(409);
    }

    /**
     * Set code for: UnauthorizedAccess
     *
     * @return Response
     */
    public function setCodeAsUnauthorizedAccess()
    {
        $this->invalid();
        return $this->setCode(403);
    }

    /**
     * Set code for: UnauthorizedAccess
     *
     * @return Response
     */
    public function setCodeAsUnauthenticatedAccess()
    {
        $this->invalid();
        return $this->setCode(401);
    }
}
