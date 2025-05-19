<?php
require_once __DIR__ . '/../core/Database.php';
class TimeSlotModel extends Database
{
    const TABLE_NAME = 'time_slots';

    protected $connection = null;

    public function __construct()
    {
        $this->connection = $this->connect();
    }

    private function _query($sql)
    {
        return mysqli_query($this->connection, $sql);
    }

    public function getAll()
    {
        $sql = "SELECT *
        FROM time_slots";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $timeSlots = [];
        while ($row = $result->fetch_assoc()) {
            $timeSlot = date("H:i", strtotime($row['slot_time']));
            $timeSlots[] = $timeSlot;
        }

        $stmt->close();
        return $timeSlots;
    }

    public function getByDateAndDoctor($date_slot, $doctorId)
    {
        $sql = "SELECT ts.slot_time
        FROM time_slots AS ts
        JOIN appointments AS a ON a.time_id = ts.time_id
        JOIN employees AS e ON a.employee_id = e.employee_id
        JOIN roles AS r ON e.role_id = r.role_id
        JOIN specialties AS s ON s.specialty_id = a.specialty_id
        WHERE r.role_name = 'doctor' AND a.date_slot = ? AND e.employee_id = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ii", $date_slot, $doctorId);
        $stmt->execute();
        $result = $stmt->get_result();

        $timeSlots = [];
        while ($row = $result->fetch_assoc()) {
            $timeSlot = date("H:i", strtotime($row['slot_time']));
            $timeSlots[] = $timeSlot;
        }

        $stmt->close();
        return $timeSlots;
    }
}