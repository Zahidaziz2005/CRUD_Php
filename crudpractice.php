<?php
// 1. ڈیٹا بیس کنکشن
$servername = "localhost";
$username = "root";
$password = "";
$database = "zahid";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ویری ایبلز کی شروعات
$update = false;
$name = "";
$job = "";
$id = 0;

// 2. ریکارڈ ڈیلیٹ کرنے کی لاجک
if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `phptrim` WHERE `sr.no.` = $sno LIMIT 1";
    mysqli_query($conn, $sql);
    header('location: crudpractice.php');
}

// 3. ریکارڈ داخل کرنے (Save) یا اپڈیٹ (Update) کرنے کی لاجک
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $job = $_POST['job'];
    $sql = "INSERT INTO `phptrim` (`name`, `job`) VALUES ('$name', '$job')";
    mysqli_query($conn, $sql);
    header('location: crudpractice.php');
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $job = $_POST['job'];
    $sql = "UPDATE `phptrim` SET `name`='$name', `job`='$job' WHERE `sr.no.`=$id";
    mysqli_query($conn, $sql);
    header('location: crudpractice.php');
}

// 4. ایڈٹ کے لیے ڈیٹا نکالنا
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $res = mysqli_query($conn, "SELECT * FROM `phptrim` WHERE `sr.no.`=$id");
    if ($res && mysqli_num_rows($res) == 1) {
        $row = mysqli_fetch_array($res);
        $name = $row['name'];
        $job = $row['job'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP CRUD - Clean Version</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #333; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .highlight { background-color: #ffffcc !important; } /* سرچ کے لیے رنگ */
        .btn { padding: 5px 10px; text-decoration: none; border-radius: 3px; }
    </style>
</head>
<body>

    <h2><?php echo $update ? "EDIT RECORD" : "ADD NEW RECORD"; ?></h2>
    
    <form action="crudpractice.php" method="POST" style="background: #eee; padding: 20px; border-radius: 5px;">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        NAME: <input type="text" name="name" value="<?php echo $name; ?>" required>
        PROFESSION: <input type="text" name="job" value="<?php echo $job; ?>" required>

        <?php if ($update): ?>
            <button type="submit" name="update" style="background: orange;">UPDATE</button>
            <a href="crudpractice.php">Cancel</a>
        <?php else: ?>
            <button type="submit" name="save" style="background: green; color: white;">SAVE</button>
        <?php endif; ?>
    </form>

    <hr>

    <div style="margin: 20px 0;">
        <form action="crudpractice.php" method="GET">
            <input type="text" name="search" placeholder="Search name or job..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            <button type="submit">Search</button>
            <a href="crudpractice.php"><button type="button">Reset</button></a>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>NAME</th>
                <th>PROFESSION</th>
                <th>ACTION</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // سرچ لاجک
            $sql = "SELECT * FROM `phptrim`";
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $_GET['search'];
                $sql = "SELECT * FROM `phptrim` WHERE `name` LIKE '%$search%' OR `job` LIKE '%$search%'";
            }

            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while($row = mysqli_fetch_assoc($result)) {
                    // اگر سرچ ہو رہی ہے تو اس رو کو ہائی لائٹ کریں
                    $class = (isset($_GET['search']) && !empty($_GET['search'])) ? "class='highlight'" : "";

                    echo "<tr $class>
                            <td>". $row['sr.no.'] ."</td>
                            <td>". $row['name'] ."</td>
                            <td>". $row['job'] ."</td>
                            <td>
                                <a href='crudpractice.php?edit=" . $row['sr.no.'] . "' style='color: blue;'>Edit</a> | 
                                <a href='crudpractice.php?delete=" . $row['sr.no.'] . "' style='color: red;' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>No records found!</td></tr>";
            }
        ?>
        </tbody>
    </table>

</body>
</html>