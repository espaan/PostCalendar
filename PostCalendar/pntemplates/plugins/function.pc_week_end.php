<?php
/**
 * SVN: $Id$
 *
 * @package     PostCalendar
 * @author      $Author$
 * @link        $HeadURL$
 * @version     $Revision$
 *
 * PostCalendar::Zikula Events Calendar Module
 * Copyright (C) 2002  The PostCalendar Team
 * http://postcalendar.tv
 * Copyright (C) 2009  Sound Web Development
 * Craig Heydenburg
 * http://code.zikula.org/soundwebdevelopment/
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * To read the license please read the docs/license.txt or visit
 * http://www.gnu.org/copyleft/gpl.html
 *
 */

/**
 * Get last day of the week
 *
 * @param array $args array with arguments.
 *                    $args['date'] date to use for range building
 *                    $args['sep'] seperate the dates by this string
 *                    $args['format'] format all dates like this
 *                    $args['format1'] format date 1 like this
 *                    $args['format2'] format date 2 like this
 * @param Smarty $smarty the Smarty instance
 * @return unknown
 */
function smarty_function_pc_week_end($args, &$smarty)
{
    setlocale(LC_TIME, _PC_LOCALE);
    if (!isset($args['date'])) {
        //not sure these three lines are needed with call to getDate here
        $jumpday   = FormUtil::getPassedValue('jumpday');
        $jumpmonth = FormUtil::getPassedValue('jumpmonth');
        $jumpyear  = FormUtil::getPassedValue('jumpyear');
        $Date      = FormUtil::getPassedValue('Date');
        $args['date']      = pnModAPIFunc('PostCalendar','user','getDate',compact('Date','jumpday','jumpmonth','jumpyear'));
    }

    if (!isset($args['sep'])) $args['sep'] = ' - ';

    if (!isset($args['format'])) {
        if (!isset($args['format1'])) $args['format1'] = _SETTING_DATE_FORMAT;
        if (!isset($args['format2'])) $args['format2'] = _SETTING_DATE_FORMAT;
    } else {
        $args['format1'] = $args['format'];
        $args['format2'] = $args['format'];
    }

    $y = substr($args['date'], 0, 4);
    $m = substr($args['date'], 4, 2);
    $d = substr($args['date'], 6, 2);

    // get the week date range for the supplied $date
    $dow = date('w', mktime(0, 0, 0, $m, $d, $y));
    if (_SETTING_FIRST_DAY_WEEK == 0) {
        // $firstDay = strftime('%Y-%m-%d', mktime(0, 0, 0, $m, ($d - $dow), $y));
        $lastDay = strftime('%Y-%m-%d', mktime(0, 0, 0, $m, ($d + (6 - $dow)), $y));
    } elseif (_SETTING_FIRST_DAY_WEEK == 1) {
        $sub = ($dow == 0 ? 6 : $dow - 1);
        // $firstDay = strftime('%Y-%m-%d', mktime(0, 0, 0, $m, ($d - $sub), $y));
        $lastDay = strftime('%Y-%m-%d', mktime(0, 0, 0, $m, ($d + (6 - $sub)), $y));
    } elseif (_SETTING_FIRST_DAY_WEEK == 6) {
        $sub = ($dow == 6 ? 0 : $dow + 1);
        // $firstDay = strftime('%Y-%m-%d', mktime(0, 0, 0, $m, ($d - $sub), $y));
        $lastDay = strftime('%y-%m-%d', mktime(0, 0, 0, $m, ($d + (6 - $sub)), $y));
    }

    // return the formated range
    //echo $lastDay;

    if (isset($args['assign'])) {
        $smarty->assign($args['assign'], $lastDay);
        return;
    } else {
        return $lastDay;
    }
}
