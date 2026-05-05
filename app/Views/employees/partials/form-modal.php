<div class="modal fade" id="<?= htmlspecialchars($modalId) ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= htmlspecialchars($action) ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><?= htmlspecialchars($modalTitle) ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <?php if ($action === '/employees/update'): ?>
                        <input type="hidden" name="id" value="">
                    <?php endif; ?>
                    <div class="mb-3">
                        <label class="form-label">Tên nhân viên</label>
                        <input class="form-control" name="name" type="text" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chức vụ</label>
                        <input class="form-control" name="position" type="text" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Văn phòng</label>
                        <input class="form-control" name="office" type="text" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tuổi</label>
                        <input class="form-control" name="age" type="number" min="18" max="80" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input class="form-control" name="start_date" type="date" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lương</label>
                        <input class="form-control" name="salary" type="text" placeholder="12.000.000" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary"><?= htmlspecialchars($submitText) ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
