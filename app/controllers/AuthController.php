<?php
require_once 'app/models/UserModel.php';

class AuthController {
    private $model;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->model = new UserModel($this->db);
    }

   // Màn hình đăng nhập
    public function login() {
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=home");
            exit();
        }

        $error = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = $this->model->login($username, $password);

            if ($user) {
                // 1. LƯU THÔNG TIN CƠ BẢN VÀ QUYỀN (Rất quan trọng)
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['vai_tro'] = $user['vai_tro']; 
                
                // 2. PHÂN LOẠI QUYỀN
                if ($user['vai_tro'] === 'sinh_vien') {
                    $ma_sv = $user['ma_sv_lien_ket'];
                    $_SESSION['ma_sv'] = $ma_sv;
                    
                    $rs = $this->db->query("SELECT phong_id, ho_ten FROM sinh_vien WHERE ma_sv = '$ma_sv'");
                    if ($rs && $rs->num_rows > 0) {
                        $sv_info = $rs->fetch_assoc();
                        $_SESSION['phong_id_cua_sv'] = $sv_info['phong_id'];
                        $_SESSION['fullname'] = $sv_info['ho_ten'];
                    } else {
                        $_SESSION['fullname'] = "Sinh viên";
                        $_SESSION['phong_id_cua_sv'] = null;
                    }
                } else {
                    // Dành cho Admin và Nhân viên
                    $_SESSION['fullname'] = $user['ho_ten']; 
                }
                
                header("Location: index.php?controller=home");
                exit();
            } else {
                $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
            }
        }

        require_once 'app/views/auth/login.php';
    }
    // Xử lý đổi mật khẩu
    public function changePassword() {
        // Nếu chưa đăng nhập thì không cho vào
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }

        $error = "";
        $success = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $old_pass = $_POST['old_password'];
            $new_pass = $_POST['new_password'];
            $confirm_pass = $_POST['confirm_password'];

            if ($new_pass !== $confirm_pass) {
                $error = "Mật khẩu xác nhận không khớp!";
            } else if (strlen($new_pass) < 6) {
                $error = "Mật khẩu mới phải có ít nhất 6 ký tự!";
            } else {
                // Gọi Model để xử lý
                $result = $this->model->changePassword($_SESSION['user_id'], $old_pass, $new_pass);
                if ($result) {
                    $success = "Đổi mật khẩu thành công! Hãy ghi nhớ mật khẩu mới.";
                } else {
                    $error = "Mật khẩu cũ không chính xác!";
                }
            }
        }

        // Gọi giao diện
        require_once 'app/views/auth/change_password.php';
    }

    // Xử lý đăng xuất
    public function logout() {
        session_unset();
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
    // Xử lý Quên mật khẩu
    public function forgot() {
        $error = "";
        $success = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $new_password = $_POST['new_password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($email) || empty($new_password) || empty($confirm_password)) {
                $error = "Vui lòng nhập đầy đủ thông tin!";
            } else if ($new_password !== $confirm_password) {
                $error = "Mật khẩu xác nhận không khớp!";
            } else if (strlen($new_password) < 6) {
                $error = "Mật khẩu mới phải có ít nhất 6 ký tự!";
            } else {
                // Kiểm tra xem tên đăng nhập và email có đúng là của 1 người không
                if ($this->model->checkUserAndEmail($username, $email)) {
                    // Cập nhật pass mới
                    if ($this->model->updatePasswordByUsernameAndEmail($username, $email, $new_password)) {
                        $success = "Đặt lại mật khẩu thành công! Hãy quay lại trang Đăng nhập.";
                    } else {
                        $error = "Lỗi hệ thống khi cập nhật mật khẩu.";
                    }
                } else {
                    $error = "Tên đăng nhập hoặc Email không đúng!";
                }
            }
        }

        require_once 'app/views/auth/forgot.php';
    }
}
?>