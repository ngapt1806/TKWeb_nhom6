<?php
// Thiết lập thời gian tồn tại của cookie và session
session_set_cookie_params(86400);
ini_set('session.gc_maxlifetime', 86400);

// Khởi động session an toàn hơn
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'BaseModel.php'; // Đảm bảo tệp này tồn tại và định nghĩa lớp BaseModel

class AuthModel extends BaseModel {
    const TABLE_NAME = "customers"; // Dùng cho registerClient, loginClient, changePasswordClient, forgotPasswordClient
    const TABLE_EMPLOYEES = "employees"; // Dùng cho loginAdmin, changePasswordAdmin

    protected $connection = null;

    public function __construct() {
        $this->connection = $this->connect(); // Giả định $this->connect() trả về đối tượng PDO
    }

    // Hàm _query() không còn cần thiết và nên được loại bỏ vì nó sử dụng mysqli_*
    /*
    private function _query($sql){
        return mysqli_query($this->connection, $sql);
    }
    */

    public function registerClient($phone, $password, $name): array
    {
        try {
            $sql_check_phone = "SELECT phone FROM " . self::TABLE_NAME . " WHERE phone = :phone";
            $stmt_check_phone = $this->connection->prepare($sql_check_phone);
            $stmt_check_phone->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt_check_phone->execute();

            if ($stmt_check_phone->rowCount() > 0) {
                return ['success' => false, 'message' => 'Số điện thoại đã được đăng ký'];
            }

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
            $sql_insert_user = "INSERT INTO " . self::TABLE_NAME . " (phone, password, name, status) VALUES (:phone, :password, :name, 1)";
            $stmt_insert_user = $this->connection->prepare($sql_insert_user);
            $stmt_insert_user->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt_insert_user->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt_insert_user->bindParam(':name', $name, PDO::PARAM_STR);

            if ($stmt_insert_user->execute()) {
                return ['success' => true, 'message' => 'Đăng ký thành công'];
            } else {
                $errorInfo = $stmt_insert_user->errorInfo();
                return ['success' => false, 'message' => 'Đăng ký không thành công: ' . ($errorInfo[2] ?? 'Lỗi không xác định từ DB')];
            }
        } catch (PDOException $e) {
            error_log("Lỗi PDO khi đăng ký client: " . $e->getMessage());
            return ['success' => false, 'message' => 'Đã có lỗi cơ sở dữ liệu khi đăng ký. Vui lòng thử lại sau.'];
        }
    }

