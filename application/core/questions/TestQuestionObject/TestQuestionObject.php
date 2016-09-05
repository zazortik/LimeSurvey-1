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
   * @return string
   */
  public function getAnswer()
  {
      return '<p>Some answer</p>';
  }

  /**
   * All question codes for this question
   * @return array
   */
  public function getQuestionCodes()
  {
      return array('123X234X345');
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
      $attributeNames = array();
      $attributeNames[] = ls\helpers\questionHelper::$attributes['hidden'];
      $attributeNames[0]['i18n'] = false;  // TODO: Why needed?
      $attributeNames[0]['name'] = 'hidden';  // TODO: Why needed?
      $attributeNames[0]['default'] = 0;  // TODO: Why needed?
      return $attributeNames;
  }

}
