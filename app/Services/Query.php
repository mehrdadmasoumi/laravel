<?php namespace App\Services;

use FarsiLib;
use Request;
use Config;

class Query {
    private $optArray = array('like' => 'LIKE', 'like%' => 'LIKE', '%like' => 'LIKE', 'eq' => '=', 'ne' => '<>', 'gt' => '>', 'ge' => '>=', 'lt' => '<', 'le' => '<=');
    public $where = '';
    public $group = '';
    public $having = '';
    public $order = '';
    public $limit = '';

    public function __construct($q)
    {
        if (!is_array($q)) $q = array();
        $this->where = $this->makeWhere(@$q['where']);
        $this->group = $this->makeGroup(@$q['group']);
        $this->having = $this->makeHaving(@$q['having']);
        $this->order = $this->makeorder(@$q['order']);
        $this->limit = $this->makeLimit(@$q['limit']);
        $this->skip = $this->makeLimit(@$q['limit'], '', 'array')['skip'];
        $this->take = $this->makeLimit(@$q['limit'], '', 'array')['take'];
    }

    // like:  %LIKE%
    // like%: LIKE%
    // %like: %LIKE
    // eq: =
    // ne: <>
    // gt: >
    // ge: >=
    // lt: <
    // le: <=
    public function makeWhere($w, $prefix = '')
    {
        $cond = strtoupper(@$w['condition']) == 'OR' ? 'OR' : 'AND';
        unset($w['condition']);
        $res = array();
        if (is_array($w)) foreach ($w as $fld => $val1) {
            if (!preg_match('/^[\w\.]+$/', $fld)) {
                alert("Invalid field name: $fld");
                continue;
            }
            foreach ($val1 as $rawop => $val2) {
                $op = @$this->optArray[strtolower($rawop)];
                if (!$op) {
                    alert("Invalid operator: $op");
                    continue;
                }
                foreach ($val2 as $type => $val3) {
                    $type = strtolower($type);
                    if (!preg_match('/^int|float|str|date|pdate$/', $type)) {
                        alert("Invalid type: $type");
                        continue;
                    }
                    if (!is_array($val3)) $val3 = array($val3);
                    foreach ($val3 as $val) {
                        if ($val == '') continue;
                        switch ($type) {
                            case 'int':
                                $val = intval($val);
                                break;
                            case 'float':
                                $val = floatval($val);
                                break;
                            case 'pdatetime':
                                $val = FarsiLib::j2gDate($val); //   persian dates
                            case 'datetime':
                                $fld = 'DATE(' . $fld . ')';
                                $val = date('Y-m-d H:i:s', strtotime($val));
                                $val = addcslashes($val, "\n\r\'\"\\");
                                break;
                            case 'pdate':
                                $val = FarsiLib::j2gDate($val); //   persian dates
                            case 'date':
                                $fld = 'DATE(' . $fld . ')';
                                $val = date('Y-m-d', strtotime($val));
                                break;
                            case 'str':
                                $val = addcslashes($val, "\n\r\'\"\\");
                                break;
                        }
                        $val = QUERY::makeVal($val, $rawop);
                        $res[] = $fld . ' ' . $op . ' ' . $val;
                    }
                }
            }
        }
        return $res ? $prefix . ' ' . implode(' ' . $cond . ' ', $res) : $prefix . ' 1';
    }

    public function makeGroup($g, $prefix = '')
    {
        if (!$g) return '';
        if (!is_array($g)) $g = array($g);
        $res = null;
        foreach ($g as $fld) {
            if (preg_match('/^[\w\.]+$/', $fld)) {
                $res[] = $fld;
            } else {
                alert("Invalid group by field: $fld");
            }
        }
        return $res ? $prefix . ' ' . implode(', ', $res) : '';
    }

    public function makeHaving($h, $prefix = '')
    {
        return QUERY::makeWhere($h, $prefix);
    }

    public function makeOrder($o, $prefix = '', $return = 'NULL')
    {
        if (!$o) return 'NULL';
        if (!is_array($o)) $o = array($o => 'ASC');
        $res = null;
        foreach ($o as $fld => $dir) {
            if (strtoupper($dir) != 'DESC' and strtoupper($dir) != 'ASC') {
                $fld = $dir;
                $dir = 'ASC';
            }
            $dir = (strtoupper($dir) == 'DESC') ? 'DESC' : 'ASC';
            if (preg_match('/^[\w\.]+$/', $fld)) {
                $res[] = $fld . ' ' . $dir;
            } else {
                alert("Invalid order by field: $fld");
            }
        }
        return $res ? $prefix . ' ' . implode(', ', $res) : '';
    }

    public function makeLimit($l, $prefix = 'LIMIT', $return = '')
    {
        if (empty($l['count'])) {
            $l['count'] = (Request::get('perPage') ? Request::get('perPage') : Config::get('custom.perPage'));
            $l['count'] = $l['count'] ? $l['count'] : 10;
            if (!empty($_REQUEST['page'])) {
                $l['offset'] = max((intval(Request::get('page')) - 1) * $l['count'], 0);
            }
        }

        if ($return == 'array') {
            $return = ['skip' => intval(@$l['offset']), 'take' => intval(@$l['count'])];     
            return $return;
        } else {
            $l['count'] = intval(@$l['count']);
            $l['offset'] = ' OFFSET ' . intval(@$l['offset']);
            return $prefix . ' ' . $l['count'] . $l['offset'];
        }
    }

    public function makeVal($val, $op)
    {
        $op = strtolower($op);
        $val = FarsiLib::fa2EnDigit($val);

        if ($op == 'like') {
            $val = "'%$val%'";
        } elseif ($op == 'like%') {
            $val = "'$val%'";
        } elseif ($op == '%like') {
            $val = "'%$val'";
        } elseif (gettype($val) == 'string' and !preg_match('/DATE\(\'[-: \d]+\'\)/', $val)) {
            $val = "'$val'";
        }
        return $val;
    }
}
