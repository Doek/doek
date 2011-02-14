<?php
// $Id: advanced-forum.naked.search-forum.tpl.php,v 1.1.2.1 2011/01/05 16:02:32 michellec Exp $
/**
 * @file
 * Display the search forum widget.
 *
 * The real widget is part of a view, but this widget actually leads to the
 * view, and can be redone. It does not need to use FAPI because it
 * is just a simple get form, which allows us to style it however we
 * like as long as it has the two important keys: forum and keys.
 *
 * Variables:
 * - $forum: The forum ID. Will be 'All' for no particular forum.
 * - $path: The path to the search widget for the form action.
 */
?>
<form action="<?php print $path ?>" accept-charset="UTF-8" method="get" id="advanced-forum-search-forum">
<input type="hidden" name="forum" id="edit-forum" value="<?php print $forum; ?>"/>
<div class="container-inline">
  <div class="form-item" id="edit-keys-wrapper">
    <input type="text" maxlength="128" name="keys" id="edit-keys" value="" title="<?php print t('Enter the terms you wish to search for.'); ?>" class="form-text" />
  </div>
  <input type="submit" id="edit-submit-forum-search" value="<?php print t('Search forum'); ?>"  class="form-submit" />
</div>
</form>

