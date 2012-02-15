<?php
/*
 * LimeSurvey
 * Copyright (C) 2007 The LimeSurvey Project Team / Carsten Schmitz
 * All rights reserved.
 * License: GNU/GPL License v2 or later, see LICENSE.php
 * LimeSurvey is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 *
 * $Id$
 */


include_once("login_check.php");


// Todo: This is a dead script. Either use it or trash it.
//OK: Here's how it works so far!

/*include this file in a php script

* The two "overall" functions are:
*   - giveMeRawDataFromFieldNames($surveyid, $gid, $qid, $fieldarray, responsestyle)
*     $surveyid = the survey id number
*     $gid = the group id for the question you want results for
*     $qid = the question id for the question you want results for
*     $fieldarray = a keyed array containing the fieldnames as key, and the value for of that fieldname that you want matches to
*        -ie: array ("1X2X34"=>"FL", "1X2X35"=>"Cork")
*     $responsestyle should be "full" - the alternative (which would just be the codes) is not yet coded
*
*     This function will return a multi-level array. The first level is a numbered array with an
*     entry for every individual response. Within that array will be a further array, with an entry
*     for every possible answer to that question. Then, within each of those arrays will be
*     the question title, and the answer to that question for that record.
*
*     As an example, a single answer type question (like a List, or Yes/No)
*     will look like this:
*     Array
*     (
*       [0]=> Array
*           (
*              [4X6X22] => Array
*                       (
*                          [0] => What is your Employment Type?
*                          [1] => Ongoing
*                       )
*           )
*       [1]=> Array
* 			..... and so forth for each record in the responses table
*
*     A multiple answer type question, such as "Multiple Choice" will look like this:
*     Array
*     (
*       [0]=> Array
*           (
*             [2X2X7C] => Array
*                       (
*                          [0] => Choose your favourite foods [Cheese]
*                          [1] => Yes
*                       )
*             [2X2X7I] => Array
*                       (
*                          [0] => Choose your favourite foods [Ice Cream]
*                          [1] =>
*                       )
*           )
*
*   - makeSummaryFromRawData($results)
*     Feed this function the "raw results" string generated by the "giveMeRawDataFromFieldNames"
*     function and you'll be returned an array containing a summary/count of results
*     for every possible answer within that question.
*
*     An example result for a single option question (ie: List or Yes/No) is:
*
*     Array
*     (
*         [4X6X22] => Array
*             (
*                 [question] => What is your Employment Type?
*                 [summary] => Array
*                     (
*                         [Ongoing] => 429
*                         [Fixed Term] => 61
*                         [None] => 4
*                         [Agency / Temp] => 4
*                         [Casual] => 1
*                     )
*
*             )
*     )
*
*     An example result for a multiple answer type question is:
*     Array
*     (
*         [2X2X7G] => Array
*             (
*                 [question] => Which of the following issues do you want addressed in negotiations for the next Enterprise Bargaining Agreement? [Accrual of Time In Lieu / Flex Time]
*                 [summary] => Array
*                     (
*                         [Yes] => 10
*                         [None] => 3
*                     )
*
*             )
*
*         [2X2X7K] => Array
*             (
*                 [question] => Which of the following issues do you want addressed in negotiations for the next Enterprise Bargaining Agreement? [Allowances / Reimbursement of Expenses]
*                 [summary] => Array
*                     (
*                         [Yes] => 5
*                         [None] => 8
*                     )
*
*             )
*     )
*
*/

//THIS BIT IS JUST FOR DEBUGGING
if (!empty($_GET['debug'])) {
    //    $table = "survey_4";
    //	//$questionfields[]="4X6X22";
    //	//$questionfields[]="4X6X23";
    //	$limitby=array("4X6X22"=>"A",
    //				   "4X6X23"=>"B");
    //	$sql = buildSqlFromFieldnamesArray($limitby);
    //	$questionfields=buildQuestionFields("4", "7", "29");
    //	if (!empty($questionfields)) {
    //		$results = returnQuestionResults($table, $questionfields, $sql);
    //		echo "TOTAL RESULTS: ".count($results);
    //		echo "<pre>";
    //		print_r($results);
    //		echo "</pre>";
    //	}
    $surveyid=$_GET['sid'];
    $gid=$_GET['gid'];
    $qid=$_GET['qid'];
    //$results = giveMeRawDataFromFieldNames("4", "6", "22", array(), "full");
    //$results = giveMeRawDataFromFieldNames("2", "2", "7", array(), "full");
    //$results = giveMeRawDataFromFieldNames("8", "18", "66", array(), "full");
    //$results = giveMeRawDataFromFieldNames("29", "89", "559", array(), "full");
    $results = giveMeRawDataFromFieldNames($surveyid, $gid, $qid, array(), "full");

    $summary = makeSummaryFromRawData($results);
    foreach ($results as $result) { foreach ($result as $answer) {echo $answer[1];} }
    echo "<pre>";
    print_r($results);
    echo "</pre>";
    //
    //	foreach ($summary as $sum) {
    //		echo "<table width='400' align='center' border='1'>\n";
    //		echo "<tr><td colspan='2' align='center' bgcolor='silver'>".$sum['question']."</td></tr>\n";
    //		foreach ($sum['summary'] as $key=>$val) {
    //			echo "<tr><td align='right' valign='top'><strong>$key</strong></td><td>$val</td></tr>\n";
    //		}
    //		echo "</table><br />";
    //	}
}

