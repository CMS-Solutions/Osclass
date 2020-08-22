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
 * Class Cron
 */
class Cron extends DAO
{
    /**
     *
     * @var Cron
     */
    private static $instance;

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTableName('t_cron');
        $this->setFields(array('e_type', 'd_last_exec', 'd_next_exec'));
    }

    /**
     * @return \Cron
     */
    public static function newInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Return crons by type
     *
     * @access public
     *
     * @param string $type
     *
     * @return array|bool
     * @since  unknown
     *
     */
    public function getCronByType($type)
    {
        $this->dao->select();
        $this->dao->from($this->getTableName());
        $this->dao->where('e_type', $type);
        $result = $this->dao->get();

        if ($result->numRows == 0) {
            return false;
        }

        return $result->row();
    }
}

/* file end: ./oc-includes/osclass/model/Cron.php */
