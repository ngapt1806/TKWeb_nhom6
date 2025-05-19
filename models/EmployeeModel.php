<?php
class EmployeeModel  extends BaseModel {
    const ROLE = 'employee';

    protected $connection = null;

    public function __construct() {
        $this->connection = $this->connect();
    }

    // XÓA hoặc COMMENT hàm _query vì không dùng nữa
    /*
    private function _query($sql){
        return mysqli_query($this->connection, $sql);
    }
    */

    public function checkPhoneExists($phone): bool {
        $sql = "SELECT COUNT(*) as count FROM employees WHERE phone = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$phone]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] > 0;
    }

    public function getById($id): array
    {
        $sql = "SELECT e.employee_id AS id,
                        e.name AS name,
                        e.avt AS avt,
                        e.gender AS gender,
                        e.dob AS dob,
                        e.email AS email,
                        e.phone AS phone,
                        e.address AS address,
                        e.status AS status,
                        e.employee_code AS employee_code,
                        p.name AS position_name,
                        s.name AS specialty_name
                FROM employees AS e
                LEFT JOIN services AS s ON e.service_id = s.service_id
                LEFT JOIN positions AS p ON e.position_id = p.position_id
                WHERE e.employee_id = ?";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function getEmployeeForAdmin(): array
    {
        $sql = "SELECT
                    e.employee_id AS id,
                    e.avt AS avt,
                    e.name AS name,
                    p.position_id AS position_id,
                    p.name AS position,
                    e.email AS email,
                    e.phone AS phone,
                    e.address AS address,
                    e.gender AS gender,
                    e.dob AS dob,
                    e.employee_code AS employee_code,
                    e.status AS status
                FROM employees AS e
                         JOIN positions AS p ON e.position_id = p.position_id
                         JOIN roles AS r ON e.role_id = r.role_id
                WHERE r.role_name = LOWER('employee') ORDER BY e.employee_id DESC";
        $stmt = $this->connection->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateEmployee($employee_id, $name, $dob, $email, $phone, $gender, $address, $status, $avt, $update_by): array
    {
        // Kiểm tra số điện thoại đã tồn tại chưa và không thuộc về nhân viên hiện tại
        $currentPhone = $this->getById($employee_id)['phone'];
        if ($phone !== $currentPhone && $this->checkPhoneExists($phone)) {
            return [
                'success' => false,
                'message' => 'Số điện thoại đã tồn tại trong hệ thống.'
            ];
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $updated_at = date("Y-m-d H:i:s");

        $sql = "UPDATE employees SET
            name = ?,
            phone = ?,
            email = ?,
            dob = ?,
            gender = ?,
            address = ?, 
            status = ?,
            update_at = ?,
            avt = ?,
            update_by = ?
        WHERE employee_id = ?";

        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute([
            $name, $phone, $email, $dob, $gender,
            $address, $status, $updated_at, $avt, $update_by, $employee_id
        ]);

        if (!$result) {
            return [
                'success' => false,
                'message' => 'Cập nhật thất bại: ' . implode(', ', $stmt->errorInfo())
            ];
        }

        return [
            'success' => true,
            'message' => 'Thông tin nhân viên đã được cập nhật thành công.'
        ];
    }

    public function addEmployee($name, $dob, $email, $phone, $gender, $address, $position_id, $status, $avt, $update_by): array
    {
        // Kiểm tra số điện thoại đã tồn tại chưa
        if ($this->checkPhoneExists($phone)) {
            return [
                'success' => false,
                'message' => 'Số điện thoại đã tồn tại trong hệ thống.'
            ];
        }

        $created_at = date("Y-m-d H:i:s");
        $hashedPassword = password_hash('Abc12345', PASSWORD_BCRYPT, ['cost' => 12]);
        $role_id = 2;

        $sql = "INSERT INTO employees (
               position_id,
               role_id,
               name,
               password,
               phone,
               email,
               dob,
               gender,
               address, 
               status,
               create_at,
               avt,
               update_by)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->connection->prepare($sql);
        $result = $stmt->execute([
            $position_id, $role_id, $name, $hashedPassword, $phone, $email, $dob, $gender,
            $address, $status, $created_at, $avt, $update_by
        ]);

        if (!$result) {
            return [
                'success' => false,
                'message' => 'Thêm nhân viên thất bại: ' . implode(', ', $stmt->errorInfo())
            ];
        }

        // Lấy ID của nhân viên vừa được thêm
        $employee_id = $this->connection->lastInsertId();

        // Bước 2: Tạo employee_code và cập nhật
        $employee_code = 'EMP' . $employee_id;

        $sqlUpdate = "UPDATE employees SET employee_code = ? WHERE employee_id = ?";
        $stmtUpdate = $this->connection->prepare($sqlUpdate);
        $resultUpdate = $stmtUpdate->execute([$employee_code, $employee_id]);

        if (!$resultUpdate) {
            return [
                'success' => false,
                'message' => 'Cập nhật mã nhân viên thất bại: ' . implode(', ', $stmtUpdate->errorInfo())
            ];
        }

        return [
            'success' => true,
            'message' => 'Nhân viên mới đã được thêm thành công.'
        ];
    }
}