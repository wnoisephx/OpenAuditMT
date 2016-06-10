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
if (isset($error_message)) {
    $error_message = "<font color='red'>&nbsp;".$error_message."</font>";
} else {
    $error_message = "";
}

echo form_open('admin_network/add_network')."\n";
?>
<fieldset id="network_details" class='niceforms'>
	<legend><span style='font-size: 12pt;'>&nbsp;<?php echo __($this->data['heading'])?></span></legend>
	<img style='float: right; margin; 10px; ' src='<?php echo $oa_theme_images;?>/48_home.png' alt='' title='' width='48'/>
     	<table cellpadding = "0" cellspacing="0">
	<tr>
		<td>
			<p><label for='network_name'><?php echo __("Name")?>: </label><input type='text' id='network_name' name='network_name' tabindex='1' title='Network Name'/></p>
			<p><label for='ip_address_v4'><?php echo __("IP Address")?>: </label><input type='text' id='ip_address_v4' name='ip_address_v4' tabindex='2' title='IP Address'/></p>
			<p><label for='locations'><?php echo __("Locations")?>: </label><select id='Locations' name='locations' tabindex='3' title='Locations'/></p>
			<option value='' selected></option>
			<?php foreach ($this->data['locations'] as $key => $value) { 
				echo "<option value='".$this->data['locations'][$key]->location_id."'>".$this->data['locations'][$key]->location_name."</option>";
			} ?></select>

			<p><label for='credentials'><?php echo __("Credentials")?>: </label><select id='Credentials' name='credentials' tabindex='4' title='Credentials'/></p>
			<option value='' selected></option>
			<?php foreach ($this->data['credentials'] as $key => $value) { 
				echo "<option value='".$this->data['credentials'][$key]->credential_id."'>".$this->data['credentials'][$key]->credential_name."</option>";
			} ?></select>

		</td>
	        <td>
	        </td>
	        <td valign="top">
		        <p> <br /></p>
		        <p> <br /></p>
                        <p><label style="width: 30px;" for='activate'></label><?php echo __("Activate")?>: <input type='checkbox' id='activate' name='activate' tabindex='18' title='Activate' checked/></p>
		        <p><label style="width: 30px;" for='submit'></label><?php echo form_submit(array('id' => 'submit', 'name' => 'submit'), __('Submit') ); ?></p>
	        </td>
	</tr>
        </table>
	<p><?php echo $this->session->flashdata('message'); ?></p>
</fieldset>
<?php echo form_close(); ?>
</div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

<?php
echo "<br>\n<pre>\n";
print_r($this->data);
echo "</pre>\n";
?>
