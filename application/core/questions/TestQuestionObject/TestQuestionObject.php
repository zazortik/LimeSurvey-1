<?php

/**
 * Test question object
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
  }

  /**
   * @return array
   */
  public function getQuestionAttributes()
  {
  }

}
