<?php
require 'model/accountModel.php';

$m = trim($_GET['m'] ?? 'index'); // trim : xoa khoang trang 2 dau
$m = strtolower($m); // chuyen ve chu thuong

switch ($m) {
    case 'index':
        index();
        break;
    case 'add':
        Add();
        break;
    case 'handle-add':
        handleAdd();
        break;
    case 'delete':
        handleDelete();
        break;
    case 'edit':
        edit();
        break;
    case 'handle-update':
        handleUpdate();
        break;
    default:
        index();
        break;
}

function handleUpdate()
{
    if (isset($_POST['btnUpdate'])) {
        $id = $_GET['id'] ?? null;
        $id = is_numeric($id) ? $id : 0;

        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $password = trim($_POST['password'] ?? null);
        $password = strip_tags($password);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $roleId = $_POST['role_id'];

        // check du lieu
        $_SESSION['error_accounts'] = [];

        if (empty($name)) {
            $_SESSION['error_accounts']['name'] = 'Enter accounts, please!';
        } else {
            $_SESSION['error_accounts']['name'] = null;
        }
        if (empty($password)) {
            $_SESSION['error_accounts']['password'] = "Enter password, please!";
        } else {
            $_SESSION['error_accounts']['password'] = null;
        }
        if (empty($roleId)) {
            $_SESSION['error_accounts']['password'] = "Enter password, please!";
        } else {
            $_SESSION['error_accounts']['password'] = null;
        }

        $checkError = false;
        foreach ($_SESSION['error_accounts'] as $error) {
            if (!empty($error)) {
                $checkError = true;
                break;
            }
        }
        if ($checkError) {
            // co loi xay ra
            // quay lai form update
            header("Location:index.php?c=accounts&m=edit&id={$id}");
        } else {
            // khong co loi gi ca
            // tien hanh update vao database
            if (isset($_SESSION['error_accounts'])) {
                unset($_SESSION['error_accounts']);
            }
            //$password = passwordify($password);
            $update = updateAccountById(
                $name,
                $password,
                $status,
                $roleId,
                $id
            );
            if ($update) {
                // thanh cong
                header("Location:index.php?c=accounts&state=success");
            } else {
                // that bai
                header("Location:index.php?c=accounts&m=edit&id={$id}&state=failure");
            }
        }
    }
}

function handleDelete()
{
    $id = trim($_GET['id'] ?? null);
    $delete = deleteAccountById($id);
    if ($delete) {
        header("Location:index.php?c=accounts&state=delete_success");
    } else {
        header("Location:index.php?c=accounts&state=delete_failure");
    }
}

function handleAdd()
{
    if (isset($_POST['btnSave'])) {
        $name = trim($_POST['name'] ?? null);
        $name = strip_tags($name);

        $password = trim($_POST['password'] ?? null);
        $password = strip_tags($password);

        $status = trim($_POST['status'] ?? null);
        $status = $status === '0' || $status === '1' ? $status : 0;

        $roleId = $_POST['role_id'];

        // check du lieu
        $_SESSION['error_accounts'] = [];

        if (empty($name)) {
            $_SESSION['error_accounts']['name'] = 'Enter accounts, please!';
        } else {
            $_SESSION['error_accounts']['name'] = null;
        }
        if (empty($password)) {
            $_SESSION['error_accounts']['password'] = "Enter password, please!";
        } else {
            $_SESSION['error_accounts']['password'] = null;
        }

        if (
            !empty($_SESSION['error_accounts']['name'])
            ||
            !empty($_SESSION['error_accounts']['password'])
        ) {
            // co loi - thong bao ve lai form add
            header("Location:index.php?c=accounts&m=add&state=fail");
        } else {
            // insert vao database
            if (isset($_SESSION['error_accounts'])) {
                unset($_SESSION['error_accounts']);
            }
            $insert = insertAccount($name, $password, $status, $roleId);
            if ($insert) {
                header("Location:index.php?c=accounts&state=success");
            } else {
                header("Location:index.php?c=accounts&m=add&state=error");
            }
        }
    }
}

function edit()
{
    $detailName = detailNameRole();
    $id = trim($_GET['id'] ?? null);
    $id = is_numeric($id) ? $id : 0; // is_numeric : kiem tra gia tri co phai la so hay ko?
    $infoDetail = getDetailAccountById($id);
    if (!empty($infoDetail)) {
        // co du lieu trong database
        // hien thi thong tin du lieu
        require APP_PATH_VIEW . 'accounts/edit_view.php';
    } else {
        // khong co du lieu
        // thong bao loi
        require APP_PATH_VIEW . 'error_view.php';
    }
}

function Add()
{
    $detailName = detailNameRole();
    if (!empty($detailName)) {
        require APP_PATH_VIEW . 'accounts/add_view.php';
    } else {
        require APP_PATH_VIEW . 'error_view.php';
    }
}

function index(){
    $keyword = trim($_GET['search'] ?? null);
    $keyword = strip_tags($keyword);
    $page = trim($_GET['page'] ?? null);
    $page = (is_numeric($page) && $page > 0) ? $page : 1;
    $linkPage = createLink([
        'c' => 'accounts',
        'm' => 'index',
        'page' => '{page}',
        'search' => $keyword
    ]);
    $itemAccounts = getAllDataAccounts($keyword);
    $itemAccounts = count($itemAccounts);
    $pagination = pagination($linkPage, $itemAccounts, $page, LIMIT_ITEM_PAGE);
    $start = $pagination['start'] ?? 0;
    $accounts = getAllDataAccountsByPage($keyword, $start, LIMIT_ITEM_PAGE);
    $htmlPage = $pagination['htmlPage'] ?? null;
    // load view
    require APP_PATH_VIEW . 'accounts/index_view.php';
}