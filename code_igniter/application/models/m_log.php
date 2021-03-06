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
class M_log extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_system_log($system_id)
    {
        $sql = "SELECT
				sys_sw_log.log_id,
				sys_sw_log.log_name,
				sys_sw_log.log_file_name,
				sys_sw_log.log_file_size,
				sys_sw_log.log_max_file_size,
				sys_sw_log.log_overwrite
			FROM
				sys_sw_log,
				system
			WHERE
				sys_sw_log.system_id = system.system_id AND
				sys_sw_log.timestamp = system.timestamp AND
				system.system_id = ?
			GROUP BY
				sys_sw_log.log_id";
        $sql = $this->clean_sql($sql);
        $data = array($system_id);
        $query = $this->db->query($sql, $data);
        $result = $query->result();

        return ($result);
    }

    public function process_log($input, $details)
    {
        if (((string) $details->first_timestamp == (string) $details->original_timestamp) and ($details->original_last_seen_by != 'audit')) {
            # we have only seen this system once, and not via an audit script
            # insert the software and set the first_timestamp == system.first_timestamp
            # otherwise we cause alerts
            $sql = "INSERT INTO sys_sw_log
					( system_id,
					log_name,
					log_file_name,
					log_file_size,
					log_max_file_size,
					log_overwrite,
					timestamp,
					first_timestamp ) VALUES ( ?,?,?,?,?,?,?,? )";
            $sql = $this->clean_sql($sql);
            $data = array("$details->system_id",
                    trim($input->log_name),
                    trim($input->log_file_name),
                    "$input->log_file_size",
                    "$input->log_max_file_size",
                    trim($input->log_overwrite),
                    "$details->timestamp",
                    "$details->first_timestamp", );
            $query = $this->db->query($sql, $data);
        } else {
            // need to check for log changes
            $sql = "SELECT
					sys_sw_log.log_id
				FROM
					sys_sw_log,
					system
				WHERE
					sys_sw_log.system_id 		= system.system_id AND
					system.system_id		= ? AND
					system.man_status 		= 'production' AND
					sys_sw_log.log_name 		= ? AND
					sys_sw_log.log_file_name	= ? AND
					sys_sw_log.log_overwrite	= ? AND
					( sys_sw_log.timestamp = ? OR sys_sw_log.timestamp = ? )";
            $sql = $this->clean_sql($sql);
            $data = array("$details->system_id",
                    trim($input->log_name),
                    trim($input->log_file_name),
                    trim($input->log_overwrite),
                    "$details->original_timestamp",
                    "$details->timestamp", );
            $query = $this->db->query($sql, $data);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                // the log exists - need to update its timestamp
                $sql = "UPDATE sys_sw_log SET timestamp = ? WHERE log_id = ?";
                $data = array("$details->timestamp", "$row->log_id");
                $query = $this->db->query($sql, $data);
            } else {
                // the log does not exist - insert it
                $sql = "INSERT INTO sys_sw_log
						( system_id,
						log_name,
						log_file_name,
						log_file_size,
						log_max_file_size,
						log_overwrite,
						timestamp,
						first_timestamp ) VALUES ( ?,?,?,?,?,?,?,? )";
                $sql = $this->clean_sql($sql);
                $data = array("$details->system_id",
                        trim($input->log_name),
                        trim($input->log_file_name),
                        "$input->log_file_size",
                        "$input->log_max_file_size",
                        trim($input->log_overwrite),
                        "$details->timestamp",
                        "$details->timestamp", );
                $query = $this->db->query($sql, $data);
            }
        }
    }

    public function alert_log($details)
    {
        // log no longer detected
        $sql = "SELECT log_id, log_name, log_file_name FROM sys_sw_log WHERE system_id = ? and timestamp = ?";
        $data = array("$details->system_id", "$details->original_timestamp");
        $sql = $this->clean_sql($sql);
        $query = $this->db->query($sql, $data);
        foreach ($query->result() as $myrow) {
            $alert_details = 'log removed - '.$myrow->log_name;
            $this->m_alerts->generate_alert($details->system_id, 'sys_sw_log', $myrow->log_id, $alert_details, $details->timestamp);
        }

        // new log
        $sql = "SELECT log_id, log_name, log_file_name
			FROM
				sys_sw_log LEFT JOIN system ON (sys_sw_log.system_id = system.system_id)
			WHERE
				sys_sw_log.system_id = ? AND
				sys_sw_log.first_timestamp = ? AND
				sys_sw_log.first_timestamp = sys_sw_log.timestamp AND
				sys_sw_log.first_timestamp != system.first_timestamp";
        $data = array("$details->system_id", "$details->timestamp");
        $sql = $this->clean_sql($sql);
        $query = $this->db->query($sql, $data);

        foreach ($query->result() as $myrow) {
            $alert_details = 'log installed - '.$myrow->log_name.' ('.$myrow->log_file_name.')';
            $this->m_alerts->generate_alert($details->system_id, 'sys_sw_log', $myrow->log_id, $alert_details, $details->timestamp);
        }
    }
}
