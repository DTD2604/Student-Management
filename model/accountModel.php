<?php
require "database/database.php";

function updateAccountById(
    $name,
    $password,
    $status,
    $roleId,
    $id
) {
    // Here is update to database
    date_default_timezone_set('Asia/Ho_Chi_Minh');// cap nhat lai mui gio vietnamese
    $db = connectionDb();
    $checkUpdate = false;
    $sql = "UPDATE `accounts` SET `username` = :nameName, `password` = :accountPassword, `status` = :statusAccount, `role_id` = :roleId,`updated_at` = :updated_at WHERE `id` = :id AND `deleted_at` IS NULL";
    $updateTime = date('Y-m-d H:i:s');
    $stmt = $db->prepare($sql);
    if ($stmt) {
        $stmt->bindParam(':username', $name, PDO::PARAM_STR);
        $stmt->bindParam(':accountPassword', $password, PDO::PARAM_STR);
        $stmt->bindParam(':statusAccount', $status, PDO::PARAM_INT);
        $stmt->bindParam(':roleID', $roleId, PDO::PARAM_INT);
        $stmt->bindParam(':updated_at', $updateTime, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $checkUpdate = true;
        }
    }
    disconnectionDb($db);
    return $checkUpdate;
}
function insertUser(
    $firstName,
    $lastName,
    $fullName,
    $email,
    $phone,
    $address,
    $birthDay,
    $gender,
    $avatar,
    $status
){
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $db = connectionDb();
    $flagInsert = false;
    $sqlInsert = "INSERT INTO `users`(`first_name`, `last_name`, `email`, `role_id`, `created_at`) VALUES(:username, :accountPassword, :statusAccount, :roleId, :created_at)";
    $stmt = $db->prepare($sqlInsert);
    $currentDate = date('Y-m-d H:i:s');
    if ($stmt) {
        $stmt->bindParam(':username', $name, PDO::PARAM_STR);
        $stmt->bindParam(':accountPassword', $password, PDO::PARAM_STR);
        $stmt->bindParam(':statusAccount', $status, PDO::PARAM_INT);
        $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
        $stmt->bindParam(':created_at', $currentDate, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $flagInsert = true;
        }
        disconnectionDb($db); // ngat ket noi database
    }
    // $flagInsert la true : insert thanh cong va nguoc lai
    return $flagInsert;
}
function insertAccount(
    $name,
    $password,
    $status,
    $roleId
) {
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $db = connectionDb();
    $flagInsert = false;
    $sqlInsert = "INSERT INTO `accounts`(`username`, `password`, `status`, `role_id`, `created_at`) VALUES(:username, :accountPassword, :statusAccount, :roleId, :created_at)";
    $stmt = $db->prepare($sqlInsert);
    $currentDate = date('Y-m-d H:i:s');
    if ($stmt) {
        $stmt->bindParam(':username', $name, PDO::PARAM_STR);
        $stmt->bindParam(':accountPassword', $password, PDO::PARAM_STR);
        $stmt->bindParam(':statusAccount', $status, PDO::PARAM_INT);
        $stmt->bindParam(':roleId', $roleId, PDO::PARAM_INT);
        $stmt->bindParam(':created_at', $currentDate, PDO::PARAM_STR);
        if ($stmt->execute()) {
            $flagInsert = true;
        }
        disconnectionDb($db); // ngat ket noi database
    }
    // $flagInsert la true : insert thanh cong va nguoc lai
    return $flagInsert;
}

function deleteAccountById($id = 0)
{
    // Dữ liệu trên giao diện bị xóa nhưng vẫn còn trong database
    date_default_timezone_set('Asia/Ho_Chi_Minh');// cap nhat lai mui gio vietnamese

    $db = connectionDb();
    $sql = "UPDATE `accounts` SET `deleted_at` = :deleted_at WHERE `id` = :id";
    $deletedAt = date("Y-m-d H:i:s");
    $stmt = $db->prepare($sql);
    $checkDelete = false;
    if ($stmt) {
        $stmt->bindParam(':deleted_at', $deletedAt, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $checkDelete = true;
        }
    }
    disconnectionDb($db);
    return $checkDelete;
}

function getDetailAccountById($id = 0)
{
    $db = connectionDb();
    $sql = "SELECT * FROM `accounts` WHERE `id` = :id AND `deleted_at` IS NULL";
    $stmt = $db->prepare($sql);
    $infoAccount = [];
    if ($stmt) {
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $infoAccount = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }
    }
    disconnectionDb($db);
    return $infoAccount;
}

function getAllDataAccounts($keyword = null)
{
    $db = connectionDb();
    $key = "%{$keyword}%";
    $sql = "SELECT roles.name AS role_name, users.full_name AS full_name, users.email AS email, users.phone AS phone,users.extra_code AS extra_code ,accounts.*
            FROM accounts
            INNER JOIN users ON accounts.user_id = users.id
            INNER JOIN roles ON  roles.id = accounts.role_id 
            WHERE (accounts.`username` LIKE :keywordName OR accounts.`password` LIKE :keywordPassword OR users.`full_name` LIKE :keywordFullName) 
            AND accounts.`deleted_at` IS NULL";
    $stmt = $db->prepare($sql);
    $data = [];
    if ($stmt) {
        $stmt->bindParam(':keywordName', $key, PDO::PARAM_STR);
        $stmt->bindParam(':keywordPassword', $key, PDO::PARAM_STR);
        $stmt->bindParam(':keywordFullName', $key, PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                //fetchall: là câu lệnh lấy tất cả dữ liệu của một câu lệnh sql đã được thực thi. câu lệnh này trả về một mảng chứa tất cả các hàng trong tập kết quả
            }
        }
        // print_r($data);
        // die;
    }
    disconnectionDb($db);
    return $data;
}

function getAllDataAccountsByPage($keyword = null, $start = 0, $limit = LIMIT_ITEM_PAGE)
{
    $db = connectionDb();
    $key = "%{$keyword}%";
    $sql = "SELECT roles.name AS role_name, users.full_name AS full_name, users.email AS email, users.phone AS phone,users.extra_code AS extra_code ,accounts.*
            FROM users
            INNER JOIN accounts ON accounts.user_id = users.id 
            INNER JOIN roles ON  roles.id = accounts.role_id 
            WHERE (accounts.`username` LIKE :keywordName OR accounts.`password` LIKE :keywordPassword OR users.`full_name` LIKE :keywordFullName) 
            AND accounts.`deleted_at` IS NULL 
            LIMIT :startData, :limitData";
    $stmt = $db->prepare($sql);
    $dataAccounts = [];
    if ($stmt) {
        $stmt->bindParam(':keywordName', $key, PDO::PARAM_STR);
        $stmt->bindParam(':keywordPassword', $key, PDO::PARAM_STR);
        $stmt->bindParam(':keywordFullName', $key, PDO::PARAM_STR);
        $stmt->bindParam(':startData', $start, PDO::PARAM_INT);
        $stmt->bindParam(':limitData', $limit, PDO::PARAM_INT);
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $dataAccounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }
    // print_r($dataAccounts);
    // die;
    disconnectionDb($db);
    return $dataAccounts;
}

function detailNameRole()
{
    $db = connectionDb();
    $sql = "SELECT * FROM `Roles`";
    $stmt = $db->prepare($sql);
    $infoName = [];
    if ($stmt) {
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $infoName = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        }
    }

    disconnectionDb($db);
    return $infoName;
}
