SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `projects` (`id`, `name`, `description`, `image`, `project_url`, `project_order`, `created_at`, `updated_at`) VALUES
(1, 'Operator Logbook', '', 'projects/tXvk8xuK8MIOd32y7aqXIHD5pBD6uLZBQmyheOwu.png', 'https://github.com/DigitalDadKC/VBA-OperatorLogbook', 4, '2024-02-28 19:44:53', '2024-02-28 19:44:53'),
(2, 'Production Line Efficiency Logbook', '', 'projects/eQkzEPmapfPyr5zGCLYBA8EPzfPHL42Uzw1btmXn.png', 'https://github.com/DigitalDadKC/VBA-ProductionLineEfficiencyLog', 5, '2024-02-28 19:46:09', '2024-02-28 19:46:09'),
(3, 'Project Estimating Table', 'Sample Project Estimate', 'projects/Hc1cioNeUqIMOujC4h7MYJ9UDewGpBWHNn1IRRRV.png', 'http://localhost:8000/estimating', 2, '2024-02-28 19:46:54', '2024-09-11 19:42:19'),
(4, 'Responsive Spreadsheet', '', 'projects/F5JbauLZ989y4xuR80lLl3GqHxFB81lA3tVZ4QiA.png', 'http://localhost:8000/datatable', 1, '2024-02-28 20:01:11', '2024-02-28 20:01:11'),
(5, 'Nifty Cards', '', 'projects/y7rv2Ga0swRDld7wU7znguIzxJAWgDRXpoFCIfe5.png', 'http://localhost:8000/portfolio#vue-flipcard', 3, '2024-06-15 01:15:37', '2024-06-15 01:15:37')

;
COMMIT;