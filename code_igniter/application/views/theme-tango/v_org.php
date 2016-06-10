<?php
#  Copyright 2003-2015 Opmantek Limited (www.opmantek.com)
#
#  ALL CODE MODIFICATIONS MUST BE SENT TO CODE@OPMANTEK.COM
#
#  This file is part of Open-AudIT.
#
#  Open-AudIT is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as published
#  by the Free Software Foundation, either version 3 of the License, or
#  (at your option) any later version.
#
#  Open-AudIT is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
#
#  You should have received a copy of the GNU Affero General Public License
#  along with Open-AudIT (most likely in a file named LICENSE).
#  If not, see <http://www.gnu.org/licenses/>
#
#  For further information on Open-AudIT or for a license other than AGPL please see
#  www.opmantek.com or email contact@opmantek.com
#
# *****************************************************************************

/**
 * @author Mark Unwin <marku@opmantek.com>
 *
 * @version 1.12.6
 *
 * @copyright Copyright (c) 2014, Opmantek
 * @license http://www.gnu.org/licenses/agpl-3.0.html aGPL v3
 */
?>
<form action="#" method="post" class='niceforms'>
	<fieldset class='niceforms'>
		<legend><span style='font-size: 12pt;'>&nbsp;<?php echo __('Organisation Details')?></span></legend>
                <a href="../../admin_org/edit_org/<?php echo $org->id?>"><img style='float: right; margin; 10px; ' src='<?php echo $oa_theme_images;?>/48_edit.png' alt='' title='' width='48'/></a>
                <a href="../../admin_location/add_location/<?php echo $org->id?>"><img style='float: right; margin; 10px; ' src='<?php echo $oa_theme_images;?>/48_plus.png' alt='' title='' width='48'/></a>
               <?php if ($org->parent_id == '-1') { $org->parent_name = ''; } ?>

		<table cellpadding = "0" cellspacing="0">
			<tr>
				<td>
					<p><label for='name'><?php echo __("Name")?>: </label><span id='name' name='name'><?php echo htmlentities($org->name)?></span></p>
					<p><label for='parent'><?php echo __("Parent Org")?>: </label><span id='parent' name='parent'><a href='<?php echo intval($org->parent_id)?>'><?php echo htmlentities($org->parent_name)?></a></span></p>
					<p><label for='comments'><?php echo __("Comments")?>: </label><span id='comments' name='comments'><?php echo htmlentities($org->comments)?></span></p>
				</td>
			</tr>
		</table>
	</fieldset>
</form>

<?php
$CI=&get_instance();
$CI->load->model("m_oa_location");
$query = $CI->m_oa_location->get_org_locations($org->id);

$sortcolumn = 3;
if (count($query) > 0) {
    ?>
<table cellspacing="1" class="tablesorter">
      <thead>
              <tr>
                      <th><?php echo __('Name')?></th>
                      <th><?php echo __('Comments')?></th>
                      <th><?php echo __('Address')?></th>
                      <th align='center' width="120"><?php echo __('Networks')?></th>
              </tr>
      </thead>
      <tbody>
      <?php
            foreach ($query as $key):
            if ($key->name == '') {
                $key->name = '(none)';
            } ?>
                      <tr>
                              <td><a href="../view_location/<?php echo $key->id?>"><?php echo htmlentities($key->name)?></a></td>
                              <td><?php echo htmlentities($key->comments)?></td>
                              <td><?php echo htmlentities($key->address.', ');
                                      echo htmlentities($key->city.', ');
                                      echo htmlentities($key->state)?></td>
                              <td align='center'><?php echo $key->total?></td>
                      </tr>
                      <?php endforeach;
            ?>
      </tbody>
</table>
<?php
} else {
    echo "<br />".__('There are no locations for this organization').".<br />";
}
?>

</div>
</div>
