<?php
class CustomerModel extends Database
{
    protected $connection = null;

    public function __construct()
    {
        $this->connection = $this->connect();
    }

    public function getPatientForAdmin(): array
    {
        $sql = "SELECT p.patient_id AS patient_id,
                       p.name AS name,
                       p.dob AS dob,
                       p.gender AS gender,
                       p.address AS address,
                       p.phone AS phone,
                       p.email AS email,
                       COUNT(a.patient_id) AS total_appointments
                FROM customers AS p
                         LEFT JOIN appointments AS a ON p.patient_id = a.patient_id
                GROUP BY p.patient_id, p.name, p.dob, p.gender, p.address, p.phone, p.email";
        
        try {
            // Đối với truy vấn không có tham số và không thay đổi dữ liệu, query() có thể được sử dụng
            $stmt = $this->connection->query($sql); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Ghi log lỗi hoặc xử lý một cách phù hợp
            error_log("PDO Error in getPatientForAdmin: " . $e->getMessage());
            return []; // Trả về mảng rỗng nếu có lỗi
        }
    }

    public function findById($id): ?array // Cho phép trả về null nếu không tìm thấy
    {
        $sql = "SELECT p.patient_id,
                       p.name AS name,
                       p.email AS email,
                       p.phone AS phone,
                       p.gender AS gender,
                       p.dob AS dob,
                       p.address AS address,
                       p.status AS status
                FROM customers AS p WHERE p.patient_id = :id";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null; // Trả về null nếu fetch không có kết quả
        } catch (PDOException $e) {
            error_log("PDO Error in findById: " . $e->getMessage());
            return null;
        }
    }

    public function findByPhone($phone): ?array // Cho phép trả về null nếu không tìm thấy
    {
        $sql = "SELECT p.patient_id,
                       p.name AS name,
                       p.email AS email,
                       p.phone AS phone,
                       p.gender AS gender,
                       p.dob AS dob,
                       p.address AS address,
                       p.status AS status
                FROM customers AS p WHERE p.phone = :phone";
        
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("PDO Error in findByPhone: " . $e->getMessage());
            return null;
        }
    }

    public function updatePatient($name, $gender, $dob, $email, $address, $phone): bool
    {
        // Lưu ý: Cập nhật dựa trên `phone` có thể không phải là ý tưởng tốt nếu `phone` không phải là khóa chính
        // hoặc không có ràng buộc UNIQUE. Nếu có nhiều khách hàng cùng SĐT, tất cả sẽ bị cập nhật.
        // Thông thường, cập nhật sẽ dựa trên patient_id.
        $sql = "UPDATE customers SET 
                    name = :name,
                    email = :email,
                    gender = :gender,
                    dob = :dob,
                    address = :address
                WHERE phone = :phone_condition"; // Đổi tên placeholder để tránh nhầm lẫn với biến $phone

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            // Giả sử gender là INT (0 hoặc 1). Nếu là string ('Nam', 'Nữ') thì dùng PDO::PARAM_STR
            $stmt->bindParam(':gender', $gender, PDO::PARAM_INT); 
            $stmt->bindParam(':dob', $dob, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':phone_condition', $phone, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("PDO Error in updatePatient: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($patient_id, $status, $employee_id): bool
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $update_at = date('Y-m-d H:i:s');
        $sql = "UPDATE customers SET 
                    status = :status,
                    update_at = :update_at,
                    update_by = :update_by
                WHERE patient_id = :patient_id";

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->bindParam(':update_at', $update_at, PDO::PARAM_STR);
            $stmt->bindParam(':update_by', $employee_id, PDO::PARAM_INT); // Giả sử employee_id là INT
            $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("PDO Error in updateStatus: " . $e->getMessage());
            return false;
        }
    }

    public function checkPhoneExists($phone): bool
    {
        $sql = "SELECT COUNT(*) as count FROM customers WHERE phone = :phone";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result && $result['count'] > 0);
        } catch (PDOException $e) {
            error_log("PDO Error in checkPhoneExists: " . $e->getMessage());
            // Tùy thuộc vào logic của bạn, bạn có thể muốn trả về false
            // hoặc ném một exception để báo hiệu có lỗi nghiêm trọng hơn.
            return false; 
        }
    }
}