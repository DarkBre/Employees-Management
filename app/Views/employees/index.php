<div class="container-fluid px-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mt-4 mb-4">
        <div>
            <h1 class="mb-1">Quản lí nhân viên</h1>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item active">Thêm, sửa và xoá thông tin nhân viên</li>
            </ol>
        </div>
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#createEmployeeModal">
            <i class="fas fa-plus me-1"></i>
            Thêm nhân viên
        </button>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Danh sách nhân viên
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên nhân viên</th>
                        <th>Chức vụ</th>
                        <th>Văn phòng</th>
                        <th>Tuổi</th>
                        <th>Ngày bắt đầu</th>
                        <th>Lương</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                        <tr>
                            <td colspan="8" class="text-center">Chưa có dữ liệu</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?= (int) $employee['id'] ?></td>
                            <td><?= htmlspecialchars($employee['name']) ?></td>
                            <td><?= htmlspecialchars($employee['position']) ?></td>
                            <td><?= htmlspecialchars($employee['office']) ?></td>
                            <td><?= (int) $employee['age'] ?></td>
                            <td><?= htmlspecialchars($employee['start_date']) ?></td>
                            <td><?= htmlspecialchars($employee['salary']) ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <button
                                        class="btn btn-warning btn-sm"
                                        type="button"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editEmployeeModal"
                                        data-id="<?= (int) $employee['id'] ?>"
                                        data-name="<?= htmlspecialchars($employee['name']) ?>"
                                        data-position="<?= htmlspecialchars($employee['position']) ?>"
                                        data-office="<?= htmlspecialchars($employee['office']) ?>"
                                        data-age="<?= (int) $employee['age'] ?>"
                                        data-start-date="<?= htmlspecialchars($employee['start_date']) ?>"
                                        data-salary="<?= htmlspecialchars($employee['salary']) ?>"
                                    >
                                        <i class="fas fa-edit"></i>
                                        Sửa
                                    </button>
                                    <form action="/employees/delete" method="post" onsubmit="return confirm('Bạn có chắc chắn muốn xoá nhân viên này?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                                        <input type="hidden" name="id" value="<?= (int) $employee['id'] ?>">
                                        <button class="btn btn-danger btn-sm" type="submit">
                                            <i class="fas fa-trash"></i>
                                            Xoá
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $action = '/employees/store'; $modalId = 'createEmployeeModal'; $modalTitle = 'Thêm nhân viên'; $submitText = 'Lưu'; ?>
<?php require APP_PATH . '/Views/employees/partials/form-modal.php'; ?>

<?php $action = '/employees/update'; $modalId = 'editEmployeeModal'; $modalTitle = 'Sửa nhân viên'; $submitText = 'Cập nhật'; ?>
<?php require APP_PATH . '/Views/employees/partials/form-modal.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const editModal = document.getElementById('editEmployeeModal');
        editModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            editModal.querySelector('[name="id"]').value = button.dataset.id;
            editModal.querySelector('[name="name"]').value = button.dataset.name;
            editModal.querySelector('[name="position"]').value = button.dataset.position;
            editModal.querySelector('[name="office"]').value = button.dataset.office;
            editModal.querySelector('[name="age"]').value = button.dataset.age;
            editModal.querySelector('[name="start_date"]').value = button.dataset.startDate;
            editModal.querySelector('[name="salary"]').value = button.dataset.salary;
        });
    });
</script>
