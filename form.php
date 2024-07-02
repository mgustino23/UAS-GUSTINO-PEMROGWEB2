<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_session";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$selected_team = '';
$results = [];

if (isset($_SESSION['id']) && isset($_SESSION['user_name'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['proses'])) {
            $selected_team = $_POST['tim'];
            $sql = "SELECT * FROM tickets WHERE tim='$selected_team'";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $results[] = $row;
                }
            }
        }

        if (isset($_POST['save'])) {
            $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
            $tim = $_POST["tim"];
            $menang = (float) $_POST["menang"];
            $seri = (int) $_POST["seri"];
            $kalah = isset($_POST['kalah']) ? (int) $_POST["kalah"] : 0;
            $poin = isset($_POST['poin']) ? (int) $_POST["poin"] : 0;

            if ($id > 0) {
                $sql = "UPDATE tickets SET tim='$tim', menang='$menang', seri='$seri', kalah='$kalah', poin='$poin' WHERE id='$id'";
            } else {
                $sql = "INSERT INTO tickets (tim, menang, seri, kalah, poin) VALUES ('$tim', '$menang', '$seri', '$kalah', '$poin')";
            }

            if ($conn->query($sql) === TRUE) {
                echo "Record processed successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        if (isset($_POST['delete'])) {
            $id = (int) $_POST['delete_id'];
            $sql = "DELETE FROM tickets WHERE id='$id'";
            $conn->query($sql);
        }
    }

    $edit_id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $edit_row = [];
    if ($edit_id > 0) {
        $sql = "SELECT * FROM tickets WHERE id='$edit_id'";
        $edit_result = $conn->query($sql);
        if ($edit_result->num_rows > 0) {
            $edit_row = $edit_result->fetch_assoc();
        }
    }
}
?>

<html>
<head>
    <title>Penjualan Tiket</title>
    <script>
        function updatePrice() {
            var tim = document.getElementById('tim').value;
            var menang = 0;
            var seri = 0;
            var kalah = 0;
            var poin = 0;
            switch (tim) {
                case 'Austria':
                    
                    break;
                case 'Prancis':
                    
                    break;
                case 'Belanda':
                    
                    break;
                case 'Polandia':
                    
                    break;
            }
            document.getElementById('menang').value = menang;
        }
    </script>
</head>
<body>
    <form method="post">
        <center>
            <br><b>DATA GROUP D</b>
            <br><b>02 JULY 2024 / 22.39</b>
            <br><b>201011401029</b>
        </center>
        <br>
        <br>
        <table cellpadding=8>
            <tr>
                <td>Tim</td>
                <td>
                    <select id="tim" name="tim" onchange="updatePrice()">
                        <option value="">Pilih Tim</option>
                        <option value="Austria" <?php echo $selected_team == 'Austria' ? 'selected' : ''; ?>>Austria</option>
                        <option value="Prancis" <?php echo $selected_team == 'Prancis' ? 'selected' : ''; ?>>Prancis</option>
                        <option value="Belanda" <?php echo $selected_team == 'Belanda' ? 'selected' : ''; ?>>Belanda</option>
                        <option value="Polandia" <?php echo $selected_team == 'Polandia' ? 'selected' : ''; ?>>Polandia</option>
                    </select>
                </td>
            </tr>
        </table>
        <input type="submit" name="proses" value="PROSES">
        <input type="button" value="LOGOUT" onclick="window.location.href='logout.php'">
        *untuk melakukan cek DATA GROUP KLIK PROSES
        <br>
        <br>
        <?php if (!empty($results)): ?>
        <table border=5 cellpadding=10>
            <tr>
                <td>ID</td>
                <td>Tim</td>
                <td>Menang</td>
                <td>Seri</td>
                <td>Kalah</td>
                <td>Poin</td>
                <td>Aksi</td>
            </tr>
            <?php
            foreach ($results as $row) {
                echo "<tr>
                    <td>" . $row['id'] . "</td>
                    <td>" . $row['tim'] . "</td>
                    <td>" . $row['menang'] . "</td>
                    <td>" . $row['seri'] . "</td>
                    <td>" . $row['kalah'] . "</td>
                    <td>" . $row['poin'] . "</td>
                    <td><a href='?id=" . $row['id'] . "'>Edit</a> | 
                    <form method='post' style='display:inline-block;'>
                        <input type='hidden' name='delete_id' value='" . $row['id'] . "'>
                        <input type='submit' name='delete' value='Delete'>
                    </form></td>
                </tr>";
            }
            ?>
        </table>
        <?php endif; ?>
    </form>
</body>
</html>

<?php
$conn->close();
?>
