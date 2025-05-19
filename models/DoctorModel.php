<?php
require_once 'configs/cloudinaryConfig.php';
class DoctorModel  extends BaseModel {

    /**
     * Check if a phone number already exists in the database.
     *
     * @param string $phone
     * @return bool
     */
    public function checkPhoneExists($phone): bool
    {
        $sql = "SELECT COUNT(*) as count FROM employees WHERE phone = ?";
        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            throw new Exception('MySQL prepare error: ' . implode(' ', $this->connection->errorInfo()));
        }

        $stmt->bindParam(1, $phone, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new Exception('Failed to execute statement: ' . implode(' ', $stmt->errorInfo()));
        }

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }
    const ROLE = 'doctor';

    protected $connection = null;

    public function __construct() {
        $this->connection = $this->connect();
    }

    private function _query($sql){
        $stmt = $this->connection->query($sql);
        return $stmt;
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
                       e.service_id AS service_id,
                       p.name AS positionName,
                       e.employee_code AS employee_code
                FROM employees AS e
                JOIN positions AS p ON e.position_id = p.position_id
                WHERE e.employee_id = $id";

        $query = $this->_query($sql);
        if ($query === false) {
            throw new Exception('Query failed: ' . implode(' ', $this->connection->errorInfo()));
        }
        return $query ? $query->fetch(PDO::FETCH_ASSOC) : null;
    }

    /**
     * @throws Exception
     */
    public function updateDoctor($doctor_id, $name, $dob, $email, $phone, $gender, $address, $service_id, $status, $avt, $update_by)
    {
        // Kiểm tra số điện thoại đã tồn tại chưa và không thuộc về nhân viên hiện tại
        $currentPhone = $this->getById($doctor_id)['phone'];
        if ($phone !== $currentPhone && $this->checkPhoneExists($phone)) {
            return [
                'success' => false,
                'message' => 'Số điện thoại đã tồn tại trong hệ thống.'
            ];
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $updated_at = date("Y-m-d H:i:s");

        $sql = "UPDATE employees SET
                service_id = ?,
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
        if ($stmt === false) {
            throw new Exception('MySQL prepare error: ' . implode(' ', $this->connection->errorInfo()));
        }

        $stmt->bindParam(1, $service_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $name, PDO::PARAM_STR);
        $stmt->bindParam(3, $phone, PDO::PARAM_STR);
        $stmt->bindParam(4, $email, PDO::PARAM_STR);
        $stmt->bindParam(5, $dob, PDO::PARAM_STR);
        $stmt->bindParam(6, $gender, PDO::PARAM_INT);
        $stmt->bindParam(7, $address, PDO::PARAM_STR);
        $stmt->bindParam(8, $status, PDO::PARAM_INT);
        $stmt->bindParam(9, $updated_at, PDO::PARAM_STR);
        $stmt->bindParam(10, $avt, PDO::PARAM_STR);
        $stmt->bindParam(11, $update_by, PDO::PARAM_INT);
        $stmt->bindParam(12, $doctor_id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception('Failed to execute statement: ' . implode(' ', $stmt->errorInfo()));
        }
        return [
            'success' => true,
            'message' => 'Thông tin nhân viên đã được cập nhật thành công.'
        ];
    }

    public function addDoctor($name, $dob, $email, $phone, $gender, $address, $service_id, $position_id, $status, $avt, $update_by): array
    {
        // Kiểm tra số điện thoại đã tồn tại chưa
        if ($this->checkPhoneExists($phone)) {
            return [
                'success' => false,
                'message' => 'Số điện thoại đã tồn tại trong hệ thống.'
            ];
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $created_at = date("Y-m-d H:i:s");
        $hashedPassword = password_hash('Abc12345', PASSWORD_BCRYPT, ['cost' => 12]);
        if($position_id == 5) {
            $role_id = 3;
        } else {
            $role_id = 2;
        }
        // Bước 1: Thêm  mà không có employee_code
        $sql = "INSERT INTO employees (
                   service_id,
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
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->connection->prepare($sql);
        if ($stmt === false) {
            throw new Exception('MySQL prepare error: ' . implode(' ', $this->connection->errorInfo()));
        }

        $stmt->bindParam(1, $service_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $position_id, PDO::PARAM_INT);
        $stmt->bindParam(3, $role_id, PDO::PARAM_INT);
        $stmt->bindParam(4, $name, PDO::PARAM_STR);
        $stmt->bindParam(5, $hashedPassword, PDO::PARAM_STR);
        $stmt->bindParam(6, $phone, PDO::PARAM_STR);
        $stmt->bindParam(7, $email, PDO::PARAM_STR);
        $stmt->bindParam(8, $dob, PDO::PARAM_STR);
        $stmt->bindParam(9, $gender, PDO::PARAM_INT);
        $stmt->bindParam(10, $address, PDO::PARAM_STR);
        $stmt->bindParam(11, $status, PDO::PARAM_INT);
        $stmt->bindParam(12, $created_at, PDO::PARAM_STR);
        $stmt->bindParam(13, $avt, PDO::PARAM_STR);
        $stmt->bindParam(14, $update_by, PDO::PARAM_INT);
        // Removed redundant and misplaced $stmtUpdate code block

        return [
            'success' => true,
            'message' => 'Chuyên gia đã được thêm thành công.'
        ];
    }

    public function getDoctorForHome(): array
    {
        $sql = "SELECT e.employee_id, e.name as doctorName, e.avt, s.name as specialtyName
                FROM employees AS e
                JOIN services AS s ON e.service_id = s.service_id
                JOIN roles AS r ON r.role_id = e.role_id
                WHERE r.role_name = 'employee'
                LIMIT 3";

        $query = $this->_query($sql);
        $data = [];
        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $result;
        }
        return $data;
    }

    public function getDoctorForAdmin(): array
    {
        $sql = "SELECT
                    e.employee_id AS id,
                    e.avt AS avt,
                    e.name AS name,
                    p.name AS position,
                    s.name AS specialty,
                    e.email AS email,
                    e.phone AS phone,
                    e.gender AS gender,
                    e.dob AS dob,
                    e.employee_code AS employee_code,
                    e.status AS status
                FROM employees AS e
                    JOIN positions AS p ON e.position_id = p.position_id
                    JOIN services AS s ON e.service_id = s.service_id
                    JOIN roles AS r ON e.role_id = r.role_id
                WHERE r.role_name = LOWER('employee') OR r.role_name = LOWER('consultant') ORDER BY e.employee_id DESC";

        $query = $this->_query($sql);
        $data = [];
        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $result;
        }
        return $data;
    }

    // ... (các phương thức khác trong lớp DoctorModel) ...

