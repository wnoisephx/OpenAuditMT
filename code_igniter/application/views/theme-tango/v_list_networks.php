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
                        <th><?php echo __('Proxy Name')?></th>
                        <th><?php echo __('Network')?></th>
                        <th><?php echo __('Comments')?></th>
                        <th align='center' width="80" class='{sorter: false}'><?php echo __('Activate Network')?></th>
                        <th align='center' width="80" class='{sorter: false}'><?php echo __('Edit Network')?></th>
                        <th align='center' width="80" class='{sorter: false}'><?php echo __('Delete Network')?></th>
                        <th align='center' width="80" class='{sorter: false}'><?php echo __('Audit Network')?></th>
                        <th align='center' width="120"><?php echo __('Systems')?></th>
                </tr>
        </thead>
        <tbody>
        <?php
            foreach ($query as $key):
		if($key->enabled) {
                	$enable_pic = "<a href=\"../admin_network/toggle_network/".intval($key->loc_net_id)."\"><img src='".$oa_theme_images."/16_greencheck.png' alt='' title='' width='16'/></a>";
		} else {
                	$enable_pic = "<a href=\"../admin_network/toggle_network/".intval($key->loc_net_id)."\"><img src='".$oa_theme_images."/16_disabled.png' alt='' title='' width='16'/></a>";
		}

                $edit_pic = "<a href=\"../admin_network/edit_network/".intval($key->loc_net_id)."\"><img src='".$oa_theme_images."/16_edit.png' alt='' title='' width='16'/></a>";
                $delete_pic = "<a href=\"../admin_network/delete_network/".intval($key->loc_net_id)."\"><img src='".$oa_theme_images."/16_delete.png' alt='' title='' width='16'/></a>";
                $audit_pic = "<a href=\"../admin_network/audit_network/".intval($key->loc_net_id)."\"><img src='".$oa_theme_images."/16_network.png' alt='' title='' width='16'/></a>";
                 ?>
                 <tr>
                      <td><a href="../admin_proxy/edit_proxy/<?php echo $key->proxy_id?>"><?php echo htmlentities($key->proxy_name)?></a></td>
                      <td><?php echo htmlentities($key->ip_address_v4)?>/<?php echo htmlentities($key->ip_subnet)?></a></td>
                      <td><?php echo htmlentities($key->network_comments)?></td>
		      <td align='center'><?php echo $enable_pic?></td>
                      <td align='center'><?php echo $edit_pic?></td>
                      <td align='center'><?php echo $delete_pic?></td>
                      <td align='center'><?php echo $audit_pic?></td>
                      <td align='center'><?php echo $key->total?></td>
                 </tr>
                 <?php endforeach; ?>
        </tbody>
</table>
<?php
echo "<pre>";
print_r($query);
echo "</pre>";

} else {
    echo "<br />".__('There are no current networks').".<br />";
}
?>
