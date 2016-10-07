<?php

Yii::import('application.core.questions.QuestionObjectBase', true);

/**
 * Basic array question type
 * @since 2016-10-06
 * @author Olle Haerstedt
 */
class ArrayQuestionObject extends QuestionObjectBase
{
    /**
     * @return 
     */
    public function getFieldMap($fieldname)
    {
        $subQuestions = Question::model()->getSubQuestions($this->data['qid'])->readAll();
        tracevar($fieldname);

        $fieldnames = array();
        foreach ($subQuestions as $subQuestion) {
            $fieldnames[$fieldname . '_' . $subQuestion['title']] = array(
                'fieldname' => $fieldname . '_' . $subQuestion['title'],
                'sid' => $this->data['sid'],
                'gid' => $this->data['gid'],
                'qid' => $this->data['qid'],
                'aid' => null,
                'title' => $subQuestion['title'],
                'question' => $subQuestion['question'],
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
                'extended_type' => 'ArrayQuestionObject',
                'database' => 'string(5)'
            );
        }

        return $fieldnames;
    }

    /**
     * 
     * @return 
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
     * 
     * @return 
     */
    public function getQuestionText()
    {
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
     * @return string html
     */
    public function getAnswer()
    {
        // List with comment 'O'
        $answerOptions = $this->questionModel->getOrderedAnswers(0, 0);
        $subQuestions = $this->questionModel->getSubQuestions()->readAll();

        $answer = '<table>';
        foreach ($subQuestions as $subQuestion) {
            $answer .= '<tr>';
            $answer .= '<td>';
            $answer .= $subQuestion['question'];
            $answer .= '</td>';
            foreach ($answerOptions as $answerOption) {
                $answer .= '<td>';
                $answer .= $answerOption['answer'];
                $answer .= '<input type="radio" name="' . $this->ia[1] . '_' . $subQuestion['title']. '" value="' . $answerOption['code'] . '" />';
                $answer .= '</td>';
            }
            $answer .= '</tr>';
        }
        $answer .= '</table>';

        return $answer;
    }

    /**
     * All question codes for this question
     * Called from qanda_helper.
     * @return array
     */
    public function getQuestionCodes()
    {
        $subQuestions = $this->questionModel->getSubQuestions()->readAll();
        $codes = array();
        foreach ($subQuestions as $subQuestion) {
            $codes[] = $this->ia[1] . '_' . $subQuestion['title'];
        }
        return $codes;
    }

    public function getDatabaseFieldTypes(array $row)
    {
        return array(
            $row['fieldname'] => $row['database']
        );
    }

}
