<!DOCTYPE html>
<html lang="en">
<head>
    <?
        require_once 'home/meta.php';
        require_once 'home/css.php';
    ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        <?
            require_once 'home/header.php';
            require_once 'home/sidebar.php';
            require_once $changeView['main'].'.php';
            require_once 'home/footer.php';
        ?>
    </div>
    <!-- ./wrapper -->
    <?
        require_once 'home/js.php';
    ?>
</body>
</html>