function makeSummaryFromRawData($results, $surveyid=null, $gid=null, $qid=null) {

    //echo "<pre>";print_r($results);echo "</pre>";
    if (empty($results)) {
        return array();
    }
    if (!empty($qid)) {
        $thisquestion=getQuestionInfo($qid);
    }
    $rowcodes=array_keys($results[0]);
    //	echo "<pre>";print_r($rowcodes);echo "</pre>";

    $summary = array();
    foreach ($results as $result) {
        foreach($rowcodes as $row) {
            //echo "<pre>";print_r($result);echo "</pre>";

            if (is_array($result[$row])) {
                //echo "<pre>";print_r($result);echo "</pre>";
                $summary[$row]['question']=$result[$row][0];
                switch($thisquestion['type']) {
                    case "T":
                    case "S":
                    case "H":
                        if ($result[$row][1] != "") {
                            if (!isset($summary[$row]['summary'][$clang->gT("Answered")])) {
                                $summary[$row]['summary'][$clang->gT("Answered")]=1;
                            } else {
                                $summary[$row]['summary'][$clang->gT("Answered")]++;
                            }
                        }
                        break;
                    default:
                        if (!isset($summary[$row]['summary'][$result[$row][1]])) {
                            $summary[$row]['summary'][$result[$row][1]]=1;
                        } else {
                            $summary[$row]['summary'][$result[$row][1]]++;
                        }
                        break;
                }
            }
            if ($thisquestion['type'] == "N") {
                ksort($summary[$row]['summary'], SORT_NUMERIC);
            }
        }
    }
    //echo "<pre>";print_r($summary);echo "</pre>";
    //fill in the blanks from answer table and sort
    if (isset($surveyid) && isset($qid) && $summary) {
        //$thissurvey=getSurveyInfo($surveyid);
        $rowcodes=array_keys($summary);
        switch($thisquestion['type']){
            case "F":
            case "H":
                $answers=getLabelSet($thisquestion['lid']);
                break;
            case "!":
            case "L":
            case "O":
                $answers=getAnswersSingle($surveyid, $gid, $qid);
                $answers[]=array("code"=>"", "answer"=>$clang->gT("No answer"));
                break;
            case "1":
                $answers=getLabelSet($thisquestion['lid']);
                $answers[]=array("code"=>"", "answer"=>$clang->gT("No answer"));
                break;
            case "C":
                $answers[]=array("code"=>"Y", "answer"=>$clang->gT("Yes"));
                $answers[]=array("code"=>"U", "answer"=>$clang->gT("Uncertain"));
                $answers[]=array("code"=>"N", "answer"=>$clang->gT("No"));
                break;
            case "E":
                $answers[]=array("code"=>"I", "answer"=>$clang->gT("Increase"));
                $answers[]=array("code"=>"S", "answer"=>$clang->gT("Same"));
                $answers[]=array("code"=>"D", "answer"=>$clang->gT("Decrease"));
                $answers[]=array("code"=>"", "answer"=>$clang->gT("No answer"));
                break;
            case "M":
            case "P":
                $answers[]=array("code"=>"Y", "answer"=>$clang->gT("Yes"));
                $answers[]=array("code"=>"", "answer"=>$clang->gT("No answer"));
                break;
            case "Y":
                $answers[]=array("code"=>"Y", "answer"=>$clang->gT("Yes"));
                $answers[]=array("code"=>"N", "answer"=>$clang->gT("No"));
                $answers[]=array("code"=>"", "answer"=>$clang->gT("No answer"));
                break;
            case "G":
                $answers[]=array("code"=>"M", "answer"=>$clang->gT("Male"));
                $answers[]=array("code"=>"F", "answer"=>$clang->gT("Female"));
                $answers[]=array("code"=>"", "answer"=>$clang->gT("No answer"));
                break;
            case "T":
            case "S":
            case "U":
                $answers[]=array("code"=>$clang->gT("Answered"), "answer"=>$clang->gT("Answered"));
                $answers[]=array("code"=>"", "answer"=>$clang->gT("No answer"));
                break;
        } // switch

        if (isset($answers)) {
            foreach($rowcodes as $row) {
                $newarray[$row]['question']=$summary[$row]['question'];
                foreach($answers as $answer) {
                    //echo "<pre>";print_r($answer);echo "</pre>";
                    if (isset($summary[$row]['summary'][$answer['answer']])) {
                        $newarray[$row]['summary'][$answer['answer']]=$summary[$row]['summary'][$answer['answer']];
                    } else {
                        $newarray[$row]['summary'][$answer['answer']]=0;
                    }
                }
            }
            $summary=$newarray;
        }
    }

    //Fix the output for multiple
    if ($thisquestion['type'] == "M" || $thisquestion['type'] == "P" || $thisquestion['type'] == "^"  || $thisquestion['type'] == "1") {
        if (isset($newarray)) {unset($newarray);}
        $newarray[$thisquestion['sid']."X".$thisquestion['gid']."X".$thisquestion['qid']]['question']=$thisquestion['question'];
        foreach ($summary as $sum) {
            preg_match("/\[(.*)\]/", $sum['question'], $regs);
            $newarray[$thisquestion['sid']."X".$thisquestion['gid']."X".$thisquestion['qid']]['summary'][$regs[1]]=$sum['summary'][$clang->gT("Yes")];
        }
        $summary=$newarray;
    }
    //	echo "<pre>";print_r($summary);echo "</pre>";
    return $summary;
}

