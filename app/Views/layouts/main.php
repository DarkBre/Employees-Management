<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
        <link href="/assets/css/styles.css" rel="stylesheet" />
        <style>
            .sb-nav-fixed #layoutSidenav #layoutSidenav_content {
                padding-left: 0;
            }
        </style>
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="/">Nhân Sự</a>
            <div class="ms-auto me-3 text-white-50 small d-none d-md-block">
                Xin chào, <?= htmlspecialchars($user['name'] ?? 'Quản trị viên') ?>
            </div>
            <form action="/logout" method="post" class="me-3">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <button class="btn btn-outline-light btn-sm" type="submit">
                    <i class="fas fa-sign-out-alt me-1"></i>
                    Đăng xuất
                </button>
            </form>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_content">
                <main>
                    <?= $content ?>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted"><?= APP_NAME ?></div>
                            <div class="text-muted">PHP MVC</div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    </body>
</html>
