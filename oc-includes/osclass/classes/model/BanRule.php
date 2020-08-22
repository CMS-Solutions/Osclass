<?php

/*
 *  Copyright 2020 Osclass
 *  Maintained and supported by Mindstellar Community
 *  https://github.com/mindstellar/Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * BanRule DAO
 */
class BanRule extends DAO
{
    /**
     *
     * @var \BanRule
     */
    private static $instance;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTableName('t_ban_rule');
        $this->setPrimaryKey('pk_i_id');
        $array_fields = array(
            'pk_i_id',
            's_name',
            's_ip',
            's_email'
        );
        $this->setFields($array_fields);
    }

    /**
     * @return \BanRule
     */
    public static function newInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Return list of ban rules
     *
     * @access public
     *
     * @param int    $start
     * @param int    $end
     * @param string $order_column
     * @param string $order_direction
     * @param string $name
     *
     * @return array
     * @parma  string $name
     * @since  3.1
     *
     */
    public function search($start = 0, $end = 10, $order_column = 'pk_i_id', $order_direction = 'DESC', $name = '')
    {
        // SET data, so we always return a valid object
        $rules                  = array();
        $rules['rows']          = 0;
        $rules['total_results'] = 0;
        $rules['rules']         = array();

        $this->dao->select('SQL_CALC_FOUND_ROWS *');
        $this->dao->from($this->getTableName());
        $this->dao->orderBy($order_column, $order_direction);
        $this->dao->limit($start, $end);
        if ($name != '') {
            $this->dao->like('s_name', $name);
        }
        $rs = $this->dao->get();

        if ($rs == false) {
            return $rules;
        }

        $rules['rules'] = $rs->result();

        $rsRows = $this->dao->query('SELECT FOUND_ROWS() as total');
        $data   = $rsRows->row();
        if ($data['total']) {
            $rules['total_results'] = $data['total'];
        }

        $rsTotal = $this->dao->query('SELECT COUNT(*) as total FROM ' . $this->getTableName());
        $data    = $rsTotal->row();
        if ($data['total']) {
            $rules['rows'] = $data['total'];
        }

        return $rules;
    }

    /**
     * Return number of ban rules
     *
     * @return int
     * @since 3.1
     */
    public function countRules()
    {
        $this->dao->select('COUNT(*) as i_total');
        $this->dao->from($this->getTableName());

        $result = $this->dao->get();

        if ($result == false || $result->numRows() == 0) {
            return 0;
        }

        $row = $result->row();

        return $row['i_total'];
    }
}
