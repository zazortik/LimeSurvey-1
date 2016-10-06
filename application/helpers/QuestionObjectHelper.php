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
class QuestionObjectHelper {

    /**
     * @todo Check if extended type is core type
     * @todo Unsafe to require file from field in database (extendedType is in database)
     * @param array $row Stuff
     * @param string $fieldname
     */
    public static function getFieldmap(array $row, $fieldname)
    {
        $extendedType = $row['extended_type'];
        $file = '/core/questions/' . $extendedType . '/' . $extendedType . '.php';

        require_once(\Yii::app()->basePath . $file);

        //$r = new \ReflectionClass($extendedType);
        $m = new \ReflectionMethod($extendedType, 'getInstance');

        $question = $m->invoke(null);
        $question->setData(array(
            'sid' => $row['sid'],
            'gid' => $row['gid'],
            'qid' => $row['qid']
        ));
        return $question->getFieldmap($fieldname);
    }
}
