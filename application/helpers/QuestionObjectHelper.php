<?php

/**
* LimeSurvey
* Copyright (C) 2007-2016 The LimeSurvey Project Team / Carsten Schmitz
* All rights reserved.
* License: GNU/GPL License v2 or later, see LICENSE.php
* LimeSurvey is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
namespace ls\helpers;

/**
 * Entry point for the question object system.
 * @since 2016-10-06
 * @author Olle Haerstedt
 */
final class QuestionObjectHelper {

    /**
     * Used in createFieldMap.
     * @todo Unsafe to require file from field in database (extendedType is in database)
     * @param array $row Stuff
     * @param string $fieldname
     * @return array
     */
    public static function getFieldmap(array $row, $fieldname)
    {
        $question = self::getInstance($row['extended_type']);
        $question->setData(array(
            'sid' => $row['sid'],
            'gid' => $row['gid'],
            'qid' => $row['qid']
        ));
        return $question->getFieldmap($fieldname);
    }

    /**
     * Used in activate helper.
     * @param array $row
     * @return array
     */
    public static function getDatabaseFieldTypes($row)
    {
        $question = self::getInstance($row['extended_type']);
        return $question->getDatabaseFieldTypes($row);
    }

    /**
     * @param string $extendedType
     */
    public static function getQuestionText($extendedType)
    {
        $question = self::getInstance($extendedType);
        return $question->getQuestionText();
    }

    /**
     * @todo Order of this and getQuestionCodes matter, should be fixed.
     * @param string $extendedType
     * @return string html
     */
    public static function getAnswer($extendedType, $ia, $aQuestionAttributes, $oQuestion)
    {
        $question = self::getInstance($extendedType);
        $question->setIa($ia);
        $question->setQuestionAttributes($aQuestionAttributes);
        $question->setQuestionModel($oQuestion);
        return $question->getAnswer();
    }

    /**
     * @param string $extendedType
     * @return array
     */
    public static function getQuestionCodes($extendedType)
    {
        $question = self::getInstance($extendedType);
        return $question->getQuestionCodes();
    }

    /**
     * @param string $extendedType
     * @return array
     */
    public static function getAttributeNames($extendedType)
    {
        $question = self::getInstance($extendedType);
        return $question->getAttributeNames();
    }

    /**
     * @todo Validate $extendedType
     * @todo Check if extended type is core type
     * @param string $extendedType
     * @return object of $extendedType
     */
    private static function getInstance($extendedType)
    {
        $file = '/core/questions/' . $extendedType . '/' . $extendedType . '.php';

        require_once(\Yii::app()->basePath . $file);

        $m = new \ReflectionMethod($extendedType, 'getInstance');
        return $m->invoke(null);
    }
}
