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
class Admin_network extends MY_Controller
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
        redirect('/admin_network/list_networks');
    }

    public function list_networks()
    {
        $this->load->model("m_oa_network");
        $this->data['query'] = $this->m_oa_network->get_all_networks();
        $this->data['heading'] = 'List Networks';
        $this->data['include'] = 'v_list_networks';
        $this->data['sortcolumn'] = '1';
        $this->load->view('v_template', $this->data);
    }

    public function list_networks_by_proxy_id()
    {
	$proxy_id = $this->data['id'];
        $this->load->model("m_oa_network");
        $this->data['query'] = $this->m_oa_network->get_networks_by_proxy_id($proxy_id);
        $this->data['heading'] = 'List Networks';
        $this->data['include'] = 'v_list_networks';
        $this->data['sortcolumn'] = '1';
        $this->load->view('v_template', $this->data);
    }

    public function toggle_network()
    {
	$network = $this->data['id'];
        $this->load->model("m_oa_network");
	$this->m_oa_network->toggle_network($network);
        redirect('/admin_network/list_networks');
    }

//    public function delete_network()
//    {
//        $this->load->model("m_oa_network");
//        $this->load->model("m_oa_group");
//        $org_id = $this->data['id'];
//        # delete the group
//        $group_id = $this->m_oa_org->get_group_id($org_id);
//        $this->m_oa_group->delete_group($group_id);
//        # delete the org
//        $this->m_oa_org->delete_org($org_id);
//        redirect('admin_networks/list_networks');
//    }

//    public function delete_group()
//    {
//        $this->load->model("m_oa_group");
//        $this->load->model("m_oa_network");
//        # we have the org_id, need to get the correct group_id
//        $org_id = $this->data['id'];
//        $group_id = $this->m_oa_org->get_group_id($org_id);
//        # delete the group
//        $this->m_oa_group->delete_group($group_id);
//        # update the oa_org by removing the group_id
//        $this->m_oa_org->set_group_id($org_id, '0');
//        redirect('admin_network/list_networks');
//    }

//    public function activate_group()
//    {
//        # insert a matching Group
//        $this->load->model("m_oa_network");
//        $this->load->model("m_oa_group");
//        $org_id = $this->data['id'];
//        $org_name = $this->m_oa_org->get_org_name($org_id);
//        $org_group_id = $this->m_oa_org->get_group_id($org_id);
//
//        $group = new stdClass();
//        $group->group_name = $org_name." owned items";
//        $group->group_padded_name = '';
//        $group->group_description = $org_name." owned items";
//        $group->group_icon = 'contact';
//        $group->group_category = 'owner';
//        $group->group_dynamic_select = "SELECT distinct(system.system_id) FROM system WHERE system.man_org_id = '".$this->data['id']."' AND system.man_status = 'production'";
//        $group->group_parent = '';
//        $group->group_display_sql = '';
//        if (isset($org_group_id) and $org_group_id != '' and $org_group_id != '0') {
//            # update an existing group
//            $group->group_id = $org_group_id;
//            $this->m_oa_group->update_group($group);
//        } else {
//            # insert a new group
//            $group->group_id = $this->m_oa_group->insert_group($group);
//            # update the oa_org with the correct group_id
//            $this->m_oa_org->set_group_id($org_id, $group->group_id);
//        }
//        # and now update the group contents
//        $this->m_oa_group->update_specific_group($group->group_id);
//        # now send the user back to list_orgs
//        redirect('admin_network/list_networks');
//    }

    public function add_network()
    {
        $this->load->model("m_oa_network");
        $this->load->model("m_oa_location");
        if (!isset($_POST['submit'])) {
            # load the initial form
            $this->data['locations'] = $this->m_oa_location->get_location_names();
            $this->data['heading'] = 'Add Network';
            $this->data['include'] = 'v_add_network';
            $this->load->view('v_template', $this->data);
        }
echo "not implamented in admin_network controller";
//       else {
//            # process the form
//            foreach ($_POST as $key => $value) {
//                $details->$key = $value;
//            }
//            if (is_null($this->m_oa_org->select_org($details->org_name))) {
//                #org does not exist - good
//                $details->org_id = $this->m_oa_org->add_org($details);
//            } else {
//                $this->data['error_message'] = "Organisation name already exists.";
//                $this->data['heading'] = 'Add Organisation';
//                $this->data['include'] = 'v_add_network';
//                $this->load->view('v_template', $this->data);
//            }
//            if ($details->org_group == 'on') {
//                # activate the group
//                redirect('admin_network/activate_group/'.$details->org_id);
//            } else {
//                redirect('admin_network/list_networks');
//            }
//        }
    }

//    public function edit_network()
//    {
//        $this->load->model("m_oa_network");
//        if (!isset($_POST['submit'])) {
//            # load the initial form
//            $this->data['org'] = $this->m_oa_org->get_org_details($this->data['id']);
//            $this->data['org_names'] = $this->m_oa_org->get_org_names();
//            $this->data['heading'] = 'Edit Organisation';
//            $this->data['include'] = 'v_edit_network';
//            $this->data['sortcolumn'] = '1';
//            $this->load->view('v_template', $this->data);
//        } else {
//            # process the form
//            $error = '0';
//            $details = new stdClass();
//            foreach ($_POST as $key => $value) {
//                $details->$key = $value;
//            }
//            if ($this->m_oa_org->check_org_name($details->org_name, $details->org_id) == false) {
//                $error = '1';
//                $this->data['error_message'] = "Organisation name already exists.";
//                $this->data['org'] = $this->m_oa_org->get_org_details($details->org_id);
//                $this->data['heading'] = 'Edit Organisation';
//                $this->data['include'] = 'v_edit_org';
//                $this->load->view('v_template', $this->data);
//            }
//            if ($error == '0') {
//                $this->m_oa_org->edit_org($details);
//                redirect('admin_network/list_networks');
//            }
//        }
//    }
}