function giveMeRawDataFromFieldNames($surveyid, $gid, $qid, $fieldlimiters=array(), $output="full") {
    //Builds output data for question $surveyid$gid$qid, limiting with $fieldlimiters array
    $questionfields = buildQuestionFields($surveyid, $qid);
    $sql = buildSqlFromFieldnamesArray($fieldlimiters);
    $tablename = "survey_".$surveyid;
    $fieldmap=createFieldMap($surveyid, "full");
    //echo "<pre>"; print_r($answers); echo "</pre>";
    list($questioninfo, $results) = returnQuestionResults($surveyid, $questionfields, $sql);
    //echo "<pre>"; print_r($questioninfo); echo "</pre>";
    if (count($results) < 1) {
        return array();
    }
    foreach ($questioninfo as $qi) {
        $questiontype=$qi['type'];
    }
    //	echo "[$questiontype]<br />";
    if ($output == "full") {
        GetBaseLanguageFromSurveyID($surveyid);
        //echo "<pre>"; print_r($answers); echo "</pre>";
        switch($questiontype) {
            case "L":
            case "!":
            case "O":
            case "D":
            case "E":
            case "M":
            case "P":
            case "C":
            case "B":
            case "A":
            case "F":
            case "H":
            case "1":
                $answers = getAnswersSingle($surveyid, $gid, $qid);
                break;
            case "Y":
                $answers[]=array("code"=>"Y", "answer"=>$clang->gT("Yes"));
                $answers[]=array("code"=>"N", "answer"=>$clang->gT("No"));
                break;
            case "G":
                $answers[]=array("code"=>"M", "answer"=>$clang->gT("Male"));
                $answers[]=array("code"=>"F", "answer"=>$clang->gT("Female"));
                break;
        } // switch
        $answers[]=array("code"=>"", "answer"=>$clang->gT("No answer"));
        $answers[]=array("code"=>"-oth-", "answer"=>$clang->gT("Other"));

        switch($questiontype) {
            case "A":
                for ($i=1; $i <= 5; $i++) {
                    $values[]=array("code"=>$i, "answer"=>$i);
                }
                break;
            case "B":
                for ($i=1; $i<=10; $i++) {
                    $values[]=array("code"=>$i, "answer"=>$i);
                }
                break;
            case "E":
                $values[]=array("code"=>"I", "answer"=>$clang->gT("Increase"));
                $values[]=array("code"=>"S", "answer"=>$clang->gT("Same"));
                $values[]=array("code"=>"D", "answer"=>$clang->gT("Decrease"));
                break;
            case "C":
            case "M":
            case "P":
                $values[]=array("code"=>"Y", "answer"=>$clang->gT("Yes"));
                $values[]=array("code"=>"U", "answer"=>$clang->gT("Uncertain"));
                $values[]=array("code"=>"N", "answer"=>$clang->gT("No"));
                break;
            case "F":
            case "H":
                $thisquestion=getQuestionInfo($qid);
                $values = getLabelSet($thisquestion['lid']);
                break;
        }
        $values[]=array("code"=>"", "answer"=>$clang->gT("No answer"));

        switch($questiontype) {
            case "L":
            case "!":
            case "O":
            case "Y":
            case "G":
            case "S":
            case "T":
            case "H":
            case "N":
            case "5":
            case "D":
                //The first key needs to be expanded
                $firstkey=array_keys($results[0]);
                $firstkey=$firstkey[0];
                $questions=arraySearchByKey($firstkey, $fieldmap, "fieldname", 1);
                //echo $firstkey;
                $i=0;
                foreach($results as $result) {
                    $results[$i][$firstkey]=array($questions['question'], arraySubstitute($result[$firstkey], $answers));
                    $i++;
                }
                break;
            case "A":
            case "B":
            case "C":
            case "E":
            case "F":
            case "H":
            case "M":
            case "P":
            case "Q":
            case "1":
                $i=0;
                foreach($results as $result) {
                    foreach($result as $key=>$val) {
                        $questions=arraySearchByKey($key, $fieldmap, "fieldname", 1);
                        if (substr($key, -7, 7) != "comment") {
                            $code=substr($key, strlen($surveyid."X".$gid."X".$qid), strlen($key)-strlen($surveyid."X".$gid."X".$qid));
                            //echo $code;
                            $results[$i][$key]=array($questions['question'], arraySubstitute($val, $values));
                        }
                    }
                    $i++;
                }
                break;
        } // switch
    }
    return $results;
}

