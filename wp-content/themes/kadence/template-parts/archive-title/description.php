<?php
/**
 * Template part for displaying the archive description
 *
 * @package kadence
 */

namespace Kadence;

if ( is_tax() || is_category() || is_tag() ) {
	the_archive_description( '<div class="archive-description">', '</div>' );
}
