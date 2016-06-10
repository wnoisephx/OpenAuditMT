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
class M_oa_network extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all networks for a given location
     *
     * @access public
     *
     * @param  loction_id
     *
     * @return string
     */
     public function get_location_networks($id)
     {
        $sql = "SELECT oa_network.*, count(oa_group_sys.system_id) as total FROM oa_network LEFT JOIN oa_group_sys ON oa_group_sys.group_id = oa_network.location_group_id where oa_network.location_id = ? GROUP BY oa_network.ip_address_v4 ORDER BY oa_network.ip_address_v4";
        $data = array($id);
        $sql = $this->clean_sql($sql);
        $query = $this->db->query($sql,$data);
        $result = $query->result();

        return ($result);
     }

     public function toggle_network($id)
     {
	$sql = "SELECT * FROM oa_network WHERE loc_net_id = ? limit 1";
	$data = array($id);
	$query = $this->db->query($sql,$data);
	$result = $query->result();

echo "result1 = $result<br>";
	// toggle the enable flag
	$result->enabled = !$result->enabled;
echo "result2 = $result->enabled<br>";

	// update the record
        $sql = "UPDATE oa_network SET enabled = ? WHERE loc_net_id = ? ";
        $data = array($result->enabled, $id);
	$query = $this->db->query($sql,$data);
die();
     }

//    /**
//     * Get the network details from the id.
//     *
//     * @access	public
//     *
//     * @param	location_id
//     *
//     * @return string
//     */
//    public function get_network($id)
//    {
//        $sql = "SELECT * FROM oa_location WHERE location_id = ? LIMIT 1";
//        $data = array($id);
//        $query = $this->db->query($sql, $data);
//        $result = $query->result();
//
//        return ($result);
//    }

//    /**
//     * Get the network's group id.
//     *
//     * @access	public
//     *
//     * @param	networok_id
//     *
//     * @return string
//     */
//    public function get_group_id($network_id)
//    {
//        $sql = "SELECT location_group_id FROM oa_location WHERE location_id = ? LIMIT 1";
//        $data = array("$location_id");
//        $query = $this->db->query($sql, $data);
//        $row = $query->row();
//
//        return ($row->location_group_id);
//    }

//    /**
//     * Set the network's group id.
//     *
//     * @access	public
//     *
//     * @param	network_id
//     *
//     * @return string
//     */
//    public function set_group_id($network_id, $group_id)
//    {
//        $sql = "UPDATE oa_location SET location_group_id = ? WHERE location_id = ? ";
//        $data = array("$group_id", "$location_id");
//        $query = $this->db->query($sql, $data);
//    }

//    /**
//     * Get the location's details, including the number of devices in the corresponding group.
//     *
//     * @access	public
//     *
//     * @param	network_id
//     *
//     * @return string
//     */
//    public function get_network_details($org_id)
//    {
//        $sql = "SELECT oa_location.*, count(oa_network.location_id) as total FROM oa_location LEFT JOIN oa_network ON oa_network.location_id = oa_location.location_id where oa_location.location_id = ? GROUP BY oa_location.location_id LIMIT 1";
//        $data = array("$org_id");
//        $query = $this->db->query($sql, $data);
//        $row = $query->row();
//
//        return ($row);
//    }

//    /**
//     * Get the network id from the name.
//     *
//     * @access	public
//     *
//     * @param	name of the network
//     *
//     * @return string
//     */
//    public function get_network_id($name)
//    {
//        $sql = "SELECT location_id FROM oa_location WHERE location_name = ? LIMIT 1";
//        $data = array("$name");
//        $query = $this->db->query($sql, $data);
//        $row = $query->row();
//        if ($query->num_rows() > 0) {
//            $row = $query->row();
//
//            return ($row->location_id);
//        } else {
//            return;
//        }
//    }

