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
class M_oa_proxy extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get the proxy details from the id.
     *
     * @access	public
     *
     * @param	proxy_id
     *
     * @return string
     */
    public function get_proxy($id)
    {
        $sql = "SELECT * FROM oa_proxy WHERE proxy_id = ? LIMIT 1";
        $data = array($id);
        $query = $this->db->query($sql, $data);
        $result = $query->result();

        return ($result);
    }

    /**
     * Get all the proxy details.
     *
     * @access  public
     *
     * @return array
     */
    public function list_proxies()
    {
        $sql = "SELECT *, count(oa_network.proxy_id) as total FROM oa_proxy LEFT JOIN oa_network ON oa_network.proxy_id = oa_proxy.proxy_id GROUP BY oa_proxy.proxy_id ORDER BY oa_proxy.proxy_name";
        $sql = $this->clean_sql($sql);
        $query = $this->db->query($sql);
        $result = $query->result();

        return ($result);
    }

    /**
     * Add a proxy to the database - Admin only.
     *
     * @access	public
     *
     * @param	details - the relevant proxy details object
     *
     * @return string
     */
    public function add_proxy($details)
    {
        # need to insert suburb, district, region, area, tags, picture
        $sql = "INSERT INTO oa_proxy
                                       (proxy_id,
					ip_address_v4,
                                        proxy_name,
					activate
                                        )
                                VALUES (?, ?, ?, ?)";
        $sql = $this->clean_sql($sql);
        $data = array("$details->proxy_id",
	    "$details->ip_address_v4",
            "$details->proxy_name", 
	    "$details->activate", );
        $query = $this->db->query($sql, $data);

        return($this->db->insert_id());
    }

    /**
     * Delete a proxy from the database - Admin only.
     *
     * @access	public
     *
     * @param	detail id - the id of the proxy to delete
     *
     * @return nothing
     */
    public function delete_proxy($proxy_id)
    {
        $sql = "DELETE FROM oa_proxy WHERE proxy_id = ?";
        $data = array("$proxy_id");
        $query = $this->db->query($sql, $data);
    }

    /**
     * Update a proxy in the database - Admin only.
     *
     * @access	public
     *
     * @param	details - the relevant proxy details object
     *
     * @return TRUE
     */
    public function edit_proxy($details)
    {
        $sql = "UPDATE oa_proxy SET
					ip_address_v4 = ?,
					proxy_name = ?,
					activate = ?
				WHERE
					proxy_id = ?";
        $sql = $this->clean_sql($sql);
        $data = array("$details->ip_address_v4",
            "$details->proxy_name",
            "$details->activate",
            "$details->proxy_id", );
        $query = $this->db->query($sql, $data);

        return(true);
    }
}
