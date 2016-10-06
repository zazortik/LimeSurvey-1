<?php

abstract class QuestionObjectBase
{
    /**
     * Specification for this array is in top of qanda helper.
     * @var array
     * @todo Rename to a more descriptive name
     */
    private $ia;

    /**
     * List of attributes is in questionHelper.
     * @var array
     */
    private $questionAttributes;

    /**
     * @var Question
     */
    private $questionModel;

    /**
     * Data used to create fieldmap
     * @var array
     */
    private $data;

    /**
     * @var TestQuestionObject
     */
    static private $instance = null;

    /**
     * @return TestQuestionObject
     */
    public static function getInstance()
    {
        $inheritedClassName = get_called_class();  // Late static binding
        if (empty(self::$instance))
        {
            self::$instance = new $inheritedClassName();
        }

        return self::$instance;
    }

    /**
     * Set data needed to create the fieldmap
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * Called from qanda_helper.
     * @param array $ia
     * @return void
     */
    public function setIa(array $ia)
    {
        $this->ia = $ia;
    }
}
