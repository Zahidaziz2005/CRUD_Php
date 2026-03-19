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

// ویری ایبلز کو خالی سیٹ کریں تاکہ ایرر نہ آئے
$update = false;
$name = "";
$job = "";
$id = 0;

// 2. ریکارڈ ڈیلیٹ کرنے کی لاجک
if (isset($_GET['delete'])) {
    $sno = $_GET['delete'];
    $sql = "DELETE FROM `phptrim` WHERE `sr.no.` = $sno LIMIT 1";
    mysqli_query($conn, $sql);
    header('location: crudpractice.php'); // ڈیلیٹ کے بعد پیج صاف کرنے کے لیے
}

// 3. نیا ریکارڈ داخل کرنے کی لاجک
if (isset($_POST['save'])) {
    $name = $_POST['name'];
    $job = $_POST['job'];
    $sql = "INSERT INTO `phptrim` (`name`, `job`) VALUES ('$name', '$job')";
    if (mysqli_query($conn, $sql)) {
        echo "<div style='color:blue;'>CONGRATULATIONS! NEW RECORD IS ADDED</div>";
    }
}

// 4. ایڈٹ کے لیے ڈیٹا نکالنے کی لاجک
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

// 5. اپڈیٹ کرنے کی لاجک
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $job = $_POST['job'];
    mysqli_query($conn, "UPDATE `phptrim` SET `name`='$name', `job`='$job' WHERE `sr.no.`=$id");
    header('location: crudpractice.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>PHP CRUD Operation</title>
    <style>
        table { width: 80%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn-delete { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <h2><?php echo $update ? "EDIT RECORD" : "ADD NEW RECORD"; ?></h2>

    <form action="crudpractice.php" method="GET" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search name or profession." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
        <button type="submit"> Search</button>
        <a href="crudpractice.php"><button type="button">Reset</button></a>
    </form>
    
    <form action="crudpractice.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>">

        NAME: <input type="text" name="name" value="<?php echo $name; ?>" required>
        PROFESSION: <input type="text" name="job" value="<?php echo $job; ?>" required>

        <?php if ($update == true): ?>
            <button type="submit" name="update" style="background: orange;">UPDATE</button>
            <a href="crudpractice.php">Cancel</a>
        <?php else: ?>
            <button type="submit" name="save">SAVE</button>
        <?php endif; ?>
    </form>

    <hr>

    <h2>Database Records</h2>
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
            // چیک کریں کہ کیا سرچ بار میں کچھ لکھا گیا ہے
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $_GET['search'];
                // LIKE % کا مطلب ہے کہ نام کے شروع، درمیان یا آخر میں کہیں بھی وہ لفظ ہو تو دکھا دو
                $sql = "SELECT * FROM `phptrim` WHERE `name` LIKE '%$search%' OR `job` LIKE '%$search%'";
            } else {
                // اگر سرچ خالی ہے تو سارا ڈیٹا دکھاؤ
                $sql = "SELECT * FROM `phptrim`";
            }

            $result = mysqli_query($conn, $sql);
            $total_found = mysqli_num_rows($result);

            if(isset($_GET['search'])) {
                echo "<p>Total <b>$total_found</b> records are found here</p>";
            }

            while($row = mysqli_fetch_assoc($result)){
                // یہاں پرانا وہائل لوپ (while loop) کا ٹیبل ڈیٹا آئے گا
                echo "<tr>
                        <td>". $row['sr.no.'] ."</td>
                        <td>". $row['name'] ."</td>
                        <td>". $row['job'] ."</td>
                        <td>
                            <a href='crudpractice.php?edit=" . $row['sr.no.'] . "' style='color: blue;'>Edit</a> | 
                            <a href='crudpractice.php?delete=" . $row['sr.no.'] . "' style='color: red;'>Delete</a>
                        </td>
                    </tr>";
            }
        ?>

        <?php
        // ... آپ کی باقی SQL لاجک یہاں آئے گی ...

        while($row = mysqli_fetch_assoc($result)){
            
            // 1. چیک کریں کہ کیا سرچ ہو رہی ہے
            $bg_color = ""; 
            if(isset($_GET['search']) && !empty($_GET['search'])) {
                // اگر سرچ ہو رہی ہے تو اسٹرائل میں ہلکا پیلا رنگ (Light Yellow) سیٹ کریں
                $bg_color = "style='background-color: #ffffcc;'"; 
            }

            // 2. اس $bg_color کو <tr> ٹیگ کے اندر ڈال دیں
            echo "<tr $bg_color>
                    <td>". $row['sr.no.'] ."</td>
                    <td>". $row['name'] ."</td>
                    <td>". $row['job'] ."</td>
                    <td>
                        <a href='crudpractice.php?edit=" . $row['sr.no.'] . "' style='color: blue;'>Edit</a> | 
                        <a href='crudpractice.php?delete=" . $row['sr.no.'] . "' style='color: red;'>Delete</a>
                    </td>
                </tr>";
        }
        ?>

         <?php
            $sql = "SELECT * FROM `phptrim`";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_assoc($result)){
                echo "<tr>
                        <td>". $row['sr.no.'] ."</td>
                        <td>". $row['name'] ."</td>
                        <td>". $row['job'] ."</td>
                        <td>
                            <a href='crudpractice.php?edit=" . $row['sr.no.'] . "' style='color: blue; text-decoration: none; font-weight: bold;'>Edit</a> | 
                            <a href='crudpractice.php?delete=" . $row['sr.no.'] . "' class='btn-delete' onclick='return confirm(\"DO YOU WANT TO DELETE\")'>Delete</a>
                        </td>
                      </tr>";
            }
        ?>
        </tbody>
    </table>

</body>
</html>