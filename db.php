<?php

require "secret.php";

try {
    $conn = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, DBPASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    print "Error: " . $e->getMessage() . "\n";
    exit();
}


function WHERE(string $sql, array $wheres, string $type_where = "AND"): string
{
    $flag = match ($type_where) {
        "OR" => "OR",
        default => "AND",
    };
    $segment_where = "";
    foreach ($wheres as $key => $item) {
        $segment_where .= "`" . $key . "` = :" . $key . " " . $flag . " ";
    }
    $segment_where = rtrim($segment_where, " $flag ");
    $sql .= " WHERE " . $segment_where;
    return $sql;
}

function select($table_name, $cols = array(), $wheres = array(), $type_where = "AND"): array
{
    global $conn;

    $sql = "SELECT ";
    if (empty($cols))
        $cols = "*";
    else
        $cols = implode(", ", $cols);
    $sql .= $cols . " FROM " . $table_name;

    if (!empty($wheres)) {
        $sql = WHERE($sql, $wheres, $type_where);
    }
    $stmt = $conn->prepare($sql);
    foreach ($wheres as $key => $item) {
        $stmt->bindValue(':' . $key, $item);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);

}

function insert(string $table_name, array $cols): bool
{
    global $conn;

    $sql = "INSERT INTO `" . $table_name . "`(";

    foreach ($cols as $key => $item) {
        $sql .= "`" . $key . "`, ";
    }

    $sql = rtrim($sql, ", ");

    $sql .= ") VALUES (";

    foreach ($cols as $key => $item) {
        $sql .= ":" . $key . ", ";
    }
    $sql = rtrim($sql, ", ");

    $sql .= ")";

    $stmt = $conn->prepare($sql);

    foreach ($cols as $key => $item) {
        $stmt->bindValue(":" . $key, $item);
    }
    return $stmt->execute();
}

function delete(string $table_name, array $wheres = array(), $type_where = "AND"): void
{
    global $conn;

    $sql = "DELETE FROM `" . $table_name . "` ";

    if (!empty($wheres)) {
        $sql = WHERE($sql, $wheres, $type_where);
    }
    $stmt = $conn->prepare($sql);

    foreach ($wheres as $key => $item) {
        //???????????????
        $stmt->bindValue(":" . "$key", $item);
    }
    $stmt->execute();
}

function update(string $table_name, array $cols, array $wheres, string $type_where = "AND")
{
    global $conn;

    $sql = "UPDATE `" . $table_name . "` SET";

    foreach ($cols as $key => $item) {
        $sql .= " `" . $key . "`=:$key ,";
    }
    $sql = rtrim($sql, ",");

    if (!empty($wheres)) {
        $flag = match ($type_where) {
            "OR" => "OR",
            default => "AND",
        };
        $segment_where = "";
        foreach ($wheres as $key => $item) {
            $segment_where .= "`" . $key . "` = :old" . $key . " " . $flag . " ";
        }
        $segment_where = rtrim($segment_where, " $flag ");
        $sql .= " WHERE " . $segment_where;
    }

    $stmt = $conn->prepare($sql);

    foreach ($cols as $key => $item) {
        $stmt->bindValue(":$key", $item);
    }

    foreach ($wheres as $key => $item) {
        //???????????????
        $stmt->bindValue(":old" . "$key", $item);
    }
    return $stmt->execute();
}