function buildSqlFromFieldnamesArray($fieldnamesarray) {
    //Expects an array like this: "1x2x3"=>"FL"
    //and builds SQL "where" statement out of it (without the "WHERE" at the front)
    if (count($fieldnamesarray)) {
        foreach ($fieldnamesarray as $key=>$val) {
            if ($val == "{NULL}") {
                $sql[] = "$key IS NULL";
            } else {
                $sql[] = "$key = '$val'";
            }
        }
        if (count($sql) > 1) {
            return implode(" AND\n", $sql);
        } else {
            return $sql[0];
        }
    }
}

function buildQuestionFields($surveyid, $qid) {
    //Takes a specific question, and returns an array containing
    //all the possible fieldnames for responses to that question
    $fieldmap=createFieldMap($surveyid);
    foreach ($fieldmap as $fields) {
        if ($fields['sid'] == $surveyid && $fields['qid'] == $qid && $fields['aid'] != "comment") {
            $questionfields[]=$fields['fieldname'];
        }
    }
    if (!empty($questionfields)) {
        return $questionfields;
    } else {
        return array("id");
    }
}

function returnQuestionResults($surveyid, $questionfields, $sql=null) {
    global $connect;
    //Returns uninterpreted raw results from survey table for question(s)
    //$table = survcey table name (ie: "survey_1")
    //$questionfields should contain an array of the question fields that are being returned
    //$sql is any additional "filtering" sql code
    $details=array();
    $output=array();
    foreach($questionfields as $questionfield) {
        $detailsarray=arraySearchByKey($questionfield, createFieldMap($surveyid), "fieldname");
        foreach ($detailsarray as $dt) {
            $details[]=$dt;
        }
    }
    $table="survey_".$surveyid;

    if (count($questionfields) > 1) {
        $selects = "`".implode("`, `", $questionfields)."`";
    } else {
        $selects = "`".$questionfields[0]."`";
    }
    $query = "SELECT $selects
			  FROM $table";
    if (!empty($sql)) {
        $query .= "\nWHERE $sql";
    }
    $result = db_execute_assoc($query) or safe_diee("error getting results in returnQuestionResults<br />$query<br />".$connect->ErrorMsg());
    while($row=$result->FetchRow()) {
        $output[]=$row;
    } // while
    return array($details, $output);
}

function getAnswersSingle($surveyid, $gid, $qid) {
    global $dbprefix, $connect;
    $query = "SELECT *
			  FROM ".db_table_name("answers")."
			  WHERE qid=$qid
			  ORDER BY sortorder, answer";
    $result = db_execute_assoc($query);
    while($row = $result->FetchRow()) {
        $answer[]=array("code"=>$row['code'],
		"answer"=>$row['answer']);
    } // while
    return $answer;
}

function getLabelSet($lid) {
    global $dbprefix, $connect;
    $query = "SELECT *
			  FROM ".db_table_name("labels")."
			  WHERE lid=$lid
			  ORDER BY sortorder";
    $result = db_execute_assoc($query) or safe_die($connect->ErrorMsg());
    while($row = $result->FetchRow()) {
        if ($row['title'] <> '')
        {
            $answer[]=array("code"=>$row['code'],
			"answer"=>$row['title']);
        }
    } // while
    return $answer;
}

function arraySubstitute($value, $substitutes) {
    foreach ($substitutes as $sub) {
        if ($value == $sub['code']) {
            return $sub['answer'];
        }
    }
    return $value;
}
?>
