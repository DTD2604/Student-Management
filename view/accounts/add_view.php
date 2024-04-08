<?php
if (!defined('APP_ROOT_PATH')) {
    die('Can not access');
}

$namePage = 'Create Accounts';
$errorAdd = $_SESSION['error_accounts'] ?? null;
?>
<link rel="shortcut icon" href="public/img/images (1).png" type="x-icon">
<!-- load header view -->
<?php require APP_PATH_VIEW . "partials/header_view.php"; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Create Accounts</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a style="color: green;" href="index.php?c=account">Accounts</a></li>
                        <li class="breadcrumb-item active">Form Add new</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h5 class="card-title">
                                Create Accounts
                            </h5>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data" method="post" action="index.php?c=account&m=handle-add">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label>User Name</label>
                                            <input type="text" class="form-control" name="name" />
                                            <?php if(!empty($errorAdd['name'])): ?>
                                                <span class="text-danger"><?= $errorAdd['name'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label>Password</label>
                                            <input type="text" class="form-control" name="password" />
                                            <?php if(!empty($errorAdd['password'])): ?>
                                                <span class="text-danger"><?= $errorAdd['password'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="form-group mb-3">
                                            <label> Role </label>
                                            <select id="" class="form-control" name="role_id" title="role_id">
                                                <?php foreach($detailName as $key => $item): ?>
                                                    <option value="<?php echo $item['id'];?>"><?php echo $item['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label> Status </label>
                                            <select class="form-control" name="status">
                                                <option value="1">Active</option>
                                                <option value="0">Deactive</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-md-6">
                                        <button class="btn btn-warning btn-lg" type="submit" name="btnSave"> Save </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- load footer view -->
<?php require APP_PATH_VIEW . "partials/footer_view.php"; ?>