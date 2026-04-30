<?php 
include '../includes/db.php'; 

if (isset($_POST['btn_delete'])) {
    $id = $_POST['d_id'];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?status=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Management System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body> 
    
    <nav class="navbar">
        <img src="../images/SMS.svg" id="logo" onclick="hideAllContent()">
        <button class="navbarbuttons" onclick="showSection('create')"> Create </button>
        <button class="navbarbuttons" onclick="showSection('read')"> Read </button>
        <button class="navbarbuttons" onclick="showSection('update')"> Update </button>
        <button class="navbarbuttons" onclick="showSection('delete')"> Delete </button>
    </nav>

    <section id="home" class="homecontent"> 
        <h1 class="splash">Welcome to Student Management System</h1>
        <h2 class="splash">A Project in Integrative Programming Technologies</h2>
    </section>
    
    <section id="create" class="content">
        <h1 class="contenttitle"> Insert New Student </h1>
        <form action="../includes/insert.php" method="POST">
            <label class="label">Surname</label><input type="text" name="surname" class="field" required><br/>
            <label class="label">Name</label><input type="text" name="name" class="field" required><br/>
            <label class="label">Middle name</label><input type="text" name="middlename" class="field"><br/>
            <label class="label">Address</label><input type="text" name="address" class="field"><br/>
            <label class="label">Contact</label><input type="text" 
       name="contact" 
       class="field" 
       pattern="\d{11}" 
       title="Please enter exactly 11 digits (e.g., 09123456789)" 
       maxlength="11" 
       oninput="this.value = this.value.replace(/[^0-9]/g, '')" 
       required><br/>
            <div class="action-container">
                <button type="button" class="btns" onclick="clearFields()">Clear Fields</button>
                <button type="submit" class="btns">Save</button>
            </div>
        </form>   
    </section>

    <section id="read" class="content"> 
        <h1 class="contenttitle">View Students</h1>
        <table>
            <thead>
                <tr><th>ID</th><th>Surname</th><th>Middlename</th><th>Name</th><th>Address</th><th>Contact</th></tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM students");
                while($row = $stmt->fetch()) {
                    echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['surname']}</td>
                        <td>{$row['middlename']}</td>
                        <td>{$row['name']}</td>
                        
                        <td>{$row['address']}</td>
                        
                        <td>{$row['contact_number']}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </section>

<section id="update" class="content"> 
    <h1 class="contenttitle">Update Records</h1>
    <form method="GET">
        <label class="label">Enter Student ID:</label>
        <input type="number" name="search_u" class="field" required>
        <button type="submit" class="btns" style="width:80px;">Search</button>
    </form>
    
    <?php if(isset($_GET['search_u'])): 
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$_GET['search_u']]);
        if($row = $stmt->fetch()): ?>
            <div style="margin-top:20px; display: flex; flex-direction: column; align-items: center;">
                <form action="../includes/test.php" method="POST">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <table>
                        <tr><th>Field</th><th>Update Information</th></tr>
                        <tr><td>Middlename</td><td><input type="text" name="middlename" class="field" value="<?= $row['middlename'] ?>"></td></tr>
                        <tr><td>Surname</td><td><input type="text" name="surname" class="field" value="<?= $row['surname'] ?>"></td></tr>
                        <tr><td>Name</td><td><input type="text" name="name" class="field" value="<?= $row['name'] ?>"></td></tr>
                        <tr><td>Address</td><td><input type="text" name="address" class="field" value="<?= $row['address'] ?>"></td></tr>
                        <tr><td>Contact</td><td><input type="text" name="contact" class="field" value="<?= $row['contact_number'] ?>"></td></tr>
                    </table>
                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <button type="submit" name="btn_update" class="btns">Update Record</button>
                        <button type="button" class="btns" onclick="window.location.href='index.php?section=update'" style="background-color: #95a5a6; color: white; border: none;">Cancel</button>
                    </div>
                </form>
            </div>
        <?php else: echo "<p style='color:red; margin-top:20px;'>Student ID not found.</p>"; endif; 
    endif; ?>
</section>

<section id="delete" class="content"> 
    <h1 class="contenttitle">Remove Records</h1>
    <form method="GET">
        <label class="label">Enter Student ID:</label>
        <input type="number" name="search_d" class="field" required>
        <button type="submit" class="btns" style="width:80px;">Search</button>
    </form>

    <?php if(isset($_GET['search_d'])): 
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$_GET['search_d']]);
        if($row = $stmt->fetch()): ?>
            <div style="margin-top:20px; display: flex; flex-direction: column; align-items: center;">
                <table>
                    <tr><th>ID</th><th>Surname</th><th>Name</th><th>Middle Name</th><th>Address</th><th>Contact</th></tr>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['surname'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['middlename'] ?></td>
                        <td><?= $row['address'] ?></td>
                        <td><?= $row['contact_number'] ?></td>
                    </tr>
                </table>
                <div style="display: flex; gap: 10px; margin-top: 10px;">
                    <form method="POST" style="margin: 0;">
                        <input type="hidden" name="d_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="btn_delete" class="btns" style="background-color: #e74c3c; color: white; border: none;">Delete Student</button>
                    </form>
                    <button type="button" class="btns" onclick="window.location.href='index.php?section=delete'" style="background-color: #95a5a6; color: white; border: none;">Cancel</button>
                </div>
            </div>
        <?php else: echo "<p style='color:red; margin-top:20px;'>Student ID not found.</p>"; endif; 
    endif; ?>
</section>
    <script src="script.js"></script>
</body>
</html>
