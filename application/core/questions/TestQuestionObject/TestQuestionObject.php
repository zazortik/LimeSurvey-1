<?php

/**
 * Test question object
 * Dummy system to be replaced, only here to investigate the communication
 * between old and new question system.
 */
class TestQuestionObject // extends QuestionObjectBase
{

  /**
   * HTML
   * @param array $ia
   * @return string
   */
  public function getAnswer(array $ia)
  {
      // $ia[1] = 123X234X345
      return '<p>Some answer: <input name="' . $ia[1] . '" type="text" /></p>';
  }

  /**
   * All question codes for this question
   * @param array $ia
   * @return array
   */
  public function getQuestionCodes(array $ia)
  {
      return array($ia[1]);
  }

  /**
   * Example of attribute:
        "max_num_value" = array(
        "types"=>"K",
        'category'=>gT('Input'),
        'sortorder'=>100,
        'inputtype'=>'text',
        "help"=>gT('Maximum sum value of multiple numeric input'),
        "caption"=>gT('Maximum sum value'));

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
   * @param array $ia Spec at top of file
   * @param array $questionAttributes
   * @param Question $question
   * @return array
   */
  public function getQuestionText(array $ia, array $QuestionAttributes, Question $questionModel)
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
          'code'               => $ia[2],           // Question code
          'number'             => '',               // ?
          'help'               => 'some help',      // Not used, question help is set in getQuestionReplacement and _ValidateQuestion?
          'mandatory'          => '',               // HTML content of mandatory sign (*)
          'man_message'        => '',               // HTML of mandatory message
          'valid_message'      => '',               // HTML when question is not valid
          'file_valid_message' => '',               // Only for file upload?
          'class'              => '',               // ?
          'man_class'          => '',               // ?
          'input_error_class'  => '',               // 'input-error' will show a red border
          'essentials'         => ''
      );
      return $questionText;
  }

}
