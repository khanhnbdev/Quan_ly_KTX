<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu - UTT KTX</title>
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .panel { background: white; padding: 40px 30px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 100%; max-width: 400px; }
        .panel h2 { color: #1565C0; text-align: center; margin-top: 0; margin-bottom: 10px; font-size: 24px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 8px; color: #444; font-size: 14px; }
        .form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; outline: none; transition: 0.3s; }
        .form-group input:focus { border-color: #1565C0; }
        .btn { width: 100%; background: #1565C0; color: white; border: none; padding: 14px; border-radius: 6px; font-weight: bold; font-size: 15px; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .btn:hover { background: #0D47A1; }
        .msg { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: bold; text-align: center; font-size: 14px; }
        .error { background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; }
        .success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #E65100; text-decoration: none; font-weight: 600; font-size: 14px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="panel">
        <h2>Khôi phục mật khẩu</h2>
        <p style="text-align: center; color: #666; font-size: 14px; margin-bottom: 25px; line-height: 1.5;">
            Xác nhận tài khoản và email để đặt lại mật khẩu mới.
        </p>

        <?php if(!empty($error)): ?>
            <div class="msg error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if(!empty($success)): ?>
            <div class="msg success"><?php echo $success; ?></div>
        <?php else: ?>
            <form method="POST" action="index.php?controller=auth&action=forgot">
                <div class="form-group">
                    <label>Tên đăng nhập (*)</label>
                    <input type="text" name="username" required placeholder="">
                </div>
                <div class="form-group">
                    <label>Email trên hệ thống (*)</label>
                    <input type="email" name="email" required placeholder="">
                </div>
                <div class="form-group">
                    <label>Mật khẩu mới (*)</label>
                    <input type="password" name="new_password" required minlength="6" placeholder="Nhập ít nhất 6 ký tự">
                </div>
                <div class="form-group">
                    <label>Xác nhận mật khẩu mới (*)</label>
                    <input type="password" name="confirm_password" required minlength="6" placeholder="Nhập lại mật khẩu mới">
                </div>
                <button type="submit" class="btn">Lưu mật khẩu mới</button>
            </form>
        <?php endif; ?>

        <a href="index.php?controller=auth&action=login" class="back-link">← Trở về trang Đăng nhập</a>
    </div>
</body>
</html>