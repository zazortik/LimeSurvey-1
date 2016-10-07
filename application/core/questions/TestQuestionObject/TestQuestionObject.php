<?php

Yii::import('application.core.questions.QuestionObjectBase', true);

/**
 * Test question object
 * Dummy system to be replaced, only here to investigate the communication
 * between old and new question system.
 * This is implemented as a singleton.
 *
 * Files with entry points calling this object:
 *   qanda_helper
 *   QuestionAttribute
 *   activate_helper
 *
 * Questions that have been modelled:
 *   Short text
 *   List
 *   List, dropdown
 *   List with comment
 */
class TestQuestionObject extends QuestionObjectBase
{
    /**
     */
    private function __construct()
    {
        // Nothing, use setIa() etc instead
    }

    /**
     * HTML for answers
     * This method will call renderPartial for views.
     * Called from qanda_helper.
     * @return string
     */
    public function getAnswer()
    {
        // $this->ia[1] = 123X234X345
        // As long as this code is provided, answer will be saved in database.

        // Short text question 'S'
        //$answer = '<p>Some answer: <input name="' . $this->ia[1] . '" type="text" /></p>';

        // Radio button (5-point choice etc) '5'
        //$answer = 'Alt 1: <input type="radio" name="' . $this->ia[1] . '" value=1 />';
        //$answer .= 'Alt 2: <input type="radio" name="' . $this->ia[1] . '" value=2 />';

        // List with dropdown '!'
        // Same logic with list with radio buttons
        /*
        $answerOptions = $this->questionModel->getOrderedAnswers(
            0,
            0
            //$questionAttributes['random_order'],
            //$questionAttributes['alphasort']
        );
        $answer = '<select name="' . $this->ia[1] . '">';
        foreach ($answerOptions as $answerOption) {
            traceVar($answerOption);
             // Example answer option:
			 // array
			 // (
			 // 'qid' => 9034
			 // 'code' => 'A2'
			 // 'answer' => 'answeropt2'
			 // 'sortorder' => 2
			 // 'language' => 'en'
			 // 'assessment_value' => 0
			 // 'scale_id' => 0
			 // )
            $answer .= '<option value="' . $answerOption['code'] . '">' . $answerOption['answer'] . ' </option>';
        }
        $answer .= '<select>';
         */

        // List with comment 'O'
        $answerOptions = $this->questionModel->getOrderedAnswers(
            0, 0
        );
        $answer = '<select name="' . $this->ia[1] . '">';
        $answer .= '<option value="">No answer</option>';
        foreach ($answerOptions as $answerOption) {
            $answer .= '<option value="' . $answerOption['code'] . '">' . $answerOption['answer'] . ' </option>';
        }
        $answer .= '<select>';
        $answer .= '<textarea name="' . $this->ia[1]  . 'comment' . '"></textarea>';

        return $answer;
    }

    /**
     * All question codes for this question
     * Called from qanda_helper.
     * @return array
     */
    public function getQuestionCodes()
    {
        return array($this->ia[1]);
    }

    /**
     * Example of attribute:
     * "max_num_value" = array(
     * "types"=>"K",
     * 'category'=>gT('Input'),
     * 'sortorder'=>100,
     * 'inputtype'=>'text',
     * "help"=>gT('Maximum sum value of multiple numeric input'),
     * "caption"=>gT('Maximum sum value'));
     *
     * Called from QuestionAttribute.
     * @return array
     */
    public function getAttributeNames()
    {
        // The 'hidden' attribute is mandatory, won't work without.
        $attributeNames = array();
        $attributeNames[] = ls\helpers\questionHelper::$attributes['hidden'];
        $attributeNames[0]['i18n'] = false;  // TODO: Why needed?
        $attributeNames[0]['name'] = 'hidden';  // TODO: Why needed?
        $attributeNames[0]['default'] = 0;  // TODO: Why needed?

        return $attributeNames;
    }