//    /**
//     * Get the location name from the id.
//     *
//     * @access	public
//     *
//     * @param	id of the location
//     *
//     * @return string
//     */
//    public function get_location_name($id)
//    {
//        $sql = "SELECT location_name FROM oa_location WHERE location_id = ? LIMIT 1";
//        $data = array($id);
//        $query = $this->db->query($sql, $data);
//        $row = $query->row();
//
//        return ($row->location_name);
//    }
//
//    /**
//    * Get all the locations names.
//     *
//     * @access	public
//    *
//     * @return array
//     */
//    public function get_location_names()
//    {
//        $sql = "SELECT location_name, location_id FROM oa_location ORDER BY location_name";
//        $query = $this->db->query($sql);
//        $result = $query->result();
//
//        return ($result);
//    }
//
//    /**
//     * Get all the locations details.
//     *
//     * @access	public
//     *
//     * @return array
//     */
    public function get_all_networks()
    {
        $sql = "SELECT oa_network.*, count(oa_group_sys.system_id) as total, oa_proxy.proxy_name as proxy_name FROM oa_network LEFT JOIN oa_group_sys ON oa_group_sys.group_id = oa_network.location_group_id left join oa_proxy on oa_proxy.proxy_id = oa_network.proxy_id GROUP BY oa_network.ip_address_v4 ORDER BY oa_network.ip_address_v4";
        $sql = $this->clean_sql($sql);
        $query = $this->db->query($sql);
        $result = $query->result();

        return ($result);
    }

    public function get_networks_by_proxy_id($proxy_id)
    {
        $sql = "SELECT oa_network.*, count(oa_group_sys.system_id) as total, oa_proxy.proxy_name as proxy_name FROM oa_network LEFT JOIN oa_group_sys ON oa_group_sys.group_id = oa_network.location_group_id left join oa_proxy on oa_proxy.proxy_id = oa_network.proxy_id WHERE oa_network.proxy_id = ? GROUP BY oa_network.ip_address_v4 ORDER BY oa_network.ip_address_v4";
	$data = array($proxy_id);
        $sql = $this->clean_sql($sql);
        $query = $this->db->query($sql,$data);
        $result = $query->result();

        return ($result);
    }
