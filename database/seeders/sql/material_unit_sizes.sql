SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `material_unit_sizes` (`id`, `Unit_Size`) VALUES
(1, 'Each'),
(2, 'Unit'),
(3, 'Kit'),
(4, 'Pallet'),
(5, 'Case'),
(6, 'Bundle')

;COMMIT;