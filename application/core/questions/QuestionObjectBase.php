<?php

abstract class QuestionObjectBase
{
    /**
     * Specification for this array is in top of qanda helper.
     * @var array
     * @todo Rename to a more descriptive name
     */
    protected $ia;

    /**
     * List of attributes is in questionHelper.
     * @var array
     */
    protected $questionAttributes;

    /**
     * @var Question
     */
    protected $questionModel;

    /**
     * Data used to create fieldmap
     * @var array
     */
    protected $data;

    /**
     * @var TestQuestionObject
     */
    static protected $instance = null;

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

    /**
     * Called from qanda_helper.
     * @param array $questionAttributes
     * @return void
     */
    public function setQuestionAttributes(array $questionAttributes)
    {
        $this->questionAttributes = $questionAttributes;
    }

    /**
     * Called from qanda_helper.
     * @param Question $questionModel
     * @return void
     */
    public function setQuestionModel(Question $questionModel)
    {
        $this->questionModel = $questionModel;
    }
}
