<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Obat.xls");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <table border="1">
        <thead>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($obat as $key => $val): ?>
            <tr>
                <td><?= htmlspecialchars($key) ?></td>
                <td>
                    <?php 
                        if(is_string($val) && (strpos($val, '{') === 0 || strpos($val, '[') === 0)) {
                            $decoded = json_decode($val, true);
                            if(json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                foreach($decoded as $k => $v) {
                                    if(is_array($v)) {
                                        echo "<b>Item " . htmlspecialchars($k) . "</b>: ";
                                        foreach($v as $k2 => $v2) {
                                            echo htmlspecialchars($k2) . "=" . htmlspecialchars($v2) . ", ";
                                        }
                                        echo "<br>";
                                    } else {
                                        echo "<b>" . htmlspecialchars($k) . "</b>: " . htmlspecialchars($v) . "<br>";
                                    }
                                }
                            } else {
                                echo htmlspecialchars($val);
                            }
                        } else {
                            echo htmlspecialchars($val);
                        }
                    ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>