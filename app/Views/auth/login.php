<div class="container-fluid px-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mt-4 mb-4">
        <div>
            <h1 class="mb-1">Đăng nhập</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Truy cập hệ thống quản lí nhân viên</li>
            </ol>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-sign-in-alt me-1"></i>
            Thông tin đăng nhập
        </div>
        <div class="card-body">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form action="/login" method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" for="inputEmail">Email</label>
                        <input class="form-control" id="inputEmail" name="email" type="email" placeholder="email@example.com" required />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="inputPassword">Mật khẩu</label>
                        <input class="form-control" id="inputPassword" name="password" type="password" placeholder="Nhập mật khẩu" required />
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-end gap-2 mt-4">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-sign-in-alt me-1"></i>
                        Đăng nhập
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