public function getDoctorsBySpecialty($specialty): array
{
    // Sử dụng placeholder '?' cho prepared statement
    $sql = "SELECT e.employee_id, e.name
            FROM employees AS e
            JOIN services AS s ON e.service_id = s.service_id
            JOIN roles AS r ON r.role_id = e.role_id
            WHERE r.role_name = 'employee' AND e.status = 1 AND s.service_id = ?"; // <-- Sử dụng placeholder

    // Chỉ prepare một lần
    $stmt = $this->connection->prepare($sql);
    if ($stmt === false) {
        // Ghi log lỗi hoặc xử lý một cách phù hợp hơn là throw Exception trực tiếp ra client nếu có thể
        error_log('MySQL prepare error in getDoctorsBySpecialty: ' . implode(' ', $this->connection->errorInfo()));
        // Trong môi trường production, bạn có thể muốn trả về một mảng rỗng hoặc thông báo lỗi thân thiện
        // thay vì throw Exception làm dừng toàn bộ script và lộ thông tin lỗi.
        // Ví dụ: return ['error' => 'Không thể tải danh sách bác sĩ lúc này.'];
        // Hoặc đơn giản là: return [];
        throw new Exception('Lỗi chuẩn bị truy vấn lấy bác sĩ theo chuyên khoa.');
    }

    // Bind giá trị $specialty vào placeholder thứ nhất
    // Đảm bảo $specialty là kiểu INT nếu cột service_id là INT
    $stmt->bindParam(1, $specialty, PDO::PARAM_INT);

    if (!$stmt->execute()) {
        // Ghi log lỗi hoặc xử lý
        error_log('Failed to execute statement in getDoctorsBySpecialty: ' . implode(' ', $stmt->errorInfo()));
        // Tương tự như trên, xử lý lỗi một cách thân thiện hơn
        // Ví dụ: return ['error' => 'Không thể thực thi truy vấn danh sách bác sĩ.'];
        // Hoặc: return [];
        throw new Exception('Lỗi thực thi truy vấn lấy bác sĩ theo chuyên khoa.');
    }

    // Lấy tất cả các dòng kết quả dưới dạng mảng các mảng kết hợp
    // Đây là cách hiệu quả hơn để lấy nhiều dòng thay vì dùng vòng lặp while với fetch()
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $data;
}

// ... (phần còn lại của lớp DoctorModel) ...
    }
