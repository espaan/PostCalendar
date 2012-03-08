<?php
/**
 * @package     PostCalendar
 * @copyright   Copyright (c) 2002, The PostCalendar Team
 * @copyright   Copyright (c) 2009-2012, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */
function smarty_function_pc_form_nav_open($args, Zikula_View $view)
{
    $formaction = ModUtil::url('PostCalendar', 'user', 'display');
    $formaction = DataUtil::formatForDisplay($formaction);
    $ret_val = '<form action="' . $formaction . '"' . ' method="post"' . ' enctype="application/x-www-form-urlencoded">';

    if (isset($args['assign'])) {
        $view->assign($args['assign'], $ret_val);
    } else {
        return $ret_val;
    }
}
