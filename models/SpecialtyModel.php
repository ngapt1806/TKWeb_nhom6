<?php
class SpecialtyModel extends Database{
    const TABLE_NAME = 'services';

    protected $connection = null;

    public function __construct() {
        $this->connection = $this->connect();
    }

    private function _query($sql){
        return $this->connection->query($sql);
    }

    public function addSpecialty($name, $description, $status, $update_by, $servicePrice) {
        $name = $this->connection->quote($name);
        $description = $this->connection->quote($description);
        $status = $this->connection->quote($status);
        $created_at = date("Y-m-d H:i:s");

        $sql = "INSERT INTO services (name, description, status, create_at, update_by, price) 
                VALUES ('$name', '$description', '$status', '$created_at', $update_by, $servicePrice)";

        return $this->_query($sql);
    }

    public function updateSpecialtyById($service_id, $specialtyName, $specialtyDescription, $specialtyStatus, $employee_id, $servicePrice): mysqli_result|bool
    {
        $specialtyName = $this->connection->quote($specialtyName);
        $specialtyDescription = $this->connection->quote($specialtyDescription);
        $specialtyStatus = $this->connection->quote($specialtyStatus);

        $sql = "UPDATE services SET 
                name = '{$specialtyName}', 
                description = '{$specialtyDescription}', 
                status = '{$specialtyStatus}', 
                price = '{$servicePrice}', 
                update_at = NOW(),
                update_by = $employee_id
                WHERE service_id = {$service_id}";
        return $this->_query($sql);
    }

    public function getSpecialtiesForAppointment(): array
    {
        $sql = "SELECT *
            FROM services
            WHERE status = 1";

        $query = $this->_query($sql);
        $data = [];
        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $result;
        }
        return $data;
    }

    public function getSpecialties(): array
    {
        $sql = "SELECT *
            FROM services ORDER BY create_at DESC";

        $query = $this->_query($sql);
        $data = [];
        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $result;
        }
        return $data;
    }

    public function getSpecialtiesForAdmin(): array
    {
        $sql = "SELECT *
            FROM services WHERE status = 1";

        $query = $this->_query($sql);
        $data = [];
        while ($result = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $result;
        }
        return $data;
    }

    public function getById($service_id): array
    {
        $sql = "SELECT *
            FROM services WHERE service_id = {$service_id}";

        $query = $this->_query($sql);
        return $query->fetch(PDO::FETCH_ASSOC);
    }
}