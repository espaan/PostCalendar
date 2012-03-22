<?php

/**
 * PostCalendar
 * 
 * @license MIT
 * @copyright   Copyright (c) 2012, Craig Heydenburg, Sound Web Development
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */
class PostCalendar_CalendarView_List extends PostCalendar_CalendarView_AbstractDays
{
    protected $listMonths = 1;

    protected function setCacheTag()
    {
        $this->cacheTag = $this->requestedDate->format('Ymd');
    }

    protected function setTemplate()
    {
        $this->template = 'user/list.tpl';
    }
    
    protected function setDates()
    {
        $this->startDate = clone $this->requestedDate;
        $this->endDate = clone $this->requestedDate;
        $this->endDate
             ->modify("+" . $this->listMonths . " months");  

        $interval = new DateInterval("P1D");
        $datePeriod = new DatePeriod($this->startDate, $interval, $this->endDate);
        $i = 0;
        $week = 0;
        foreach ($datePeriod as $date) {
            $this->dateGraph[$week][$i] = $date->format('Y-m-d');
            $i++;
            if ($i > 6) {
                $i = 0;
                $week++;
            }
        }
    }

    protected function setup()
    {
        $this->viewtype = 'list';
        $this->listMonths = ModUtil::getVar('PostCalendar', 'pcListMonths');

        $prevClone = clone $this->requestedDate;
        $prevClone->modify("-" . $this->listMonths . " months");
        $this->navigation['previous'] = ModUtil::url('PostCalendar', 'user', 'display', array(
                    'viewtype' => $this->viewtype,
                    'date' => $prevClone->format('Ymd'),
                    'pc_username' => $this->userFilter,
                    'filtercats' => $this->categoryFilter));
        $nextClone = clone $this->requestedDate;
        $nextClone->modify("+" . $this->listMonths . " months")
                  ->modify("+1 day");
        $this->navigation['next'] = ModUtil::url('PostCalendar', 'user', 'display', array(
                    'viewtype' => $this->viewtype,
                    'date' => $nextClone->format('Ymd'),
                    'pc_username' => $this->userFilter,
                    'filtercats' => $this->categoryFilter));
    }

    public function render()
    {
        if (!$this->isCached()) {
            // Load the events
            $eventsByDate = ModUtil::apiFunc('PostCalendar', 'event', 'getEvents', array(
                'start'       => $this->startDate,
                'end'         => $this->endDate,
                'filtercats'  => $this->categoryFilter,
                'date'        => $this->requestedDate,
                'pc_username' => $this->userFilter));
            // create and return template
            $this->view
                    ->assign('navigation', $this->navigation)
                    ->assign('dayDisplay', $this->dayDisplay)
                    ->assign('graph', $this->dateGraph)
                    ->assign('eventsByDate', $eventsByDate)
                    ->assign('selectedcategories', $this->selectedCategories)
                    ->assign('func', $this->view->getRequest()->query->get('func', $this->view->getRequest()->request->get('func', 'display')))
                    ->assign('viewtypeselected', $this->viewtype)
                    ->assign('todayDate', date('Y-m-d'))
                    ->assign('requestedDate', $this->requestedDate->format('Y-m-d'))
                    ->assign('startDate', $this->startDate->format('Y-m-d'))
                    ->assign('endDate', $this->endDate->format('Y-m-d'));
            // be sure to DataUtil::formatForDisplay in the template - navigation and others?
        }
        return $this->view->fetch($this->template);
    }

}