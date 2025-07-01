<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace theme_dataeu;

use context_system;
use core\hook\navigation\primary_extend;
use moodle_url;
use navigation_node;

/**
 * Hook callbacks for the DataEU theme.
 *
 * @package    theme_dataeu
 * @copyright  2024
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    /**
     * Extend the primary navigation with custom pages.
     *
     * @param primary_extend $hook Hook object containing the primary nav view.
     */
    public static function extend_primary_navigation(primary_extend $hook): void {
        $primary = $hook->get_primaryview();

        // Any default navigation item can be removed here.
        // Default items are: home, myhome, mycourses, siteadminnode.
        foreach ($primary->get_children_key_list() as $key) {
            $node = $primary->get($key);

            // Remove my courses navigation item.
            if ($key == 'mycourses') {
                $node->remove();
            }
        }
        // Add enabled category pages.
        foreach (self::get_enabled_category_pages() as $record) {
            $title = $record->name ?? $record->slug;
            $primary->add(
                    format_string($title),
                    new moodle_url('/local/course_category_page/view.php', ['slug' => $record->slug]),
                    navigation_node::TYPE_CUSTOM,
                    null,
                    'catpage_' . $record->slug
            );
        }
    }

    /**
     * Retrieve enabled course category pages for the navigation.
     *
     * @return array List of enabled records.
     */
    private static function get_enabled_category_pages(): array {
        global $DB;

        return $DB->get_records('local_course_category_page', [
                'isenabled' => 1,
                'showinprimarynavigation' => 1,
        ]) ?: [];
    }
}