    public function loginClient($phone, $password): array
    {
        try {
            $sql = "SELECT * FROM " . self::TABLE_NAME . " WHERE phone = :phone";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $patient = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $patient['password'])) {
                    if ($patient['status'] == 1) {
                        // Khởi động session nếu chưa (mặc dù đã có ở đầu file, nhưng để chắc chắn)
                        if (session_status() == PHP_SESSION_NONE) session_start();
                        $_SESSION['patient_id'] = $patient['patient_id'];
                        $_SESSION['user_name'] = $patient['name'];
                        $_SESSION['user_phone'] = $phone;
                        return [
                            'success' => true,
                            'sessionData' => ['user_phone' => $_SESSION['user_phone']]
                        ];
                    } else {
                        return ['success' => false, 'message' => 'Tài khoản bị khóa hoặc chưa được kích hoạt.'];
                    }
                } else {
                    return ['success' => false, 'message' => 'Mật khẩu không đúng'];
                }
            }
            return ['success' => false, 'message' => 'Không tìm thấy tài khoản với số điện thoại này'];
        } catch (PDOException $e) {
            error_log("Lỗi PDO khi đăng nhập client: " . $e->getMessage());
            return ['success' => false, 'message' => 'Đã có lỗi cơ sở dữ liệu khi đăng nhập. Vui lòng thử lại sau.'];
        }
    }

    public function loginAdmin($phone, $password): array
    {
        try {
            $sql = "SELECT * FROM " . self::TABLE_EMPLOYEES . " WHERE phone = :phone";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($admin['status'] == 0) { // Giả sử status 0 là tài khoản bị khóa
                    return ['success' => false, 'message' => 'Tài khoản bị khóa. Hãy liên hệ với quản trị viên của bạn'];
                }
                // QUAN TRỌNG: Kiểm tra mật khẩu admin.
                // Nếu mật khẩu admin được hash bằng password_hash:
                // if (password_verify($password, $admin['password'])) {
                // Nếu mật khẩu admin là plaintext (không khuyến khích):
                if ($password === $admin['password']) {
                    if (session_status() == PHP_SESSION_NONE) session_start();
                    $_SESSION['admin_phone'] = $admin['phone'];
                    $_SESSION['role_id'] = $admin['role_id'];
                    $_SESSION['admin_name'] = $admin['name'];
                    $_SESSION['admin_id'] = $admin['employee_id'];
                    $_SESSION['admin_avt'] = $admin['avt']; //Đảm bảo cột avt tồn tại trong bảng employees
                    return [
                        'success' => true,
                        'sessionData' => [
                            'admin_phone' => $_SESSION['admin_phone'],
                            'role_id' => $_SESSION['role_id'],
                            'admin_name' => $_SESSION['admin_name']
                        ]
                    ];
                } else {
                    return ['success' => false, 'message' => 'Mật khẩu không đúng'];
                }
            }
            return ['success' => false, 'message' => 'Không tìm thấy tài khoản quản trị viên'];
        } catch (PDOException $e) {
            error_log("Lỗi PDO khi đăng nhập admin: " . $e->getMessage());
            return ['success' => false, 'message' => 'Đã có lỗi cơ sở dữ liệu khi đăng nhập quản trị. Vui lòng thử lại sau.'];
        }
    }

    public function changePasswordAdmin($adminId, $currentPassword, $newPassword): array
    {
        try {
            $sql_select = "SELECT password FROM " . self::TABLE_EMPLOYEES . " WHERE employee_id = :admin_id";
            $stmt_select = $this->connection->prepare($sql_select);
            $stmt_select->bindParam(':admin_id', $adminId, PDO::PARAM_INT);
            $stmt_select->execute();

            if ($stmt_select->rowCount() > 0) {
                $admin = $stmt_select->fetch(PDO::FETCH_ASSOC);

                // QUAN TRỌNG: Kiểm tra mật khẩu admin hiện tại.
                // if (password_verify($currentPassword, $admin['password'])) { // Nếu đã hash
                if ($currentPassword === $admin['password']) { // Nếu là plaintext
                    // QUAN TRỌNG: Hash mật khẩu mới nếu cần
                    // $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT);
                    // $passwordToUpdate = $newPasswordHash;
                    $passwordToUpdate = $newPassword; // Nếu lưu plaintext

                    $sql_update = "UPDATE " . self::TABLE_EMPLOYEES . " SET password = :new_password WHERE employee_id = :admin_id";
                    $stmt_update = $this->connection->prepare($sql_update);
                    $stmt_update->bindParam(':new_password', $passwordToUpdate, PDO::PARAM_STR);
                    $stmt_update->bindParam(':admin_id', $adminId, PDO::PARAM_INT);

                    if ($stmt_update->execute()) {
                        return ['success' => true, 'message' => 'Mật khẩu quản trị đã được thay đổi thành công'];
                    } else {
                        $errorInfo = $stmt_update->errorInfo();
                        return ['success' => false, 'message' => 'Lỗi khi cập nhật mật khẩu quản trị: ' . ($errorInfo[2] ?? 'Lỗi DB không xác định')];
                    }
                } else {
                    return ['success' => false, 'message' => 'Mật khẩu quản trị hiện tại không đúng'];
                }
            }
            return ['success' => false, 'message' => 'Không tìm thấy tài khoản quản trị viên'];
        } catch (PDOException $e) {
            error_log("Lỗi PDO khi đổi mật khẩu admin: " . $e->getMessage());
            return ['success' => false, 'message' => 'Đã có lỗi cơ sở dữ liệu khi đổi mật khẩu quản trị. Vui lòng thử lại sau.'];
        }
    }

    public function changePasswordClient($patientId, $currentPassword, $newPassword): array
    {
        try {
            $sql_select = "SELECT password FROM " . self::TABLE_NAME . " WHERE patient_id = :patient_id";
            $stmt_select = $this->connection->prepare($sql_select);
            $stmt_select->bindParam(':patient_id', $patientId, PDO::PARAM_INT);
            $stmt_select->execute();

            if ($stmt_select->rowCount() > 0) {
                $patient = $stmt_select->fetch(PDO::FETCH_ASSOC);
                if (password_verify($currentPassword, $patient['password'])) {
                    $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
                    $sql_update = "UPDATE " . self::TABLE_NAME . " SET password = :new_password WHERE patient_id = :patient_id";
                    $stmt_update = $this->connection->prepare($sql_update);
                    $stmt_update->bindParam(':new_password', $newPasswordHash, PDO::PARAM_STR);
                    $stmt_update->bindParam(':patient_id', $patientId, PDO::PARAM_INT);

                    if ($stmt_update->execute()) {
                        return ['success' => true, 'message' => 'Mật khẩu đã được thay đổi thành công'];
                    } else {
                        $errorInfo = $stmt_update->errorInfo();
                        return ['success' => false, 'message' => 'Lỗi khi cập nhật mật khẩu: ' . ($errorInfo[2] ?? 'Lỗi DB không xác định')];
                    }
                } else {
                    return ['success' => false, 'message' => 'Mật khẩu hiện tại không đúng'];
                }
            }
            return ['success' => false, 'message' => 'Không tìm thấy tài khoản'];
        } catch (PDOException $e) {
            error_log("Lỗi PDO khi đổi mật khẩu client: " . $e->getMessage());
            return ['success' => false, 'message' => 'Đã có lỗi cơ sở dữ liệu khi đổi mật khẩu. Vui lòng thử lại sau.'];
        }
    }

    public function forgotPasswordClient($phone, $newPassword): array
    {
        try {
            $sql_check = "SELECT patient_id FROM " . self::TABLE_NAME . " WHERE phone = :phone";
            $stmt_check = $this->connection->prepare($sql_check);
            $stmt_check->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                $newPasswordHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);
                $sql_update = "UPDATE " . self::TABLE_NAME . " SET password = :new_password WHERE phone = :phone";
                $stmt_update = $this->connection->prepare($sql_update);
                $stmt_update->bindParam(':new_password', $newPasswordHash, PDO::PARAM_STR);
                $stmt_update->bindParam(':phone', $phone, PDO::PARAM_STR);

                if ($stmt_update->execute()) {
                    return ['success' => true, 'message' => 'Mật khẩu đã được đặt lại thành công'];
                } else {
                    $errorInfo = $stmt_update->errorInfo();
                    return ['success' => false, 'message' => 'Lỗi khi đặt lại mật khẩu: ' . ($errorInfo[2] ?? 'Lỗi DB không xác định')];
                }
            } else {
                 return ['success' => false, 'message' => 'Không tìm thấy tài khoản với số điện thoại này.'];
            }
        } catch (PDOException $e) {
            error_log("Lỗi PDO khi quên mật khẩu client: " . $e->getMessage());
            return ['success' => false, 'message' => 'Đã có lỗi cơ sở dữ liệu khi đặt lại mật khẩu. Vui lòng thử lại sau.'];
        }
    }

    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        // Việc chuyển hướng nên do Controller xử lý sau khi gọi logout()
        // Ví dụ: AuthController->logout() { $this->authModel->logout(); header('Location: ...'); exit(); }
    }
}