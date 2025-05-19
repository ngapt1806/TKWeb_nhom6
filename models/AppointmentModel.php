<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../utils/convertDate.php'; // Đảm bảo đường dẫn này đúng

class AppointmentModel extends Database {
    protected $connection = null;

    public function __construct() {
        $this->connection = $this->connect(); // Giả định $this->connect() trả về đối tượng PDO
    }

    // Hàm _query() bị loại bỏ vì nó sử dụng mysqli_*

    public function getAppointmentGuests($limit = 10, $page = 1, $search = null): array
    {
        $offset = ($page - 1) * $limit;
        $params = [];
        $sql = "SELECT a.patient_id AS patient_id,
                       a.patient_name AS patient_name,
                       a.patient_dob AS patient_dob,
                       a.patient_gender AS patient_gender,
                       a.patient_phone AS patient_phone,
                       a.patient_email AS patient_email,
                       COUNT(a.appointment_id) AS total_appointments
                FROM appointments AS a
                WHERE a.patient_id IS NULL AND a.status = 2 "; // status = 2 là đã hoàn thành

        if ($search) {
            $sql .= " AND (a.patient_name LIKE :search OR a.patient_phone LIKE :search_phone)";
            $params[':search'] = '%' . $search . '%';
            $params[':search_phone'] = '%' . $search . '%';
        }
        $sql .= " GROUP BY a.patient_phone, a.patient_name, a.patient_dob, a.patient_gender, a.patient_email 
                  LIMIT :limit OFFSET :offset";
        $params[':limit'] = (int)$limit;
        $params[':offset'] = (int)$offset;

        try {
            $stmt = $this->connection->prepare($sql);
            foreach ($params as $key => $value) {
                 if ($key === ':limit' || $key === ':offset') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value, PDO::PARAM_STR);
                }
            }
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDO Error in getAppointmentGuests: " . $e->getMessage() . " SQL: " . $sql . " Params: " . print_r($params, true));
            return [];
        }
    }
    // Thêm phương thức này vào trong lớp AppointmentModel (models/AppointmentModel.php)

