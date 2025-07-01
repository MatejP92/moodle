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

/**
 * Custom renderer to filter out specific profile categories on My profile.
 *
 * @package    theme_dataeu
 * @copyright  2025 Agiledrop
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace theme_dataeu\output\core_user\myprofile;
defined('MOODLE_INTERNAL') || die();

use core_user\output\myprofile\category as category;

/**
 * Renderer for the My Profile page in the DataEU theme.
 *
 * Extends the core profile renderer to allow filtering out entire
 * category “cards” by comparing their titles against a configurable list.
 *
 * @package    theme_dataeu
 * @subpackage core_user_myprofile
 */
class renderer extends \core_user\output\myprofile\renderer {
    /**
     * Renders one profile‐page category (i.e. “card”).
     *
     * If the category’s title (stripped of all HTML) matches any of
     * those returned by {@see get_skipped_category_titles()}, this method
     * returns an empty string, effectively removing it from the page.
     *
     * @param category $category The profile category to render.
     * @return string The rendered category HTML, or empty if skipped.
     */
    public function render_category(category $category) {
        $titletext = trim(strip_tags($category->title));
        $skipped = $this->get_skipped_category_titles();

        if (in_array($titletext, $skipped, true)) {
            return '';
        }

        return parent::render_category($category);
    }

    /**
     * Returns a list of profile‐category titles
     * that should be hidden.
     *
     * By default this includes “Miscellaneous”. Add or remove entries
     * here as your needs change.
     *
     * @return string[]
     */
    protected function get_skipped_category_titles(): array {
        return [
            'Miscellaneous',
            'Course details',
            // To hide more sections, add their titles here:
            // 'Another section title',
        ];
    }
}
