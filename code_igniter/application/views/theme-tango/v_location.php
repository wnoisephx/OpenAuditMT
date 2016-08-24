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
	<fieldset id="org_details" class='niceforms'>
		<legend><span style='font-size: 12pt;'>&nbsp;<?php echo __('Location Details')?></span></legend>
		<div><table style='float: left;'>
			<tr>
				<td valign="top">
					<p><label for='name'><?php echo __("Name")?>: </label><span id='name' name='name'>&nbsp;<?php echo htmlentities($location->name)?></span></p>
                                        <p><label for='org_name'><?php echo __("Organization")?>: </label><span id='org_name' name='org_name'>&nbsp;<?php echo htmlentities("blah")?></span></p>
                                        <p><label for='location_networks'># <?php echo __("networks")?>: </label><span id='location_devices' name='location_devices'>&nbsp;<?php echo htmlentities($location->total)?></span></p>
					<p><label for='type'><?php echo __("Type")?>: </label><span id='type' name='type'>&nbsp;<?php echo htmlentities($location->type)?></span></p>
					<p><label for='room'><?php echo __("Room")?>: </label><span id='room' name='room'>&nbsp;<?php echo htmlentities($location->room)?></span></p>
					<p><label for='suite'><?php echo __("Suite")?>: </label><span id='suite' name='suite'>&nbsp;<?php echo htmlentities($location->suite)?></span></p>
					<p><label for='level'><?php echo __("Level")?>: </label><span id='level' name='level'>&nbsp;<?php echo htmlentities($location->level)?></span></p>
					<p><label for='address'><?php echo __("Street Address")?>: </label><span id='address' name='address'>&nbsp;<?php echo htmlentities($location->address)?></span></p>
				</td valign="top"><td>
					<p><label for='city'><?php echo __("City")?>: </label><span id='city' name='city'>&nbsp;<?php echo htmlentities($location->city)?></span></p>
					<p><label for='state'><?php echo __("State")?>: </label><span id='state' name='state'>&nbsp;<?php echo htmlentities($location->state)?></span></p>
					<p><label for='country'><?php echo __("Country")?>: </label><span id='country' name='country'>&nbsp;<?php echo htmlentities($location->country)?></span></p>
					<p><label for='phone'><?php echo __("Phone")?>: </label><span id='phone' name='phone'>&nbsp;<?php echo htmlentities($location->phone)?></span></p>
					<p><label for='geo'><?php echo __("Geocode")?>: </label><span id='geo' name='geo'>&nbsp;<?php echo htmlentities($location->geo)?></span></p>
					<p><label for='latitude'><?php echo __("Latitude")?>: </label><span id='latitude' name='latitude'>&nbsp;<?php echo htmlentities($location->latitude)?></span></p>
					<p><label for='longitude'><?php echo __("Longitude")?>: </label><span id='longitude' name='longitude'>&nbsp;<?php echo htmlentities($location->longitude)?></span></p>
					<p><label for='comments'><?php echo __("Comments")?>: </label><span id='comments' name='comments'>&nbsp;<?php echo htmlentities($location->comments)?></span></p>
				</td>
			</tr>
		</table>

		<table style='float: right;'>
			<tr><td>
				<a href="../../admin_location/edit_location/<?php echo $location->id?>"><img style='float: right; margin; 10px; ' src='<?php echo $oa_theme_images;?>/48_edit.png' alt='Edit Location' title='Edit Location' width='48'/></a>
			</td></tr>
			<tr><td>
                		<a href="../../admin_location/delete_location/<?php echo $location->id?>" onclick="return confirm('Ok to Delete?')"><img style='float: right; margin; 10px; ' src='<?php echo $oa_theme_images;?>/48_delete_page.png' alt='Delete Location' title='Delete Location' width='48'/></a>
			</td></tr>
			<tr><td>
		                <a href="../../admin_network/add_network/<?php echo $location->id?>"><img style='float: right; margin; 10px; ' src='<?php echo $oa_theme_images;?>/48_add_page.png' alt='Add Network' title='Add Network' width='48'/></a>
			</td></tr>
		 </tr></table></div>

	</fieldset>
</form>

<?php
$CI=&get_instance();
$CI->load->model("m_oa_network");
$query = $CI->m_oa_network->get_location_networks($location->id);

$sortcolumn = 1;
if (count($query) > 0) {
    ?>
<table cellspacing="1" class="tablesorter">
        <thead>
                <tr>
                        <th align='center' width="120"><?php echo __('Systems')?></th>
                        <th><?php echo __('Network')?></th>
                        <th><?php echo __('Comments')?></th>
                        <th align='center' width="80" class='{sorter: false}'><?php echo __('Edit Network')?></th>
                        <th align='center' width="80" class='{sorter: false}'><?php echo __('Delete Network')?></th>
                        <th align='center' width="80" class='{sorter: false}'><?php echo __('Audit Network')?></th>
                </tr>
        </thead>
        <tbody>
        <?php
            foreach ($query as $key):
                $edit_pic = "<a href=\"../../admin_network/edit_network/".intval($key->location_id)."\"><img src='".$oa_theme_images."/16_edit.png' alt='' title='' width='16'/></a>";
                $delete_pic = "<a href=\"../../admin_network/delete_network/".intval($key->location_id)."\"><img src='".$oa_theme_images."/16_delete.png' alt='' title='' width='16'/></a>";
                $audit_pic = "<a href=\"../../admin_network/audit_network/".intval($key->location_id)."\"><img src='".$oa_theme_images."/16_network.png' alt='' title='' width='16'/></a>";
                 ?>
                 <tr>
                      <td align='center'><?php echo $key->total?></td>
                      <td><a href="../list_devices/<?php echo $key->location_group_id?>"><?php echo htmlentities($key->ip_address_v4)."/".htmlentities($key->ip_subnet)?></a></td>
                      <td><?php echo htmlentities($key->network_comments)?></td>
                      <td align='center'><?php echo $edit_pic?></td>
                      <td align='center'><?php echo $delete_pic?></td>
                      <td align='center'><?php echo $audit_pic?></td>
                 </tr>
                 <?php endforeach; ?>
        </tbody>
</table>
<?php
} else {
    echo "<br />".__('There are no networks for this location').".<br />";
}
?>

</div>
</div>