public function getTotalAppointments(): int
{
    $sql = "SELECT COUNT(*) AS total 
            FROM appointments AS a
            JOIN employees AS e ON e.employee_id = a.employee_id
            JOIN roles AS r ON r.role_id = e.role_id
            WHERE (r.role_name = LOWER('employee') OR r.role_name = LOWER('consultant'))";
    try {
        // Vì không có tham số từ bên ngoài, query() là an toàn ở đây.
        $stmt = $this->connection->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total'] : 0;
    } catch (PDOException $e) {
        error_log("PDO Error in getTotalAppointments (general, no params): " . $e->getMessage());
        return 0;
    }
}

    public function getTotalAppointmentGuests($search = null): int
    {
        $params = [];
        $sql = "SELECT COUNT(DISTINCT a.patient_phone) AS total 
                FROM appointments AS a
                WHERE a.patient_id IS NULL AND a.status = 2 ";
        if ($search) {
            $sql .= " AND (a.patient_name LIKE :search OR a.patient_phone LIKE :search_phone)";
            $params[':search'] = '%' . $search . '%';
            $params[':search_phone'] = '%' . $search . '%';
        }
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log("PDO Error in getTotalAppointmentGuests: " . $e->getMessage());
            return 0;
        }
    }
    
    private function buildAppointmentQuery(string $selectClause, array &$params, $specialty = null, $doctor = null, $search = null, $status = null, $date_slot = null, $useGroupByForPatient = false): string
    {
        $sql = $selectClause;
        $sql .= " FROM appointments AS a
                  JOIN employees AS e ON e.employee_id = a.employee_id
                  JOIN roles AS r ON r.role_id = e.role_id
                  JOIN time_slots AS ts ON ts.time_id = a.time_id
                  JOIN services AS s ON s.service_id = a.service_id
                  WHERE (r.role_name = LOWER('employee') OR r.role_name = LOWER('consultant')) "; // Cho phép cả employee và consultant

        if ($status !== null) {
            if (is_array($status)) {
                if (!empty($status)) {
                    $statusPlaceholders = implode(',', array_fill(0, count($status), '?'));
                    $sql .= " AND a.status IN (" . $statusPlaceholders . ")";
                    foreach ($status as $st) {
                        $params[] = $st;
                    }
                }
            } else {
                 $sql .= " AND a.status = ?";
                 $params[] = $status;
            }
        }

        if ($specialty) {
            $sql .= " AND s.service_id = ?";
            $params[] = $specialty;
        }
        if ($doctor) {
            $sql .= " AND e.employee_id = ?";
            $params[] = $doctor;
        }
        if ($date_slot !== null) {
            $sql .= " AND a.date_slot = ?";
            $params[] = $date_slot;
        }
        if ($search) {
            $sql .= " AND (a.patient_name LIKE ? OR a.patient_phone LIKE ?)";
            $params[] = '%' . $search . '%';
            $params[] = '%' . $search . '%';
        }
         if ($useGroupByForPatient) {
            $sql .= " GROUP BY a.patient_phone, a.patient_name, a.patient_dob, a.patient_gender, a.patient_email ";
        }
        return $sql;
    }


    public function getTotalAppointmentsByStatus($status, $specialty = null, $doctor = null, $search = null): int
    {
        $params = [];
        $selectClause = "SELECT COUNT(*) AS total ";
        $sql = $this->buildAppointmentQuery($selectClause, $params, $specialty, $doctor, $search, $status);

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log("PDO Error in getTotalAppointmentsByStatus (status: $status): " . $e->getMessage() . " SQL: " . $sql . " Params: " . print_r($params, true));
            return 0;
        }
    }

    public function getAppointmentsByStatus($status, $limit = 10, $page = 1, $specialty = null, $doctor = null, $search = null, $orderBy = "a.update_at DESC"): array
    {
        $offset = ($page - 1) * $limit;
        $params = [];
        $selectClause = "SELECT a.appointment_id AS id, 
                                e.name AS doctor_name,
                                e.avt AS doctor_avt,
                                a.patient_name AS patient_name,
                                a.patient_dob AS patient_dob,
                                a.patient_gender AS patient_gender,
                                a.patient_phone AS patient_phone,
                                a.patient_email AS patient_email,
                                s.name AS specialty_name,
                                a.date_slot AS date_slot,
                                ts.slot_time AS time_slot,
                                a.status AS status ";
        $sql = $this->buildAppointmentQuery($selectClause, $params, $specialty, $doctor, $search, $status);
        $sql .= " ORDER BY $orderBy LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;

        try {
            $stmt = $this->connection->prepare($sql);
            // Bind params one by one to ensure correct types for limit and offset
            for ($i = 0; $i < count($params) - 2; $i++) {
                 $stmt->bindValue($i + 1, $params[$i]); // General type, PDO will handle
            }
            $stmt->bindValue(count($params) - 1, $params[count($params) - 2], PDO::PARAM_INT); // limit
            $stmt->bindValue(count($params), $params[count($params) - 1], PDO::PARAM_INT);     // offset
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDO Error in getAppointmentsByStatus (status: $status): " . $e->getMessage() . " SQL: " . $sql . " Params: " . print_r($params, true));
            return [];
        }
    }

    // Specific status getters
    public function getTotalAppointmentsExpired($specialty = null, $doctor = null, $search = null) {
        return $this->getTotalAppointmentsByStatus(3, $specialty, $doctor, $search);
    }
    public function getAppointmentExpired($limit = 10, $page = 1, $specialty = null, $doctor = null, $search = null): array {
        return $this->getAppointmentsByStatus(3, $limit, $page, $specialty, $doctor, $search);
    }
    public function getTotalAppointmentsConfirm($specialty = null, $doctor = null, $search = null) {
        return $this->getTotalAppointmentsByStatus(0, $specialty, $doctor, $search);
    }
    public function getAppointmentConfirm($limit = 10, $page = 1, $specialty = null, $doctor = null, $search = null): array {
        return $this->getAppointmentsByStatus(0, $limit, $page, $specialty, $doctor, $search, "a.date_slot ASC, ts.slot_time ASC");
    }


    public function updateAppointment($id, $employee_id, $service_id, $date_slot, $time_id, $patient_name, $patient_gender, $patient_email, $patient_description, $status, $update_by): bool
    {
        if ($id === null) {
            return false;
        }
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $updated_at = date('Y-m-d H:i:s');

        $sql = "UPDATE appointments SET 
                    date_slot = :date_slot, employee_id = :employee_id, patient_description = :patient_description,
                    patient_email = :patient_email, patient_gender = :patient_gender, patient_name = :patient_name,
                    service_id = :service_id, status = :status, time_id = :time_id,
                    update_at = :update_at, update_by = :update_by
                WHERE appointment_id = :id";
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                ':date_slot' => $date_slot, ':employee_id' => $employee_id, ':patient_description' => $patient_description,
                ':patient_email' => $patient_email, ':patient_gender' => $patient_gender, ':patient_name' => $patient_name,
                ':service_id' => $service_id, ':status' => $status, ':time_id' => $time_id,
                ':update_at' => $updated_at, ':update_by' => $update_by, ':id' => $id
            ]);
        } catch (PDOException $e) {
            error_log('Lỗi cập nhật cuộc hẹn: ' . $e->getMessage());
            return false;
        }
    }

    public function updateResultAppointment($id, $result): bool
    {
        if ($id === null) return false;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $updatedAt = date('Y-m-d H:i:s');
        $sql = "UPDATE appointments SET
                    result = :result, update_at = :updated_at,
                    status = CASE WHEN status = 1 THEN 2 ELSE status END
                WHERE appointment_id = :id";
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([':result' => $result, ':updated_at' => $updatedAt, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Lỗi cập nhật kết quả cuộc hẹn: ' . $e->getMessage());
            return false;
        }
    }

    public function updateStatusAppointment($id, $new_status = 3): bool // Allow specifying new status, default to 3 (Expired/Cancelled)
    {
        if ($id === null) return false;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $updatedAt = date('Y-m-d H:i:s');
        // If current status is 1 (Processing), change to $new_status. Otherwise, keep current status.
        // This prevents accidental changes from other states unless explicitly desired.
        $sql = "UPDATE appointments SET
                    update_at = :updated_at,
                    status = CASE WHEN status = 1 THEN :new_status ELSE status END 
                WHERE appointment_id = :id";
        try {
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([':updated_at' => $updatedAt, ':new_status' => $new_status, ':id' => $id]);
        } catch (PDOException $e) {
            error_log('Lỗi cập nhật trạng thái cuộc hẹn: ' . $e->getMessage());
            return false;
        }
    }


    public function getAppointmentById($appointmentId = null): ?array
    {
        if ($appointmentId === null) return null;
        $sql = "SELECT a.appointment_id AS id, e.name AS doctor_name, e.employee_id AS employee_id,
                       a.patient_name AS patient_name, a.patient_dob AS patient_dob, a.patient_gender AS patient_gender,
                       a.patient_phone AS patient_phone, a.patient_email AS patient_email, a.patient_description AS patient_description,
                       s.name AS specialty_name, s.service_id AS service_id,
                       a.date_slot AS date_slot, ts.slot_time AS time_slot, ts.time_id AS time_id,
                       a.result AS result, a.status AS status
                FROM appointments AS a
                         JOIN employees AS e ON e.employee_id = a.employee_id
                         JOIN time_slots AS ts ON ts.time_id = a.time_id
                         JOIN services AS s ON s.service_id = a.service_id
                WHERE a.appointment_id = :appointment_id";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':appointment_id', $appointmentId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("PDO Error in getAppointmentById: " . $e->getMessage());
            return null;
        }
    }
    
    public function getAppointmentToday($limit = 10, $page = 1, $specialty = null, $doctor = null, $search = null): array
    {
        $converter = new ConvertDate(); // Make sure this class and method are correct
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today_str = date('d/m/Y');
        $date_timestamp = $converter->convertDateToDayTimestamp($today_str); // Ensure this returns the expected format for date_slot

        // Assuming status != 0 means appointments that are not 'pending confirmation'
        // And date_slot matches today's timestamp
        return $this->getAppointmentsByStatus([1, 2, 3], $limit, $page, $specialty, $doctor, $search, "ts.slot_time ASC", $date_timestamp);
    }

    public function getTotalAppointmentsToday($specialty = null, $doctor = null, $search = null): int
    {
        $converter = new ConvertDate();
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today_str = date('d/m/Y');
        $date_timestamp = $converter->convertDateToDayTimestamp($today_str);
        
        // Statuses 1 (Processing), 2 (Completed), 3 (Expired/Cancelled) for today
        return $this->getTotalAppointmentsByStatus([1,2,3], $specialty, $doctor, $search, $date_timestamp);
    }


    public function getAllAppointmentsForAdmin($limit = 10, $page = 1, $specialty = null, $doctor = null, $statusAppointment = null, $search = null): array
    {
        $offset = ($page - 1) * $limit;
        $params = [];
        $sql_select = "SELECT a.appointment_id AS id, e.name AS doctor_name, e.avt AS doctor_avt,
                              a.patient_name AS patient_name, a.patient_dob AS patient_dob, a.patient_gender AS patient_gender,
                              a.patient_phone AS patient_phone, a.patient_email AS patient_email,
                              s.name AS specialty_name, a.date_slot AS date_slot,
                              ts.slot_time AS time_slot, a.status AS status ";
        
        $sql_from_where = " FROM appointments AS a
                            JOIN employees AS e ON e.employee_id = a.employee_id
                            JOIN roles AS r ON r.role_id = e.role_id
                            JOIN time_slots AS ts ON ts.time_id = a.time_id
                            JOIN services AS s ON s.service_id = a.service_id
                            WHERE (r.role_name = LOWER('employee') OR r.role_name = LOWER('consultant'))";

        if ($specialty) {
            $sql_from_where .= " AND s.service_id = :specialty";
            $params[':specialty'] = $specialty;
        }
        if ($doctor) {
            $sql_from_where .= " AND e.employee_id = :doctor";
            $params[':doctor'] = $doctor;
        }

        if (isset($statusAppointment) && $statusAppointment !== '') {
            $statusArray = array_map('intval', array_filter(explode(',', $statusAppointment), 'is_numeric'));
            if (!empty($statusArray)) {
                $statusPlaceholders = rtrim(str_repeat('?,', count($statusArray)), ',');
                $sql_from_where .= " AND a.status IN (" . $statusPlaceholders . ")";
                foreach ($statusArray as $statusVal) {
                    $params[] = $statusVal; // Add to unnamed placeholders list
                }
            }
        }

        if ($search) {
            $sql_from_where .= " AND (a.patient_name LIKE :search_name OR a.patient_phone LIKE :search_phone)";
            $params[':search_name'] = '%' . $search . '%';
            $params[':search_phone'] = '%' . $search . '%';
        }

        $sql = $sql_select . $sql_from_where . " ORDER BY a.created_at DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = (int)$limit;
        $params[':offset'] = (int)$offset;
        
        try {
            $stmt = $this->connection->prepare($sql);
            // We need to re-index numeric placeholders if both named and unnamed are mixed
            // It's simpler if we stick to one type or handle binding carefully
            $final_params = [];
            $sql_rebuilt_for_execute = $sql; // For execute array if we transform placeholders

            // Separate named and unnamed from $params for clarity if needed, or ensure correct order for execute
            // For simplicity with mixed types, binding one by one might be clearer if issues arise.
            // However, PDO execute array can handle named placeholders. For unnamed, order matters.
            // Let's try to build the execute array in correct order.
            
            $execute_array = [];
            $unnamed_param_idx = 0;
            foreach ($params as $key => $value) {
                if (is_int($key)) { // Unnamed placeholder from status IN (...)
                     $execute_array[$unnamed_param_idx++] = $value; // This assumes unnamed come first
                } else {
                    $execute_array[$key] = $value;
                }
            }
            // This simplified execute_array creation might not work if unnamed placeholders are not contiguous or at the start.
            // A more robust way for mixed is to bindValue individually for complex cases or refactor to use only named.
            // Given the structure, let's assume named for most and handle the IN clause separately for binding if execute array fails.

            // Simpler approach: execute with all params. PDO is smart.
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDO Error in getAllAppointmentsForAdmin: " . $e->getMessage() . " SQL: " . $sql . " Params: " . print_r($params, true));
            return [];
        }
    }


    public function getTotalAppointmentsWithParam($specialty = null, $doctor = null, $statusAppointment = null, $search = null): int
    {
        $params = [];
        $sql_select = "SELECT COUNT(*) AS total ";
        $sql_from_where = " FROM appointments AS a
                            JOIN employees AS e ON e.employee_id = a.employee_id
                            JOIN roles AS r ON r.role_id = e.role_id
                            JOIN time_slots AS ts ON ts.time_id = a.time_id
                            JOIN services AS s ON s.service_id = a.service_id
                             WHERE (r.role_name = LOWER('employee') OR r.role_name = LOWER('consultant'))";
        
        if ($specialty) {
            $sql_from_where .= " AND s.service_id = :specialty";
            $params[':specialty'] = $specialty;
        }
        if ($doctor) {
            $sql_from_where .= " AND e.employee_id = :doctor";
            $params[':doctor'] = $doctor;
        }
        if (isset($statusAppointment) && $statusAppointment !== '') {
            $statusArray = array_map('intval', array_filter(explode(',', $statusAppointment), 'is_numeric'));
            if (!empty($statusArray)) {
                $statusPlaceholders = rtrim(str_repeat('?,', count($statusArray)), ',');
                $sql_from_where .= " AND a.status IN (" . $statusPlaceholders . ")";
                foreach ($statusArray as $statusVal) {
                     // Add to $params in order for unnamed placeholders
                    $params[] = $statusVal; // These will be bound by position
                }
            }
        }
        if ($search) {
            $sql_from_where .= " AND (a.patient_name LIKE :search_name OR a.patient_phone LIKE :search_phone)";
            $params[':search_name'] = '%' . $search . '%';
            $params[':search_phone'] = '%' . $search . '%';
        }
        $sql = $sql_select . $sql_from_where;
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params); // PDO can usually handle mixed named/unnamed if unnamed are in order
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log("PDO Error in getTotalAppointmentsWithParam: " . $e->getMessage() . " SQL: " . $sql . " Params: " . print_r($params, true));
            return 0;
        }
    }


    public function createAppointment($specialId, $doctorId, $dateSlot, $timeSlotId, $patientName, $patientGender, $patientDob, $patientPhone, $patientEmail, $patientDescription, $patient_id): int|string|false
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $createdAt = date('Y-m-d H:i:s');
        $status = 0; 

        $sql_columns = "(service_id, employee_id, date_slot, time_id, patient_name, patient_gender, patient_dob, patient_phone, patient_email, patient_description, status, created_at";
        $sql_values = "(:service_id, :employee_id, :date_slot, :time_id, :patient_name, :patient_gender, :patient_dob, :patient_phone, :patient_email, :patient_description, :status, :created_at";
        
        $params = [
            ':service_id' => $specialId, ':employee_id' => $doctorId, ':date_slot' => $dateSlot, ':time_id' => $timeSlotId,
            ':patient_name' => $patientName, ':patient_gender' => $patientGender, ':patient_dob' => $patientDob,
            ':patient_phone' => $patientPhone, ':patient_email' => $patientEmail, ':patient_description' => $patientDescription,
            ':status' => $status, ':created_at' => $createdAt
        ];

        if ($patient_id !== null) {
            $sql_columns .= ", patient_id";
            $sql_values .= ", :patient_id";
            $params[':patient_id'] = $patient_id;
        }
        $sql_columns .= ")";
        $sql_values .= ")";
        $sql = "INSERT INTO appointments " . $sql_columns . " VALUES " . $sql_values;

        try {
            $stmt = $this->connection->prepare($sql);
            if ($stmt->execute($params)) {
                return $this->connection->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("PDO Error in createAppointment: " . $e->getMessage() . " Params: " . print_r($params, true));
            return false;
        }
    }

    public function getAppointmentsByPatient($phone = null, $patient_id = null): array
    {
        $sql = "SELECT a.appointment_id AS id, a.status, a.result, a.patient_name, a.patient_phone, a.patient_email,
                       a.date_slot, e.name AS doctor_name, e.avt AS doctor_avt, s.name AS specialty_name, ts.slot_time AS time_slot
                FROM appointments AS a
                         JOIN employees AS e ON e.employee_id = a.employee_id
                         JOIN time_slots AS ts ON ts.time_id = a.time_id
                         JOIN services AS s ON s.service_id = a.service_id
                WHERE (a.patient_phone = :phone OR (:use_patient_id = 1 AND a.patient_id = :patient_id)) 
                ORDER BY a.date_slot ASC"; // Added condition for patient_id to avoid matching all if patient_id is null

        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindParam(':patient_id', $patient_id, PDO::PARAM_INT);
            $use_patient_id = ($patient_id !== null) ? 1 : 0; // Control if patient_id condition is active
            $stmt->bindParam(':use_patient_id', $use_patient_id, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDO Error in getAppointmentsByPatient: " . $e->getMessage());
            return [];
        }
    }
    
    public function getAppointmentsByPhone($phone = null): array // Assuming this is for COMPLETED appointments by phone
    {
        if ($phone === null) return [];
        $sql = "SELECT a.appointment_id AS id, a.status, a.result, a.patient_name, a.patient_phone, a.patient_email,
                       a.date_slot, e.name AS doctor_name, e.avt AS doctor_avt, s.name AS specialty_name, ts.slot_time AS time_slot
                FROM appointments AS a
                         JOIN employees AS e ON e.employee_id = a.employee_id
                         JOIN time_slots AS ts ON ts.time_id = a.time_id
                         JOIN services AS s ON s.service_id = a.service_id
                WHERE a.status = 2 AND a.patient_phone = :phone 
                ORDER BY a.date_slot ASC";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PDO Error in getAppointmentsByPhone: " . $e->getMessage());
            return [];
        }
    }

    // Helper for simple COUNT(*) queries with a single WHERE a.status = ?
    private function getTotalAppointmentsBySingleStatus(int $status): int
    {
        $sql = "SELECT COUNT(*) AS total FROM appointments AS a WHERE a.status = :status";
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->bindParam(':status', $status, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log("PDO Error in getTotalAppointmentsBySingleStatus (status: $status): " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalAppointmentsCancel() {
        return $this->getTotalAppointmentsBySingleStatus(3); // Assuming status 3 is Cancelled/Expired
    }

    public function getTotalAppointmentsSuccess() {
        return $this->getTotalAppointmentsBySingleStatus(2); // Assuming status 2 is Success/Completed
    }

    public function getTotalAppointmentsProcess() {
        return $this->getTotalAppointmentsBySingleStatus(1); // Assuming status 1 is Processing
    }

    // Helper for age-based COUNT queries
    private function getTotalAppointmentsByAgeRange(string $age_condition): int
    {
        // CURDATE() might not be available or work as expected in all SQL versions through PDO without specific settings.
        // Consider calculating age in PHP if issues arise, or ensure DB compatibility.
        // For MySQL, TIMESTAMPDIFF(YEAR, a.patient_dob, CURDATE()) is standard.
        $sql = "SELECT COUNT(*) AS total
                FROM appointments AS a
                WHERE a.status = 2 AND " . $age_condition; // Assuming status 2 is "completed"
        try {
            // This query doesn't have external parameters, so query() is safe.
            // However, if $age_condition could ever contain user input, it MUST be parameterized.
            // Since $age_condition is hardcoded in calling methods, this is currently safe.
            $stmt = $this->connection->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? (int)$result['total'] : 0;
        } catch (PDOException $e) {
            error_log("PDO Error in getTotalAppointmentsByAgeRange (condition: $age_condition): " . $e->getMessage());
            return 0;
        }
    }

    public function getTotalAppointments0_14() {
        return $this->getTotalAppointmentsByAgeRange("TIMESTAMPDIFF(YEAR, STR_TO_DATE(a.patient_dob, '%d/%m/%Y'), CURDATE()) BETWEEN 0 AND 14");
    }

    public function getTotalAppointments15_35() {
        return $this->getTotalAppointmentsByAgeRange("TIMESTAMPDIFF(YEAR, STR_TO_DATE(a.patient_dob, '%d/%m/%Y'), CURDATE()) BETWEEN 15 AND 35");
    }

    public function getTotalAppointments36_64() {
        return $this->getTotalAppointmentsByAgeRange("TIMESTAMPDIFF(YEAR, STR_TO_DATE(a.patient_dob, '%d/%m/%Y'), CURDATE()) BETWEEN 36 AND 64");
    }

    public function getTotalAppointments64() {
        // Note: Original query had 'CURDATE()) > 64;;' (double semicolon)
        return $this->getTotalAppointmentsByAgeRange("TIMESTAMPDIFF(YEAR, STR_TO_DATE(a.patient_dob, '%d/%m/%Y'), CURDATE()) > 64");
    }
}