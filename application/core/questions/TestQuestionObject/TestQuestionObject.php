<?php

/**
 * Test question object
 */
class TestQuestionObject // extends QuestionObjectBase
{

  /**
   * @return string
   */
  public function getAnswer()
  {
      return '<p>Some answer</p>';
  }

  /**
   * @return array
   */
  public function getQuestionCodes()
  {
  }
}
