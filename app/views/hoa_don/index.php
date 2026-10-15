<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Điện Nước - MVC</title>
    <link rel="stylesheet" href="public/css/style.css">
    
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
    
    <style>
        .form-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; }
        .form-group label { font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px; }
        .form-group input, .form-group select { width: 100%; padding: 8px 10px; border-radius: 6px; border: 1px solid #ddd; }
        .form-group select { border: 1px solid #1565C0; font-weight: bold; color: #1565C0; background: #e3f2fd; cursor: pointer; }
        .btn-save { background: #1565C0; color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; }
        .btn-save:hover { background: #0D47A1; }
        
        /* Cập nhật style nút Excel */
        .btn-excel { background: #28a745; color: white; padding: 8px 15px; border-radius: 6px; font-size: 13px; margin-left: 10px; border: none; cursor: pointer; font-weight: bold; transition: 0.2s;}
        .btn-excel:hover { background: #218838; }
        
        .paid { color: green; font-weight: bold; background: #e8f5e9; padding: 4px 8px; border-radius: 4px; }
        .unpaid { color: red; font-weight: bold; background: #ffebee; padding: 4px 8px; border-radius: 4px; }
        
        .action a { margin-right: 15px; font-weight: 600; text-decoration: none; }
        .edit { color: #f39c12; }
        .delete { color: #e74c3c; }
        .inline-label { display: inline-block; width: auto; margin-bottom: 5px; }
    </style>
</head>
<body>

    <?php include 'app/views/layout/sidebar.php'; ?>

    <main class="main-content">
        <?php include 'app/views/layout/header.php'; ?>

        <div class="page-content">
            
            <div class="panel">
                <div class="panel-header">
                    <h3>Thông tin hóa đơn</h3>
                </div>
                <div style="padding: 20px;">
                    <form method="post" action="index.php?controller=hoadon&action=save">
                        <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Mã Hóa đơn (*)</label>
                                <input name="mahd" value="<?= $editData['ma_hd'] ?? '' ?>" required placeholder="VD: HD01">
                            </div>

                            <div class="form-group">
                                <label>Chọn Phòng (*)</label>
                                <select name="phong_id" id="phongSelect" required>
                                    <option value="">-- Đang tải danh sách phòng... --</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Tháng chốt HĐ</label>
                                <input type="month" name="thang_nam" value="<?= $editData['thang_nam'] ?? date('Y-m') ?>" required>
                            </div>
                            
                            <div class="form-group">
                                 <label>Chỉ số điện cũ</label>
                                 <input type="number" name="dien_cu" value="<?= $dien_cu_edit ?? 0 ?>" readonly style="background:#eee">
                            </div>

                           <div class="form-group">
                             <label>Chỉ số nước cũ</label>
                             <input type="number" name="nuoc_cu" value="<?= $nuoc_cu_edit ?? 0 ?>" readonly style="background:#eee">
                           </div>

                            <div class="form-group">
                                <label>Ngày lập</label>
                                <input type="date" name="ngaylap" value="<?= $editData['ngay_lap'] ?? date('Y-m-d') ?>">
                            </div>
                           
                           <div class="form-group">
                              <label style="color:#e65100">Chỉ số điện mới</label>
                              <input type="number" name="dien_moi" min="0" value="<?= $editData['dien_moi'] ?? '' ?>" required>
                           </div>

                            <div class="form-group">
                              <label style="color:#0277bd">Chỉ số nước mới</label>
                              <input type="number" name="nuoc_moi" min="0" value="<?= $editData['nuoc_moi'] ?? '' ?>" required>
                           </div>

                            <div class="form-group">
                                <label>Trạng thái thu tiền</label>
                                <select name="trangthai">
                                    <option value="0" <?= ($editData['trang_thai'] ?? 0) == 0 ? 'selected' : '' ?>>Chưa thu</option>
                                    <option value="1" <?= ($editData['trang_thai'] ?? 1) == 1 ? 'selected' : '' ?>>Đã thu</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Thành tiền Điện</label>
                                <input name="tiendien" value="<?= $editData['tien_dien'] ?? 0 ?>" readonly style="background:#eee">
                            </div>
                            
                            <div class="form-group">
                                <label>Thành tiền Nước</label>
                                <input name="tiennuoc" value="<?= $editData['tien_nuoc'] ?? 0 ?>" readonly style="background:#eee">
                            </div>
                            <div class="form-group">
                                <label style="color:red">TỔNG CỘNG HÓA ĐƠN</label>
                                <input name="tongtien" value="<?= $editData['tong_tien'] ?? 0 ?>" 
                                readonly style="background:#fff3cd; font-weight:bold; color:red; font-size:16px;">
                            </div>

                            <div class="form-group">
                                <label class="inline-label">Tiền thuê phòng</label>
                                <input name="tien_phong"  value="<?= $editData['tien_phong'] ?? 0 ?>" readonly style="background:#eee; font-weight:bold; color:#1565C0">
                            </div>

                            <div class="form-group">
                                 <label class="inline-label" >Tiền dịch vụ chung</label>
                                 <input name="tien_dich_vu" value="<?= $editData['tien_dich_vu'] ?? 0 ?>" readonly style="background:#eee; font-weight:bold; color:#1565C0">
                            </div>
                        </div>

                        <button type="submit" name="save" class="btn-save">
                            <?= isset($editData) ? 'Cập nhật Hóa đơn' : 'Lưu Hóa đơn mới' ?>
                        </button>
                        <a href="index.php?controller=hoadon&action=index" style="margin-left:10px; color:#666; text-decoration:none;">Hủy / Làm mới</a>
                    </form>
                </div>
            </div>

            <br>

            <div class="panel">
                <div class="panel-header">
                    <h3>Danh sách hóa đơn</h3>
                    <div style="display:flex; align-items:center;">
                        <form method="get" action="index.php" style="display:flex; gap:5px;">
                            <input type="hidden" name="controller" value="hoadon">
                            <input type="hidden" name="action" value="index">
                            <input type="text" name="keyword" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($keyword ?? '') ?>" style="padding:5px; border:1px solid #ddd; border-radius: 4px;">
                            <button type="submit" style="padding:5px 10px; cursor:pointer; background: #f8f9fa; border: 1px solid #ddd; border-radius: 4px;">Tìm</button>
                        </form>
                        
                        <button type="button" class="btn-excel" onclick="exportExcelHoaDon()">Xuất Excel</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Mã HD</th>
                                <th>Phòng</th>
                                <th>Tháng</th>
                                <th>Điện / Nước</th>
                                <th>Tổng tiền</th>
                                <th>Ngày lập</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($listHoaDon)): ?>
                                <?php foreach ($listHoaDon as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['ma_hd']) ?></td>
                                    <td><strong><?= htmlspecialchars($row['ma_phong'] ?? 'Chưa rõ') ?></strong></td>
                                    <td><?= htmlspecialchars($row['thang_nam']) ?></td>
                                    <td>Đ: <?= $row['so_dien'] ?> | N: <?= $row['so_nuoc'] ?></td>
                                    <td style="color:red; font-weight:bold"><?= number_format($row['tong_tien']) ?> đ</td>
                                    <td><?= date('d/m/Y', strtotime($row['ngay_lap'])) ?></td>
                                    <td>
                                        <?= $row['trang_thai'] == 1 ? "<span class='paid'>Đã thu</span>" : "<span class='unpaid'>Chưa thu</span>" ?>
                                    </td>
                                    <td class="action">
                                        <a class="edit" href="index.php?controller=hoadon&action=index&edit=<?= $row['id'] ?>">Sửa</a>
                                        <a class="delete" href="index.php?controller=hoadon&action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Xóa hóa đơn này?')">Xóa</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan='8' style='text-align:center'>Không có dữ liệu</td></tr>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>
    </main>

<script>
// ==================== LOGIC TÍNH TIỀN ĐIỆN NƯỚC ====================
const giaDien = 3500;
const giaNuoc = 15000;

const phongSelect = document.getElementById('phongSelect');
const thangInput = document.querySelector('[name="thang_nam"]');

const dienCu  = document.querySelector('[name="dien_cu"]');
const dienMoi = document.querySelector('[name="dien_moi"]');
const nuocCu  = document.querySelector('[name="nuoc_cu"]');
const nuocMoi = document.querySelector('[name="nuoc_moi"]');

const tienDien = document.querySelector('[name="tiendien"]');
const tienNuoc = document.querySelector('[name="tiennuoc"]');
const tongTien = document.querySelector('[name="tongtien"]');

const tienPhongInput = document.querySelector('[name="tien_phong"]');
const tienDVInput = document.querySelector('[name="tien_dich_vu"]');

async function loadRooms() {
    try {
        const res = await fetch('api/phong_api.php?action=get_all');
        const rooms = await res.json();
        const currentRoomId = "<?= $editData['phong_id'] ?? '' ?>";
        
        let html = '<option value="">-- Hãy chọn phòng để tính tiền --</option>';
        rooms.forEach(r => {
            const selected = (currentRoomId == r.id) ? 'selected' : '';
            html += `<option value="${r.id}" ${selected}>${r.ma_phong} (Phòng ${r.loai_phong})</option>`;
        });
        phongSelect.innerHTML = html;

        if (!dienCu.value || dienCu.value === "0") {
            layChiSoCu();
            layTienPhongVaDV();
        }
    } catch(e) { console.error("Lỗi load phòng"); }
}

function tinhTien() {
    const dCu = parseInt(dienCu.value) || 0;
    const dMoi = parseInt(dienMoi.value) || 0;
    const nCu = parseInt(nuocCu.value) || 0;
    const nMoi = parseInt(nuocMoi.value) || 0;

    if (dMoi < dCu || nMoi < nCu) return;

    const soDien = dMoi - dCu;
    const soNuoc = nMoi - nCu;

    const td = soDien * giaDien;
    const tn = soNuoc * giaNuoc;

    const tp  = parseInt(tienPhongInput.value) || 0;
    const tdv = parseInt(tienDVInput.value) || 0;

    tienDien.value = td;
    tienNuoc.value = tn;
    tongTien.value = td + tn + tp + tdv;
}

function layChiSoCu() {
    const phong_id = phongSelect.value;
    const thang = thangInput.value;

    if (!phong_id || !thang) return;

    fetch(`api/dien_nuoc_api.php?action=get_chi_so_cu&phong_id=${phong_id}&thang=${thang}`)
        .then(res => res.json())
        .then(data => {
            dienCu.value = data.dien_cu || 0;
            nuocCu.value = data.nuoc_cu || 0;
            tinhTien(); 
        })
        .catch(() => {
            dienCu.value = 0;
            nuocCu.value = 0;
            tinhTien();
        });
}

function layTienPhongVaDV() {
    const phong_id = phongSelect.value;
    if (!phong_id) {
        tienPhongInput.value = 0;
        tienDVInput.value = 0;
        tinhTien();
        return;
    }

    fetch(`api/dien_nuoc_api.php?action=get_tien_phong_dv&phong_id=${phong_id}`)
        .then(res => res.json())
        .then(data => {
            tienPhongInput.value = data.tien_phong || 0;
            tienDVInput.value = data.tien_dich_vu || 0;
            tinhTien(); 
        });
}

phongSelect.addEventListener('change', () => { layChiSoCu(); layTienPhongVaDV(); });
thangInput.addEventListener('change', layChiSoCu);
dienMoi.addEventListener('input', tinhTien);
nuocMoi.addEventListener('input', tinhTien);
document.addEventListener('DOMContentLoaded', loadRooms);


// ==================== LOGIC XUẤT EXCEL ====================
function exportExcelHoaDon() {
    try {
        // Lấy trực tiếp mảng dữ liệu đã được PHP đổ ra sẵn
        const rawData = <?= json_encode($listHoaDon ?? []) ?>;
        
        if (!rawData || rawData.length === 0) {
            alert("Không có dữ liệu hóa đơn nào để xuất!");
            return;
        }

        // Tạo mảng dữ liệu mới với tiêu đề cột tiếng Việt rõ ràng
        const rows = rawData.map((r, i) => ({
            'STT': i + 1,
            'Mã Hóa Đơn': r.ma_hd,
            'Phòng': r.ma_phong || 'Chưa rõ',
            'Tháng/Năm': r.thang_nam,
            'Số Điện': parseInt(r.so_dien) || 0,
            'Số Nước': parseInt(r.so_nuoc) || 0,
            'Tiền Điện (VNĐ)': parseInt(r.tien_dien) || 0,
            'Tiền Nước (VNĐ)': parseInt(r.tien_nuoc) || 0,
            'Tiền Phòng (VNĐ)': parseInt(r.tien_phong) || 0,
            'Tiền Dịch Vụ (VNĐ)': parseInt(r.tien_dich_vu) || 0,
            'Tổng tiền (VNĐ)': parseInt(r.tong_tien) || 0,
            'Trạng thái': r.trang_thai == 1 ? 'Đã thu' : 'Chưa thu',
            'Ngày lập': r.ngay_lap
        }));

        // Gọi thư viện SheetJS để tạo file
        const ws = XLSX.utils.json_to_sheet(rows);
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, 'Danh sách Hóa đơn');
        
        // Tải xuống file với tên có chứa ngày tháng hiện tại
        const fileName = `HoaDon_KTX_${new Date().toLocaleDateString('vi-VN').replace(/\//g,'-')}.xlsx`;
        XLSX.writeFile(wb, fileName);
        
    } catch (error) {
        alert("Có lỗi xảy ra trong quá trình tạo file Excel!");
        console.error(error);
    }
}
</script>

</body>
</html>