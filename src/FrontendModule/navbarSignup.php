<?php

/*
 * Contao CalendarExtended Bundle
 * @copyright  Copyright (c) 2018-2020, reluem
 * @author     reluem
 * @license    http://opensource.org/licenses/lgpl-3.0.html LGPL
 * @link       https://github.com/reluem/contao-calendar-extended
 */

namespace reluem\ContaoCalendarExtendedBundle\FrontendModule;

    use Contao\Module;

    class navbarSignup extends Module
    {
        /**
         * Template.
         *
         * @var string
         */
        protected $strTemplate = 'mod_navbarSignup';

        /**
         * Compile the current element.
         */
        protected function compile()
        {
            $date = strtotime(date('Y-m-d H:i:s'));
            $pageId = $GLOBALS['objPage']->id;
            $objEvent = \Database::getInstance()->prepare('SELECT pid, title, id, signupUrl, signupLabel, signupStart, signupEnd from tl_calendar_events where pid = (select id FROM tl_calendar WHERE jumpto = (Select pid from tl_page where id = ?)) and ? between signupStart and signupEnd ORDER BY id DESC')
                ->limit(1)
                ->execute($pageId, $date);

            $this->Template->navbarSignup = $objEvent->fetchAllAssoc();
        }
    }
