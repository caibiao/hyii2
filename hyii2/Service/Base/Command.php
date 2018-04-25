<?php
/**
 * @copyright 本软件和相关文档仅限 安乐窝 和/或其附属公司开发团队内部交流使用，
 *            并受知识产权法的保护。除非公司以适用法律明确授权，否则不得以任何形
 *            式、任何方式使用、拷贝、复制、翻译、广播、修改、授权、传播、分发、
 *            展示、执行、发布或显示本软件和相关文档的任何部分。
 * @author    林猛锋<linmengfeng@anlewo.com>
 * @date      2018/3/22 16:51
 */

namespace Service\Base;


class Command extends \yii\db\Command
{
    /**
     * 批量更新
     * @param $table
     * @param $columns
     * @param $rows
     * @param $keyPrimaryArrs
     * @param $keyPrimaryColumn
     * @return $this
     */
    public function batchUpdate($table, $columns, $rows, $keyPrimaryArrs, $keyPrimaryColumn)
    {

        $sql = '';
        $sql .= 'UPDATE ' . $table . ' SET ';
        $rowsCount = count($rows);
        $columnsCount = count($columns);
        $columnName = '';
        $rowFang = '';

        for ($i = 0; $i < $columnsCount; $i++) {
            $columnName = isset($columns[$i]) ? $columns[$i] : $columnName;
            $sql .= $columnName . ' = CASE ' . $keyPrimaryColumn;

            for ($j = 0; $j < $rowsCount; $j++) {
                $rowFang = isset($rows[$j][$i]) ? $rows[$j][$i] : $rowFang;
                $keyPrimary = isset($keyPrimaryArrs[$j]) ? $keyPrimaryArrs[$j] : $keyPrimary;
                if (gettype($rowFang) == 'integer') {
                    $sql .= ' WHEN \'' . $keyPrimary . '\' THEN ' . $rowFang;
                } else {
                    $sql .= ' WHEN \'' . $keyPrimary . '\' THEN \'' . $rowFang . '\'';
                }
            }

            if ($i === $columnsCount) {
                $end = ' END ';
            } else {
                $end = ' END, ';
            }
            $sql .= $end;
        }
        $conditions = '(\'' . implode('\',\'', $keyPrimaryArrs) . '\')';

        $sql .= ' WHERE ' . $keyPrimaryColumn . ' IN ' . $conditions;
        $sql = str_replace('END,  WHERE', 'END  WHERE', $sql);
        return $this->setSql($sql);
    }
}