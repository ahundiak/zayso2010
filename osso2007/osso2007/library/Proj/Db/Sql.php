<?php
class Proj_Db_Sql
{
    static function tableName($db,$tableName,$tableAlias = NULL)
    {
        $tableName = $db->quoteIdentifier($tableName);
        
        if (!$tableAlias) return $tableName;
        
        return $tableName . ' AS ' . $db->quoteIdentifier($tableAlias);
    }    
    static function columnName($db, $tableName, $columnName, $columnAlias = NULL)
    {
        // Always have a column
        $columnName  = $db->quoteIdentifier($columnName);
        
        // Append on any aliases
        if ($columnAlias) $columnName .= ' AS ' . $db->quoteIdentifier($columnAlias);
        
        // May or may not have a table alias
        if (!$tableName) return $columnName;
        
        return $db->quoteIdentifier($tableName) . '.' . $columnName;
    }
    static function columnExp($db, $columnExp, $columnAlias = NULL)
    {
        // Append on any aliases
        if ($columnAlias) $columnExp .= ' AS ' . $db->quoteIdentifier($columnAlias);
        
        return $columnExp;
    }
}
?>
