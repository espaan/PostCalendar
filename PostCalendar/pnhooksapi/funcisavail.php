<?php
/**
 * @package     PostCalendar
 * @author      $Author$
 * @link        $HeadURL$
 * @version     $Id$
 * @copyright   Copyright (c) 2009, Craig Heydenburg, Sound Web Development
 * @license     http://www.gnu.org/copyleft/gpl.html GNU General Public License
 */

/**
 * check to see if relevent file is available in PostCalendar/pnhooksapi/ or another location
 *
 * @author  Craig Heydenburg
 * @param   module     module being hooked
 * @param   type       function type (optional) (default 'create')
 * @return  boolean    location or false
 */
function postcalendar_hooksapi_funcisavail($args)
{
    if (!isset($args['module'])) return false;
    $homearray = array($args['module'], 'PostCalendar');
    $module    = $args['module'];
    $type      = isset($args['type']) ? $args['type'] : 'create';

    $apidir = "pnhooksapi";
    $func   = "{$type}_{$module}.php";

    foreach ($homearray as $home) {
        $osdir   = DataUtil::formatForOS($home);
        $ostype  = DataUtil::formatForOS($apidir);
        $osfunc  = DataUtil::formatForOS($func);
        $mosfile = "modules/$osdir/$ostype/$osfunc"; // doesn't allow old file format
        if (file_exists($mosfile)) {
            return $home;
        }
    }
    return false;
}