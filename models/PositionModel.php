<?php
// Đảm bảo đường dẫn đến BaseModel.php hoặc Database.php là chính xác
require_once 'BaseModel.php'; // Hoặc Database.php, tùy thuộc vào lớp cha

class PositionModel extends Database // Hoặc BaseModel
{
    protected $connection = null; // Biến này sẽ lưu đối tượng PDO

    public function __construct()
    {
        // Kết nối và lấy đối tượng PDO từ lớp cha
        $this->connection = $this->connect();
        if (!$this->connection) {
            // Xử lý lỗi nếu kết nối PDO không thành công
            // Ví dụ: die("Không thể kết nối PDO.");
            error_log("PositionModel: Không thể khởi tạo kết nối PDO.");
            // Bạn có thể muốn throw một exception ở đây
        }
    }

    // Phương thức _query() sử dụng mysqli_* đã bị loại bỏ hoặc không nên được sử dụng
    // Nếu bạn vẫn thấy nó không được comment, hãy comment hoặc xóa nó đi.
    /*
    private function _query($sql){
        // Dòng này sẽ gây lỗi nếu $this->connection là PDO
        return mysqli_query($this->connection, $sql);
    }
    */

    /**
     * Lấy tất cả các vị trí sử dụng PDO.
     */
    public function getAll(): array
    {
        $sql = "SELECT * FROM positions";

        try {
            // Kiểm tra xem $this->connection có phải là đối tượng PDO hợp lệ không
            if (!$this->connection instanceof PDO) {
                error_log("PDO Error in PositionModel::getAll(): Kết nối không phải là đối tượng PDO.");
                return [];
            }

            $stmt = $this->connection->query($sql);
            // Kiểm tra $stmt trước khi gọi fetchAll
            if ($stmt) {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {
                // Ghi log lỗi nếu query không thành công
                error_log("PDO Error in PositionModel::getAll(): Không thể thực thi query. Lỗi: " . implode(":", $this->connection->errorInfo()));
                return [];
            }
        } catch (PDOException $e) {
            error_log("PDO Exception in PositionModel::getAll(): " . $e->getMessage());
            return [];
        }
    }

    // Thêm các phương thức khác sử dụng PDO tại đây nếu cần
}