//
//   /**
//     * Get all locations for a given org.
//     *
//     * @access	public
//     *
//     * @return array
//     */
//    public function get_org_locations($org_id)
//    {
//        $sql = "SELECT oa_location.*, count(oa_network.location_id) as total FROM oa_location LEFT JOIN oa_network ON oa_network.location_id = oa_location.location_id where location_org_id = ? GROUP BY oa_location.location_id ORDER BY oa_location.location_name";
//        $data = array($org_id);
//        $sql = $this->clean_sql($sql);
//        $query = $this->db->query($sql,$data);
//        $result = $query->result();
//
//        return ($result);
//    }
//
//    /**
//     * Check the location name to see if it exists, not including the supplied id.
//     *
//     * @access	public
//     *
//     * @param	location_name the name of the location
//     * @param	location_id the ID of the location
//     *
//     * @return boolean
//     */
//    public function check_location_name($location_name, $location_id)
//    {
//        $sql = "SELECT location_id FROM oa_location WHERE location_name = ? AND location_id <> ?";
//        $data = array($location_name, $location_id);
//        $query = $this->db->query($sql, $data);
//        $row = $query->row();
//        if ($query->num_rows() > 0) {
//            return false;
//        } else {
//            return true;
//        }
//    }
//
//    /**
//     * Get the location name of a supplied system.
//     *
//     * @access	public
//     *
//     * @param	system_id the ID of the system
//     *
//     * @return string
//     */
//    public function get_system_location($id)
//    {
//        $sql = "SELECT
//				oa_location.*
//			FROM
//				oa_location,
//				system
//			WHERE
//				oa_location.location_id = system.man_location_id AND
//				system.system_id = ?
//			LIMIT 1";
//       $sql = $this->clean_sql($sql);
//       $data = array($id);
//        $query = $this->db->query($sql, $data);
//        $result = $query->result();
//
//        return ($result);
//    }
//
//    /**
//     * Add a location to the database - Admin only.
//     *
//     * @access	public
//     *
//     * @param	details - the relevant location details object
//     *
//     * @return string
//     */
//    public function add_location($details)
//    {
//        # need to insert suburb, district, region, area, tags, picture
//        $sql = "INSERT INTO oa_location
//					(location_name,
//					location_type,
//					location_room,
//					location_suite,
//					location_level,
//					location_address,
//					location_postcode,
//
//   /**
//     * Get all locations for a given org.
//     *
//     * @access	public
//     *
//     * @return array
//     */
//    public function get_org_locations($org_id)
//    {
//        $sql = "SELECT oa_location.*, count(oa_network.location_id) as total FROM oa_location LEFT JOIN oa_network ON oa_network.location_id = oa_location.location_id where location_org_id = ? GROUP BY oa_location.location_id ORDER BY oa_location.location_name";
//        $data = array($org_id);
//        $sql = $this->clean_sql($sql);
//        $query = $this->db->query($sql,$data);
//        $result = $query->result();
//
//        return ($result);
//    }
//
//    /**
//     * Check the location name to see if it exists, not including the supplied id.
//     *
//     * @access	public
//     *
//     * @param	location_name the name of the location
//     * @param	location_id the ID of the location
//     *
//     * @return boolean
//     */
//    public function check_location_name($location_name, $location_id)
//    {
//        $sql = "SELECT location_id FROM oa_location WHERE location_name = ? AND location_id <> ?";
//        $data = array($location_name, $location_id);
//        $query = $this->db->query($sql, $data);
//        $row = $query->row();
//        if ($query->num_rows() > 0) {
//            return false;
//        } else {
//            return true;
//        }
//    }
//
//    /**
//     * Get the location name of a supplied system.
//     *
//     * @access	public
//     *
//     * @param	system_id the ID of the system
//     *
//     * @return string
//     */
//    public function get_system_location($id)
//    {
//        $sql = "SELECT
//				oa_location.*
//			FROM
//				oa_location,
//				system
//			WHERE
//				oa_location.location_id = system.man_location_id AND
//				system.system_id = ?
//			LIMIT 1";
//       $sql = $this->clean_sql($sql);
//       $data = array($id);
//        $query = $this->db->query($sql, $data);
//        $result = $query->result();
//
//        return ($result);
//    }
//
//    /**
//     * Add a location to the database - Admin only.
//     *
//     * @access	public
//     *
//     * @param	details - the relevant location details object
//     *
//     * @return string
//     */
//    public function add_location($details)
//    {
//        # need to insert suburb, district, region, area, tags, picture
//        $sql = "INSERT INTO oa_location
//					(location_name,
//					location_type,
//					location_room,
//					location_suite,
//					location_level,
//					location_address,
//					location_postcode,
//					location_city,
//					location_state,
//					location_country,
//					location_geo,
//					location_latitude,
//					location_longitude)
//				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
//       $sql = $this->clean_sql($sql);
//        $data = array("$details->location_name",
//            "$details->location_type",
//            "$details->location_room",
//            "$details->location_suite",
//            "$details->location_level",
//            "$details->location_address",
//            "$details->location_postcode",
//            "$details->location_city",
//            "$details->location_state",
//            "$details->location_country",
//            "$details->location_geo",
//            "$details->location_latitude",
//            "$details->location_longitude", );
//        $query = $this->db->query($sql, $data);
//
//        return($this->db->insert_id());
//    }
//
//    /**
//     * Delete a location from the database - Admin only.
//     *
//     * @access	public
//     *
//     * @param	detail id - the id of the location to delete
//     *
//     * @return nothing
//     */
//    public function delete_location($location_id)
//    {
//        $sql = "DELETE FROM oa_location WHERE location_id = ?";
//        $data = array("$location_id");
//        $query = $this->db->query($sql, $data);
//        $sql = "UPDATE system SET man_location_id = '' WHERE man_location_id = ?";
//        $data = array("$location_id");
//        $query = $this->db->query($sql, $data);
//    }
//
//    /**
//     * Update a location in the database - Admin only.
//     *
//     * @access	public
//     *
//     * @param	details - the relevant location details object
//     *
//     * @return TRUE
//     */
//    public function edit_location($details)
//    {
//        # need to insert suburb, district, region, area, tags, picture
//        $sql = "UPDATE oa_location SET
//					location_name = ?,
//					location_type = ?,
//					location_room = ?,
//					location_suite = ?,
//					location_level = ?,
//					location_address = ?,
//					location_city = ?,
//					location_postcode = ?,
//					location_state = ?,
//					location_country = ?,
//					location_geo = ?,
//					location_latitude = ?,
//					location_longitude = ?
//				WHERE
//					location_id = ?";
//       $sql = $this->clean_sql($sql);
//      $data = array("$details->location_name",
//            "$details->location_type",
//            "$details->location_room",
//            "$details->location_suite",
//            "$details->location_level",
//            "$details->location_address",
//            "$details->location_city",
//            "$details->location_postcode",
//            "$details->location_state",
//            "$details->location_country",
//            "$details->location_geo",
//            "$details->location_latitude",
//            "$details->location_longitude",
//            "$details->location_id", );
//        $query = $this->db->query($sql, $data);
//
//        return(true);
//    }
//
//   /**
//     * List all devices in a given location.
//     *
//     * @access	public
//     *
//     * @param	location_id
//     *
//     * @return array
//     */
//    public function list_devices_in_location($location_id = 0, $user_id = 0)
//    {
//        // we have not requested a specific group.
//        // display all items the current user has at least 'level 3' - view list rights on.
//        $sql = "
//			SELECT
//				system.system_id,
//				system.hostname,
//				system.man_description,
//				system.man_ip_address,
//				system.man_icon,
//				system.man_os_name,
//				system.man_os_family
//			FROM
//				system,
//				oa_group,
//				oa_group_sys,
//				oa_group_user
//			WHERE
//				system.system_id IN (
//					SELECT
//						system.system_id
//					FROM
//						system,
//						oa_group_sys,
//						oa_group,
//						oa_group_user
//					WHERE
//						system.man_status = 'production' AND
//						system.system_id = oa_group_sys.system_id AND
//						oa_group_sys.group_id = oa_group.group_id AND
//						oa_group.group_id = oa_group_user.group_id AND
//						oa_group_user.user_id = ?
//						) AND
//				system.system_id = oa_group_sys.system_id AND
//				oa_group_sys.group_id = oa_group.group_id AND
//				oa_group.group_id = oa_group_user.group_id AND
//				oa_group_user.group_user_access_level > '2' AND
//				oa_group_user.user_id = ? AND
//				system.man_location_id = ?
//			GROUP BY system.system_id ";
//       $sql = $this->clean_sql($sql);
//        $data = array("$user_id", "$user_id", "$location_id");
//        $query = $this->db->query($sql, $data);
//        $result = $query->result();
//
//        return ($result);
//    }
//
//    public function location_report_columns()
//    {
//        $i = new stdclass();
//        $result = array();
//        $i->column_order = '0';
//        $i->column_name = 'Name';
//        $i->column_variable = 'name';
//        $i->column_type = "link";
//        $i->column_align = "left";
//        $i->column_secondary = "id";
//        $i->column_ternary = "";
//        $i->column_link = "/main/view_location/";
//        $result[0] = $i;
//        $i = new stdclass();
//        $i->column_order = '1';
//        $i->column_name = 'Address';
//        $i->column_variable = 'address';
//        $i->column_type = "text";
//        $i->column_align = "left";
//        $i->column_secondary = "";
//        $i->column_ternary = "";
//        $i->column_link = "";
//        $result[1] = $i;
//        $i = new stdclass();
//        $i->column_order = '2';
//        $i->column_name = 'GeoTag';
//        $i->column_variable = 'geo';
//        $i->column_type = "text";
//        $i->column_align = "left";
//        $i->column_secondary = "";
//        $i->column_ternary = "";
//        $i->column_link = "";
//        $result[2] = $i;
//        $i = new stdclass();
//        $i->column_order = '3';
//        $i->column_name = 'Icon';
//        $i->column_variable = 'type';
//        $i->column_type = "image";
//        $i->column_align = "center";
//        $i->column_secondary = "type";
//        $i->column_ternary = "";
//        $i->column_link = "";
//        $result[3] = $i;
//        $i = new stdclass();
//        $i->column_order = '4';
//        $i->column_name = 'Type';
//        $i->column_variable = 'type';
//        $i->column_type = "text";
//        $i->column_align = "left";
//        $i->column_secondary = "";
//        $i->column_ternary = "";
//        $i->column_link = "";
//        $result[4] = $i;
//        $i = new stdclass();
//        $i->column_order = '5';
//        $i->column_name = 'GroupId';
//        $i->column_variable = 'group';
//        $i->column_type = "text";
//        $i->column_align = "left";
//        $i->column_secondary = "";
//        $i->column_ternary = "";
//        $i->column_link = "";
//        $result[5] = $i;
//        $i = new stdclass();
//
//        $count = 5;
//        $sql = "SELECT DISTINCT(man_type) FROM system";
//        $query = $this->db->query($sql);
//        $types = $query->result();
//        foreach ($types as $type) {
//            $i = new stdclass();
//            $count++;
//            if ($type->man_type == '') {
//                $type->man_type = 'unknown';
//            }
//            $i->column_order = $count;
//            $i->column_name = $type->man_type;
//            $i->column_variable = $type->man_type;
//            $i->column_type = "text";
//            $i->column_align = "left";
//            $i->column_secondary = "";
//            $i->column_ternary = "";
//            $i->column_link = "";
//            $result[] = $i;
//        }
//
//        return ($result);
//    }
//
//    public function location_report()
//    {
//       $sql = "SELECT DISTINCT(man_type) FROM system";
//        $query = $this->db->query($sql);
//       $types = $query->result();
//
//        $sql = "SELECT oa_location.location_id, oa_location.location_name, oa_location.location_icon, oa_location.location_type,
//		oa_location.location_address, oa_location.location_city, oa_location.location_state, oa_location.location_group_id,
//		oa_location.location_postcode, oa_location.location_country, oa_location.location_geo,
//		system.man_type, count(system.system_id) as count, oa_location.location_latitude, oa_location.location_longitude
//		FROM system LEFT JOIN oa_location ON system.man_location_id = oa_location.location_id
//		WHERE system.man_status = 'production' and
//		oa_location.location_latitude != '' and
//		oa_location.location_longitude != ''
//		GROUP BY system.man_location_id, system.man_type
//		ORDER BY location_name";
//        $query = $this->db->query($sql);
//        $result = $query->result();
//        $table = array();
//        $current_location = "";
//        $count = -1;
//
//       if (count($result) > 0) {
//          foreach ($result as $row) {
//                if ($row->man_type == '') {
//                    $row->man_type = 'unknown';
//                }
//                if ($row->location_name != $current_location) {
//                    $count ++;
//                    $i = new stdclass();
//                    $i->id = $row->location_id;
//                    $i->name = $row->location_name;
//                    $i->type = $row->location_type;
//                    $i->group = $row->location_group_id;
//                    $type_icon = str_replace("index.php", "", $_SERVER['SCRIPT_FILENAME']).'theme-tango/tango-images/32_'.str_replace(" ", "_", strtolower($row->location_type)).'.png';
//                    if (is_null($row->location_icon) or $row->location_icon == '') {
//                        if (file_exists($type_icon)) {
//                            $row->location_icon = str_replace(" ", "_", strtolower($row->location_type));
//                        } else {
//                            $row->location_icon = 'office';
//                        }
//                    }
//
//                    $i->address = "";
//                    if ($row->location_address > "") {
//                        $i->address = $row->location_address;
//                    }
//                    if ($row->location_city > "") {
//                        if ($i->address > "") {
//                            $i->address .= ", ".$row->location_city;
//                        } else {
//                            $i->address = $row->location_city;
//                        }
//                    }
//                    if ($row->location_postcode > "") {
//                        if ($i->address > "") {
//                            $i->address .= ", ".$row->location_postcode;
//                        } else {
//                            $i->address = $row->location_postcode;
//                        }
//                    }
//                    if ($row->location_country > "") {
//                        if ($i->address > "") {
//                            $i->address .= ", ".$row->location_country;
//                        } else {
//                            $i->address = $row->location_country;
//                        }
//                    }
//
//                    $i->geo = "";
//
//                    if ($row->location_latitude != "" and $row->location_latitude != "0.000000" and
//                        $row->location_longitude != "" and $row->location_longitude != "0.000000" and
//                        $i->geo == "") {
//                        if ($this->uri->segment($this->uri->total_rsegments()) == 'json') {
//                            $i->geo = '{"latitude":"'.$row->location_latitude.'","longitude":"'.$row->location_longitude.'"}';
//                        } else {
//                            $i->geo = "latitude: ".$row->location_latitude.", longitude: ".$row->location_longitude;
//                        }
//                    }
//
//                    if ($row->location_geo > "" and $i->geo == "") {
//                        if ($this->uri->segment($this->uri->total_rsegments()) == 'json') {
//                            $i->geo = '{"geocode":"'.$row->location_geo.'"}';
//                       } else {
//                            $i->geo = $row->location_geo;
//                        }
//                    }
//
//                    if ($i->geo == "") {
//                        $i->geo = $i->address;
//                        if ($this->uri->segment($this->uri->total_rsegments()) == 'json') {
//                            $i->geo = '{"geocode":"'.$i->geo.'"}';
//                        }
//                    }
//
//                    if ($this->uri->segment($this->uri->total_rsegments()) == 'json') {
//                        $i->icon = base_url().'theme-tango/tango-images/32_'.$row->location_icon.'.png';
//                        $i->icon = str_replace("http://127.0.0.1", "", $i->icon);
//                        $i->infoDisplay = '"'.$row->man_type.'":"'.$row->count.'", ';
//                    } else {
//                        $i->icon = base_url().'theme-tango/tango-images/32_'.$row->location_icon.'.png';
//                        $i->icon = str_replace("http://127.0.0.1", "", $i->icon);
//                        $j = $row->man_type;
//                        $i->$j = $row->count;
//                    }
//                    $table[$count] = $i;
//                } else {
//                    if ($this->uri->segment($this->uri->total_rsegments()) == 'json') {
//                        $i = $table[$count]->infoDisplay.'"'.$row->man_type.'":"'.$row->count.'", ';
//                    } else {
//                        $j = $row->man_type;
//                        $i->$j = $row->count;
//                    }
//                    $table[$count]->infoDisplay = $i;
//                }
//                $current_location = $row->location_name;
//            }
//        } else {
//            # no devices in database - send empty dataset
//            $i = new stdclass();
//            $i->id = "1";
//            $i->name = "Default Location";
//            $i->type = "Office";
//            $i->group = "";
//            $i->address = "Gold Coast, Australia";
//            $i->icon = base_url().'theme-tango/tango-images/32_office.png';
//            $i->icon = str_replace("http://127.0.0.1", "", $i->icon);
//            $i->geo = '{"latitude":"-28.017260","longitude":"153.425705"}';
//            $table[0] = $i;
//            $table[0]->infoDisplay = '"Devices": "none"  ';
//        }
//
//        $count = 0;
//        if ($this->uri->segment($this->uri->total_rsegments()) == 'json') {
//            foreach ($table as $each) {
//                $i = $each->infoDisplay;
//                $i = substr($i, 0, -2);
//                $i = "{".$i."}";
//                $table[$count]->infoDisplay = $i;
//                $count++;
//            }
//        }
//
//        return ($table);
//    }
}
