<?php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Solicitud.php';
require_once __DIR__ . '/../models/Taller.php';

class AdminController
{
    private $solicitudModel;
    private $tallerModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();
        $this->solicitudModel = new Solicitud($db);
        $this->tallerModel = new Taller($db);
    }

    public function solicitudes()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            header('Location: index.php?page=login');
            return;
        }
        require __DIR__ . '/../views/admin/solicitudes.php';
    }

    // Obtener solicitudes pendientes en JSON
    public function getSolicitudesJson()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode([]);
            return;
        }

        $solicitudes = $this->solicitudModel->getPendientes();
        header('Content-Type: application/json');
        echo json_encode($solicitudes);
    }

    // Aprobar solicitud
    public function aprobar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }

        $solicitudId = $_POST['id_solicitud'] ?? 0;

        try {
            // Obtener el taller_id antes de aprobar
            $tallerId = $this->solicitudModel->getTallerId($solicitudId);

            if (!$tallerId) {
                echo json_encode(['success' => false, 'error' => 'Solicitud no encontrada']);
                return;
            }

            // Aprobar la solicitud
            if ($this->solicitudModel->aprobar($solicitudId)) {
                // Descontar cupo del taller
                $this->tallerModel->descontarCupo($tallerId);
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al aprobar la solicitud']);
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    public function rechazar()
    {
        if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'admin') {
            echo json_encode(['success' => false, 'error' => 'No autorizado']);
            return;
        }

        $solicitudId = $_POST['id_solicitud'] ?? 0;

        if ($this->solicitudModel->rechazar($solicitudId)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Error al rechazar']);
        }
    }
}