    /**
     * Same as composeQuestionText in qanda.
     * Called from qanda_helper.
     * @return array
     */
    public function getQuestionText()
    {
      /*
      $questionText = array(
          'all'                 => '',              // All has been added for backwards compatibility with templates that use question_start.pstpl (now redundant)
          'text'               => $ia[3],
          'code'               => $ia[2],
          'number'             => '',
          'help'               => '',
          'mandatory'          => '',
          'man_message'        => '',
          'valid_message'      => '',
          'file_valid_message' => '',
          'class'              => '',
          'man_class'          => '',
          'input_error_class'  => '',              // provides a class.,
          'essentials'         => ''
      );
       */
        $questionText = array(
            'all'                 => '',              // All has been added for backwards compatibility with templates that use question_start.pstpl (now redundant)
            'text'               => 'some text',      // Question text (not answer)
            'code'               => $this->ia[2],           // Question code
            'number'             => '',               // ?
            'help'               => 'some help',      // Not used? Question help is set in getQuestionReplacement and _ValidateQuestion.
            'mandatory'          => '',               // HTML content of mandatory sign (*)
            'man_message'        => '',               // HTML of mandatory message
            'valid_message'      => '',               // HTML when question is not valid
            'file_valid_message' => '',               // Only for file upload?
            'class'              => '',               // ?
            'man_class'          => '',               // ?
            'input_error_class'  => '',               // 'input-error' will show a red border
            'essentials'         => ''                // ?
        );
        return $questionText;
    }

    /**
     * Called from activateSurvey() in activate_helper.
     * Example input:
     * Array
		(
			[fieldname] => 194171X595X9034
			[type] => ?
			[sid] => 194171
			[gid] => 595
			[qid] => 9034
			[aid] =>
			[title] => questioncode
			[question] => question text
			[group_name] => My first question group
			[mandatory] => N
			[hasconditions] => N
			[usedinconditions] => N
			[questionSeq] => 0
			[groupSeq] => 0
			[relevance] => 1
			[grelevance] => 1
			[preg] =>
			[other] => N
			[help] => question help
		)
     * @return array Column SQL definition adapted to Yii and LS, with key being the field name
     *               Example: array('123X234X345' => 'string(5))
     * @todo This method, like getFieldmap, should be automatic
     */
    public function getDatabaseFieldTypes(array $row)
    {
        // List dropdown uses string(5)
        //return 'string(5)';

        // List with comment
        return array(
            $row['fieldname'] => $row['database']
        );
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
    /**
     * Called from createFieldMap
     * Array
		(
			[fieldname] => 194171X595X9034
			[type] => ?
			[sid] => 194171
			[gid] => 595
			[qid] => 9034
			[aid] =>
			[title] => questioncode
			[question] => question text
			[group_name] => My first question group
			[mandatory] => N
			[hasconditions] => N
			[usedinconditions] => N
			[questionSeq] => 0
			[groupSeq] => 0
			[relevance] => 1
			[grelevance] => 1
			[preg] =>
			[other] => N
			[help] => question help
		)
     * @todo Move parts to config.json, then build dynamically. Should not be needed to build manually.
     * @param string $fieldname Like 'sidXgidXqid'
     * @return array
     */
    public function getFieldmap($fieldname)
    {
        return array(
            $fieldname => array(
                'fieldname' => $fieldname,
                'sid' => $this->data['sid'],
                'gid' => $this->data['gid'],
                'qid' => $this->data['qid'],
                'aid' => null,
                'title' => 'something',
                'question' => 'The text',
                'group_name' => 'group name',
                'mandatory' => 'N',
                'hasconditions' => 'N',
                'usedinconditions' => 'N',
                'questionSeq' => 0,
                'groupSeq' => 0,
                'relevance' => 1,
                'grelevance' => 1,
                'preg' => null,
                'other' => 'N',
                'help' => 'question help',
                'type' => '?',
                'extended_type' => 'TestQuestionObject',
                'database' => 'string(5)'
            ),
            $fieldname . 'comment' => array(
                'fieldname' => $fieldname . 'comment',
                'sid' => $this->data['sid'],
                'gid' => $this->data['gid'],
                'qid' => $this->data['qid'],
                'aid' => null,
                'title' => 'something',
                'question' => 'The text',
                'group_name' => 'group name',
                'mandatory' => 'N',
                'hasconditions' => 'N',
                'usedinconditions' => 'N',
                'questionSeq' => 0,
                'groupSeq' => 0,
                'relevance' => 1,
                'grelevance' => 1,
                'preg' => null,
                'other' => 'N',
                'help' => 'question help',
                'type' => '?',
                'extended_type' => 'TestQuestionObject',
                'database' => 'text'
            )
        );
    }
}
