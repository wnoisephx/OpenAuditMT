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
 * @version 1.8
 *
 * @copyright Copyright (c) 2014, Opmantek
 * @license http://www.gnu.org/licenses/agpl-3.0.html aGPL v3
 */
$sortcolumn = 2;
if (count($query) > 0) {
    ?>
<table cellspacing="1" class="tablesorter">
	<thead>
		<tr>
			<th align='center' width="80"><?php echo __('Networks')?></th>
			<th><?php echo __('Name')?></th>
			<th><?php echo __('IP Address')?></th>
			<th align='center' width="80" class='{sorter: false}'><?php echo __('Activate Proxy')?></th>
			<th align='center' width="80" class='{sorter: false}'><?php echo __('Edit Proxy')?></th>
			<th align='center' width="80" class='{sorter: false}'><?php echo __('Delete Proxy')?></th>
		</tr>
	</thead>
	<tbody>
		<?php
        if (count($query) > 0) {
            foreach ($query as $key):

                $edit_pic = "<a href=\"edit_proxy/".intval($key->proxy_id)."\"><img src='".$oa_theme_images."/16_edit.png' alt='' title='' width='16'/></a>";
                $delete_pic = "<a href=\"delete_proxy/".intval($key->proxy_id)."\"><img src='".$oa_theme_images."/16_delete.png' alt='' title='' width='16'/></a>";
                $activate_pic = "<a href=\"activate_group/".intval($key->proxy_id)."\"><img src='".$oa_theme_images."/16_true.png' alt='' title='' width='16'/></a>";

                if ($key->proxy_name == '') {
                    $key->proxy_name = '(none)';
                }
            ?>
			<tr>
				<td><a href="../admin_network/list_networks_by_proxy_id/<?php echo intval($key->proxy_id)?>"><?php echo $key->total ?></a></td>
				<td><?php echo htmlentities($key->proxy_name)?></td>
				<td><?php echo htmlentities($key->ip_address_v4)?></td>
				<td align='center'><?php echo $activate_pic?></td>
				<td align='center'><?php echo $edit_pic?></td>
				<td align='center'><?php echo $delete_pic?></td>
			</tr>
			<?php endforeach;
            ?>
		<?php 
        } else {
            ?>
		<tr>
			<td>&nbsp;</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<?php 
        }
    ?>
	</tbody>
</table>
<?php
echo "<pre>";
print_r($query);
echo "</pre>";

} else {
    echo "<br />".__('There are no current proxies').".<br />";
}
?>
