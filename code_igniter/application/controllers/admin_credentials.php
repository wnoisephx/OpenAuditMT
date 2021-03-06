<?php
#
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
class Admin_credentials extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $log_details = new stdClass();
        stdlog($log_details);
        unset($log_details);
    }

    public function index()
    {
        redirect('/');
    }

    public function list_credentials()
    {
        $this->load->model("m_oa_credential");
        $this->data['query'] = $this->m_oa_credential->get_all_credentials();
        $this->data['heading'] = 'List Credentials';
        $this->data['include'] = 'v_list_credentials';
        $this->data['sortcolumn'] = '1';
        $this->load->view('v_template', $this->data);
    }

    public function delete_location()
    {
        $this->load->model("m_oa_location");
        $this->load->model("m_oa_group");
        $location_id = $this->data['id'];
        // Delete the group.
        $group_id = $this->m_oa_location->get_group_id($location_id);
        $this->m_oa_group->delete_group($group_id);
        // Delete the location.
        $this->m_oa_location->delete_location($location_id);
        redirect('admin_location/list_locations');
    }

    public function delete_group()
    {
        $this->load->model("m_oa_group");
        $this->load->model("m_oa_location");
        // We have the location_id, need to get the correct group_id
        $location_id = $this->data['id'];
        $group_id = $this->m_oa_location->get_group_id($location_id);
        // Delete the group
        $this->m_oa_group->delete_group($group_id);
        // Update the oa_org by removing the group_id
        $this->m_oa_location->set_group_id($location_id, '0');
        redirect('admin_location/list_locations');
    }

    public function add_location()
    {
        if (!isset($_POST['submit'])) {
            # load the initial form
            $this->data['heading'] = 'Add Location';
            $this->data['include'] = 'v_add_location';
            $this->load->view('v_template', $this->data);
        } else {
            # process the form
            $details = new stdClass();
            foreach ($_POST as $key => $value) {
                $details->$key = $value;
            }
            $details->picture = '';
            $this->load->model("m_oa_location");
            if (is_null($this->m_oa_location->get_location_id($details->location_name))) {
                #location does not exist - good
                $details->location_id = $this->m_oa_location->add_location($details);
            } else {
                $this->data['error_message'] = "Location Name already exists.";
                $this->data['heading'] = 'Add Location';
                $this->data['include'] = 'v_add_location';
                $this->load->view('v_template', $this->data);
            }
            if ($details->location_group == 'on') {
                # activate the group
                redirect('admin_location/activate_group/'.$details->location_id);
            } else {
                redirect('admin_location/list_locations');
            }
        }
    }

    public function activate_group()
    {
        # insert a matching Group
        $this->load->model("m_oa_location");
        $this->load->model("m_oa_group");
        $location_id = $this->data['id'];
        $location_name = $this->m_oa_location->get_location_name($location_id);
        $location_group_id = $this->m_oa_location->get_group_id($location_id);
        $group = new stdClass();
        $group->group_id = '';
        $group->group_name = "Items in ".$location_name;
        $group->group_padded_name = '';
        $group->group_description = "Items in ".$location_name;
        $group->group_icon = 'location';
        $group->group_category = 'location';
        $group->group_dynamic_select = "SELECT distinct(system.system_id) FROM system WHERE (system.man_location_id = '".$this->data['id']."' OR LOWER(system.sysLocation) LIKE LOWER('%".$location_name."%')) AND system.man_status = 'production'";
        $group->group_parent = '';
        $group->group_display_sql = '';
        if (isset($location_group_id) and $location_group_id != '' and $location_group_id != '0') {
            # update an existing group
            $group->group_id = $location_group_id;
            $this->m_oa_group->update_group($group);
        } else {
            # insert a new group
            $group->group_id = $this->m_oa_group->insert_group($group);
            # update the oa_org with the correct group_id
            $this->m_oa_location->set_group_id($location_id, $group->group_id);
        }

        # and now update the group contents
        $this->m_oa_group->update_specific_group($group->group_id);
        # now send the user back to list_locations
        redirect('admin_location/list_locations');
    }

    public function add_locations()
    {
        if (!isset($_POST['submit'])) {
            # nothing submitted - display the form
            $this->data['heading'] = 'Add Locations';
            $this->data['include'] = 'v_add_locations';
            $this->load->view('v_template', $this->data);
        } else {

            $this->load->model("m_oa_location");
            # we have an uploaded file - store and process
            $target_path = BASEPATH."../application/uploads/".basename($_FILES['upload_file']['name']);
            try {
                move_uploaded_file($_FILES['upload_file']['tmp_name'], $target_path);
            } catch (Exception $e) {
                $this->data['query'] = $e;
                $this->data['error'] = "There was an error uploading the file, please try again.";
                $this->data['include'] = 'v_error';
                $this->load->view('v_template', $this->data);
            }
            require_once BASEPATH.'../application/libraries/phpexcel/PHPExcel/IOFactory.php';
            if (!$objPHPExcel = PHPExcel_IOFactory::load($target_path)) {
                exit;
            }
            $attributes = array();
            $details = array();
            $object = new stdClass();
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $count = 0;
            foreach ($objWorksheet->getRowIterator() as $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                if ($count == 0) {
                    foreach ($cellIterator as $cell) {
                        $attributes[] = $cell->getValue();
                    }
                } else {
                    $cell_number = 0;
                    foreach ($cellIterator as $cell) {
                        $details[$attributes[$cell_number]] = $cell->getValue();
                        $cell_number++;
                    }
                    if ($details['location_name'] != '') {
                        if ($location_id = $this->m_oa_location->get_location_id($details['location_name'])) {
                            // we need to update an existing location
                            $sql = "UPDATE oa_location SET ";
                            foreach ($details as $detail => $value) {
                                $sql .= $detail." = '".mysql_real_escape_string($value)."', ";
                            }
                            $sql = mb_substr($sql, 0, mb_strlen($sql)-2);
                            $sql .= " WHERE location_name = '".mysql_real_escape_string($details['location_name'])."'";
                        } else {
                            // this is a new location (we don't have a name match)
                            $sql = "INSERT INTO oa_location ( ";
                            foreach ($details as $detail => $value) {
                                $sql .= $detail.", ";
                            }
                            $sql = mb_substr($sql, 0, mb_strlen($sql)-2);
                            $sql .= " ) VALUES ( ";
                            foreach ($details as $detail => $value) {
                                $sql .= "'".mysql_real_escape_string(str_replace('"', '', $value))."', ";
                            }
                            $sql = mb_substr($sql, 0, mb_strlen($sql)-2);
                            $sql .= ")";
                        }
                        // run the query !!!
                        echo $sql."<br />\n";
                        $query = $this->db->query($sql);
                    } else {
                        echo "no location name provided";
                    }
                }
                $count++;
            }
            redirect('admin_location/list_locations');
        }
        if (isset($_POST['submit']) and isset($_POST['form_systemXML']) and $_POST['form_systemXML'] > '') {
            // we have XML text - process
            echo "XML processing<br />\n";
            $this->load->helper('xml');
            $this->load->model("m_oa_location");
            $xml = new SimpleXMLElement(utf8_encode(str_replace('&', '&amp;', $_POST['form_systemXML'])));
            foreach ($xml->children() as $child) {
                if ($location_id = $this->m_oa_location->get_location_id($child->location_name)) {
                    # we need to update an existing location
                    $sql = "UPDATE oa_location SET ";
                    foreach ($child->children() as $detail) {
                        $sql .= $detail->getName()." = '".$detail."', ";
                    }
                    $sql = mb_substr($sql, 0, mb_strlen($sql)-2);
                    $sql .= " WHERE location_name = '".$child->location_name."'";
                } else {
                    # this is a new location (we don't have a name match)
                    $sql = "INSERT INTO oa_location ( ";
                    foreach ($child->children() as $detail) {
                        $sql .= $detail->getName().", ";
                    }
                    $sql = mb_substr($sql, 0, mb_strlen($sql)-2);
                    $sql .= " ) VALUES ( ";
                    foreach ($child->children() as $detail) {
                        $sql .= "'".$detail."', ";
                    }
                    $sql = mb_substr($sql, 0, mb_strlen($sql)-2);
                    $sql .= ")";
                }
                if ($child->location_name != '') {
                    # run the query !!!
                    $query = $this->db->query($sql);
                } else {
                    echo "no location name provided";
                }
            }
        }
        # catch all redirect
        # redirect('admin_location/list_locations');
    }

    public function edit_location()
    {
        $this->load->model("m_oa_location");
        if (!isset($_POST['submit'])) {
            # load the initial form
            $this->data['location'] = $this->m_oa_location->get_location($this->data['id']);
            $this->data['heading'] = 'Edit Location';
            $this->data['include'] = 'v_edit_location';
            $this->data['sortcolumn'] = '1';
            $this->load->view('v_template', $this->data);
        } else {
            # process the form
            $error = '0';
            foreach ($_POST as $key => $value) {
                $details->$key = $value;
            }
            $details->picture = '';
            if ($this->m_oa_location->check_location_name($details->location_name, $details->location_id) == false) {
                $error = '1';
                $this->data['error_message'] = "Location name already exists.";
                $this->data['location'] = $this->m_oa_location->get_location($details->location_id);
                $this->data['heading'] = 'Edit Location';
                $this->data['include'] = 'v_edit_location';
                $this->load->view('v_template', $this->data);
            }

            if ($error == '0') {
                $this->m_oa_location->edit_location($details);
                if ($_POST['location_group'] == 'on') {
                    redirect('admin_location/activate_group/'.$_POST['location_id']);
                } else {
                    redirect('admin_location/list_locations');
                }
            } else {
                redirect('admin_location/list_locations');
            }
        }
    }
}
