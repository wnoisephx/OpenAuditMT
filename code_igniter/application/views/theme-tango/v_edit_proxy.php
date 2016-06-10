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

echo form_open('admin_proxy/edit_proxy')."\n";
?>
<fieldset id="proxy_details" class='niceforms'>
	<legend><span style='font-size: 12pt;'>&nbsp;<?php echo __('Proxy Details')?></span></legend>
	<a href="../delete_proxy/<?php echo $this->data['id'];?>"><img style='float: right; margin; 10px; ' src='<?php echo $oa_theme_images;?>/48_delete.png' alt='' title='' width='48'/></a>
	<?php 
	if (count($proxy)>0) {
		foreach ($proxy as $key) { ?>
     	             <table cellpadding = "0" cellspacing="0">
	 	           <tr>
			          <td>
				          <p><label for='proxy_name'><?php echo __("Name")?>: </label><input type='text' id='proxy_name' name='proxy_name' tabindex='1' title='Proxy Name' value="<?php echo $key->proxy_name; ?>"/><?php echo $error_message; ?></p>
				          <p><label for='ip_address_v4'><?php echo __("IP Address")?>: </label><input type='text' id='ip_address_v4' name='ip_address_v4' tabindex='2' title='IP Address'  value="<?php echo $key->ip_address_v4; ?>"/></p>
	                       		  <p><label for='location'><?php echo __("Location")?>: </label><select id='Location' name='location' tabindex='3' title='Location'/></p>
                      	       		  <?php foreach ($this->data['location'] as $lockey => $value) {
                                		echo "<option value='".$this->data['location'][$lockey]->location_id."'>".$this->data['location'][$lockey]->location_name."</option>";
                        		  } ?>
			          </td>
			          <td>
			          </td>
			          <td valign="top">
				          <p> <br /></p>
				          <p> <br /></p>
                                          <p><label style="width: 30px;" for='activate'></label><?php echo __("Activate")?>: <input type='checkbox' id='activate' name='activate' tabindex='18' title='Activate' <?php echo $key->activate ? 'checked' : '' ?> /></p>
				          <p><label style="width: 30px;" for='submit'></label><?php echo form_submit(array('id' => 'submit', 'name' => 'submit'), __('Submit') ); ?></p>
			          </td>
		           </tr>
	             </table>
	             <p><?php echo $this->session->flashdata('message'); ?></p>
	        <?php } ?>
	        <input type="hidden" value="<?php echo $key->proxy_id; ?>" name="proxy_id" id="proxy_id" />
                <input type="hidden" value="0" name="flag" id="flag" />
	<?php } else { ?>
                <table cellpadding = "0" cellspacing="0">
                     <tr>
                          <td>
                               <p><label for='proxy_name'><?php echo __("Name")?>: </label><input type='text' id='proxy_name' name='proxy_name' tabindex='1' title='Proxy Name' value=""/><?php echo $error_message; ?></p>
                               <p><label for='ip_address_v4'><?php echo __("IP Address")?>: </label><input type='text' id='ip_address_v4' name='ip_address_v4' tabindex='2' title='IP Address'  value=""/></p>
	                       <p><label for='location'><?php echo __("Location")?>: </label><select id='Location' name='location' tabindex='3' title='Location'/></p>
                      	       <?php foreach ($this->data['location'] as $lockey => $value) {
                                	echo "<option value='".$this->data['location'][$lockey]->location_id."'>".$this->data['location'][$lockey]->location_name."</option>";
                        	} ?>
                          </td>
                          <td>
                          </td>
                          <td valign="top">
                               <p> <br /></p>
                               <p> <br /></p>
                               <p><label style="width: 30px;" for='activate'></label><?php echo __("Activate")?>: <input type='checkbox' id='activate' name='activate' tabindex='18' title='Activate' checked /></p>
                               <p><label style="width: 30px;" for='submit'></label><?php echo form_submit(array('id' => 'submit', 'name' => 'submit'), __('Submit') ); ?></p>
                          </td>
                     </tr>
                </table>
                <p><?php echo $this->session->flashdata('message'); ?></p>
                <input type="hidden" value="<?php echo $this->data['id']; ?>" name="proxy_id" id="proxy_id" />
                <input type="hidden" value="1" name="flag" id="flag" />
        <?php } ?>

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
print_r($proxy);
echo "</pre>\n";
?>
