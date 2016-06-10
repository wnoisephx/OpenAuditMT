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
class Admin_proxy extends MY_Controller
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

    public function list_proxies()
    {
        $this->load->model("m_oa_proxy");
        $this->data['query'] = $this->m_oa_proxy->list_proxies();
        $this->data['heading'] = 'List Proxies';
        $this->data['include'] = 'v_list_proxies';
        $this->data['sortcolumn'] = '1';
        $this->load->view('v_template', $this->data);
    }

    public function add_proxy()
    {

        $this->load->model("m_oa_location");
        $this->load->model("m_oa_proxy");
//        $this->locd->model("m_oa_credential");
        $this->data['locations'] = $this->m_oa_location->get_location_names();
//        $this->data['credentials'] = $this->m_oa_credential->get_credential_names();
$this->data['credentials'] = array();
        if (!isset($_POST['submit'])) {
            # load the initial form
            $this->data['heading'] = 'Add Proxy';
            $this->data['include'] = 'v_add_proxy';
            $this->load->view('v_template', $this->data);
        } else {
            # process the form
            foreach ($_POST as $key => $value) {
                $details->$key = $value;
            }

            if(isset($details->activate))
                $details->activate=true;
            else
                $details->activate=false;

            $this->m_oa_proxy->add_proxy($details);

            redirect('admin_proxy/list_proxies/');
        }
    }

    public function delete_proxy()
    {
        $this->load->model("m_oa_proxy");
        $location_id = $this->data['id'];
        $this->m_oa_proxy->delete_proxy($location_id);
        redirect('admin_location/edit_location/'.$location_id);
    }

    public function edit_proxy()
    {
        $this->load->model("m_oa_location");
        $this->load->model("m_oa_proxy");
        if (!isset($_POST['submit'])) {
            # load the initial form
            $this->data['proxy'] = $this->m_oa_proxy->get_proxy($this->data['id']);
            $this->data['location'] = $this->m_oa_location->get_all_locations();
            $this->data['heading'] = 'Edit Proxy';
            $this->data['include'] = 'v_edit_proxy';
            $this->data['sortcolumn'] = '1';
            $this->load->view('v_template', $this->data);
        } else {
            # process the form
            foreach ($_POST as $key => $value) {
                $details->$key = $value;
            }

	    $details->proxy_id = $details->location;
            if(isset($details->activate))
                $details->activate=true;
	    else
                $details->activate=false;

            if ($_POST['flag'])
	         $this->m_oa_proxy->add_proxy($details);
	    else
                 $this->m_oa_proxy->edit_proxy($details);

            redirect('admin_location/edit_location/'.$details->proxy_id);
        }
    }
}
